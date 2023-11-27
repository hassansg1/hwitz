<template>
  <div class="packages-section">
    <div class="row gy-3 mb-28">
      <div class="col-lg-3">
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="searchKeyword"
            @change="loadData()"
        />
      </div>
      <div class="col-lg-3">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize"
          >Select Status</label
          >
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              @input="loadData()"
              v-model="lockerStatus"
              tag-placeholder="Add this as new tag"
              placeholder=""
              label="name"
              track-by="id"
              :options="lockerStatuses"
              :multiple="false"
          ></multiselect>
        </div>
      </div>
    </div>
    <entries-per-page></entries-per-page>
    <div class="mb-28">
      <div class="text-start">
        <div class="table-responsive">
          <table class="pb-5">
            <tr>
              <th>Label
                <Sorting :sortColumn="'label'"/>
              </th>
              <th>Resident</th>
              <th>Status</th>
              <th>Name/Location
                <Sorting :sortColumn="'locker_screen_id'"/>
              </th>
              <th>Action</th>
            </tr>

            <tr class="border_bottom" v-for="data in this.dataArray.data">
              <td>
                <span class="text-medium-bold color-primary">{{ data.label }}</span>
              </td>
              <td>
                <div class="d-flex" v-if="data.package && data.package.receiver">
                  <div class="me-9">
                    <div class="position-relative text-center">
                      <div class="avatar-sm">
                        <img :src="data.package.receiver.profile_picture" class="avatar-img" alt=""/>
                      </div>
                    </div>
                  </div>
                  <div class="align-self-center">
                    <div class="text-medium color-secondary-dark">{{ data.package.receiver.name }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div v-if="data.package" class="status occupied">Occupied</div>
                <div v-else class="status vacant">Vacant</div>
              </td>
              <td>
                <div>
                  <span class="text-medium color-secondary" v-if="data.locker_screen">{{
                      data.locker_screen.name
                    }}</span>
                </div>
              </td>

              <td>
                <div v-if="data.package">
                  <button
                      class="btn btn-primary"
                      id="dropdownPackages"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                  >
                    Action
                  </button>
                  <div class="dropdown">
                    <ul style="cursor: pointer"
                        class="dropdown-menu dropdown-menu-end border-standered"
                        aria-labelledby="dropdownPackages"
                    >
                      <li class="px-2 py-1 border-bottom text-sm-bold color-secondary">
                        Action
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold" @click="openLocker(data.package.id,1)">
                        Open (maintenance)
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold" @click="openLocker(data.package.id,0)">
                        Open (mark vacant)
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold" @click="openLocker(data.package.id,1)">
                        Open (keep occupied)
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold"
                          @click="lockerPackageExtendedForOneDay(data.package.id,1)">
                        Extend 1 day: No Limit
                      </li>
                      <li
                          class="px-2 py-1 border-bottom text-sm-bold"
                          @click="viewExtensionHistory(data.package.id)">
                        View Extension History
                      </li>
                      <li
                          class="px-2 py-1 border-bottom text-sm-bold"
                          @click="alertResident(data)">
                        Alert Resident
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <pagination :pagination="dataArray"></pagination>
  </div>
</template>

<script>
import ExtensionHistoryModal from "./extension-history-modal.vue";
import AlertResident from "./alert-resident.vue";

export default {
  components: {
    ExtensionHistoryModal,
    AlertResident,
  },
  created() {
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      if (this.$parent.activeTab === 'manageLocker')
        this.loadData();
    }
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      searchKeyword: '',
      entriesPerPageSelected: null,
      lockerStatus: null,
      lockerStatuses: [
        {"name": "Vacant", "id": "vacant"},
        {"name": "Abandoned", "id": "abandoned"},
        {"name": "Occupied", "id": "occupied"},
      ],
    };
  },
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.buildingId = this.building_id;
      if (this.building_id === undefined) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No building selected.",
        });
        return;
      }

      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.searchKeyword = this.searchKeyword;
      params.totalItems = this.entriesPerPageSelected;
      if (this.lockerStatus)
        params.lockerStatus = this.lockerStatus.id;
      this.$http
          .post("/lockers", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.dataArray = response.data;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message,
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    openLocker(packageId, keepOccupied) {
      this.showLoader();
      let params = {packageId: packageId, keepOccupied: keepOccupied, buildingId: this.building_id};
      this.$http
          .post("/openLocker", params)
          .then((response) => {
            Toast.fire({
              icon: response.data.icon,
              title: response.data.message,
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    lockerPackageExtendedForOneDay(packageId) {
      this.showLoader();
      let params = {packageId: packageId, hours: 24, manager: 1, buildingId: this.building_id};
      this.$http
          .post("/lockerPackageExtension", params)
          .then((response) => {
            Toast.fire({
              icon: response.data.icon,
              title: response.data.message,
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    viewExtensionHistory(packageId) {
      this.$parent.$refs.extensionHistory.viewExtensionHistory(packageId);
    },
    alertResident(locker) {
      this.$parent.$refs.alertResident.alertResident(locker);
    }
  }
};
</script>
<style :scoped>
.extension_btn {
  /*display: none;*/
}
</style>