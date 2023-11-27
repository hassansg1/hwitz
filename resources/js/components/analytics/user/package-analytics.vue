<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Packages</h3>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <select v-model="lockerId"
                v-if="this.$parent.currentBuilding"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Locker (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.lockers" :value="optionItem.id">
            {{ optionItem.label }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="unitId"
                v-if="this.$parent.currentBuilding"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Unit (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.units" :value="optionItem.id">
            {{ optionItem.unit_no }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="userId"
                v-if="this.$parent.currentBuilding"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select User (all)</option>
          <template v-for="optionItems in this.$parent.currentBuilding.units">
            <option v-if="user" v-for="user in optionItems.users" :value="user.id">
              {{ optionItems.unit_no }} - {{ user.name }}
            </option>
          </template>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="providerId"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Provider (all)</option>
          <option v-for="optionItem in this.mailServiceProviders" :value="optionItem.id">
            {{ optionItem.name }}
          </option>
        </select>
      </div>

    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Locker
              <Sorting :sortColumn="'locker_id'"/>
            </th>
            <th>
              Provider
              <Sorting :sortColumn="'provider_id'"/>
            </th>
            <th>
              Picker
              <Sorting :sortColumn="'receiver_id'"/>
            </th>
            <th>
              Picked
              <Sorting :sortColumn="'is_received'"/>
            </th>
            <th>
              Picked up by
              <Sorting :sortColumn="'received_by'"/>
            </th>
            <th>
              Drop off at
              <Sorting :sortColumn="'arrived_at'"/>
            </th>
            <th>
              Picked up At
              <Sorting :sortColumn="'received_by'"/>
            </th>
            <th>Drop Off Video</th>
            <th>Pickup Video</th>
            <th>Package Picture</th>
            <th>Drop Off Picture</th>
            <th>Delivery Person Picture</th>
            <th>Pickup (room) Picture</th>
            <th>Pickup Person Picture</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium" v-if="data.locker">{{ data.locker.label ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.provider">{{ data.provider.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.receiver">{{ data.receiver.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.is_received === 1 ? 'Yes' : 'No' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.received_by">{{ data.received_by.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.arrived_at !== ''">{{ formatDateTime(data.arrived_at) }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.received_at !== ''">{{ formatDateTime(data.received_at) }}</span>
            </td>
            <td>
              <a href="javascript:void(0)"
                 @click="showPackageVideo(data.id,'dropOff')">Play Video</a></td>
            <td>
              <a href="javascript:void(0)"
                 @click="showPackageVideo(data.id,'pickUp')">Play Video</a>
            </td>
            <td>
              <div class="camera-pic-container position-relative pt-7"
                   @click="showPackagePicture('/getBlobPhoto/'+data.id +'/package_picture')">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.id +'/package_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
              </div>
            </td>
            <td>
              <div class="camera-pic-container position-relative pt-7"
                   @click="showPackagePicture('/getBlobPhoto/'+data.id +'/dropoff_picture')">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.id +'/dropoff_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
              </div>
            </td>
            <td>
              <div class="camera-pic-container position-relative pt-7"
                   @click="showPackagePicture('/getBlobPhoto/'+data.id +'/delivery_person_picture')">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.id +'/delivery_person_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
              </div>
            </td>
            <td>
              <div class="camera-pic-container position-relative pt-7"
                   @click="showPackagePicture('/getBlobPhoto/'+data.id +'/pickup_picture')">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.id +'/pickup_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
              </div>
            </td>
            <td>
              <div class="camera-pic-container position-relative pt-7"
                   @click="showPackagePicture('/getBlobPhoto/'+data.id +'/pickup_person_picture')">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.id +'/pickup_person_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
              </div>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
    <PackageImageModal ref="PackageImageModal"/>
    <PackageVideoModal ref="PackageVideoModal"/>

  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import PackageImageModal from "../admin/package-image-modal.vue";
import PackageVideoModal from "../admin/package-video-modal.vue";

export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange, PackageImageModal, PackageVideoModal},
  created() {
    this.loadMailServiceProviders();
  },
  mounted() {
  },
  data() {
    return {
      dataArray: {},
      mailServiceProviders: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      providerId: "",
      lockerId: "",
      userId: "",
      unitId: "",
      amazonUrl: "",
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.unitId = "";
        this.userId = "";
        this.lockerId = "";
        this.providerId = "";
      }
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;
      params.laundry_id = this.laundry_id;
      params.providerId = this.providerId;
      params.lockerId = this.lockerId;
      params.userId = this.userId;
      params.unitId = this.unitId;

      this.$http
          .post("/packageAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadMailServiceProviders() {
      this.$http
          .get("/loadMailServiceProviders")
          .then((response) => {
            this.mailServiceProviders = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    showPackagePicture(url) {
      this.$refs.PackageImageModal.imageUrl = url;
      $('#packageImageModal').modal('show');
    },
    showPackageVideo(packageId, type) {
      this.$refs.PackageVideoModal.loadPackageDetail(packageId, type);
    }
  },

};
</script>

