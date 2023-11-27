<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Laundry</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Date
              <Sorting :sortColumn="'timestamp'"/>
            </th>
            <th>
              Resident: FOB#
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>Go to: address</th>
            <th>Laundry Rm: Appliance
              <Sorting :sortColumn="'laundrymachine_id'"/>
            </th>
            <th>Transaction type
              <Sorting :sortColumn="'transaction_type_id'"/>
            </th>
            <th>Charge
              <Sorting :sortColumn="'amount'"/>
            </th>
            <th>Running balance
              <Sorting :sortColumn="'balance'"/>
            </th>
            <th>Image</th>
            <th>Video</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ formatDateTime(data.timestamp) }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name ?? '' }}
              <span v-if="data.user.token">
                <br>
                FOB# {{ dataArray.user.token.card_id ?? '' }}
              </span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <a target="_blank" href="">User Profile</a>
              </span>
            </td>
            <td>
              <span v-if="data.laundry" class="text-medium">
                {{ data.laundry.name }}
              </span>
              <span v-if="data.laundry_machine" class="text-medium">
                <br>
                {{ data.laundry_machine.name }}
              </span>
            </td>
            <td>
              <span v-if="data.transaction_type" class="text-medium">
                {{ data.transaction_type.transaction_type }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ formatMoneyWithCommas(data.amount) }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ formatMoneyWithCommas(data.balance) }}
              </span>
            </td>
            <ImageRow :data="data"></ImageRow>
            <VideoRow :data="data"></VideoRow>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
    <FiveImagesModal :type="'laundry'" ref="FiveImagesModal"></FiveImagesModal>
    <EntryVideoModal :type="'laundry'" ref="EntryVideoModal"></EntryVideoModal>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import FiveImagesModal from "../five-images-modal.vue";
import EntryVideoModal from "../entry-video-modal.vue";
import ImageRow from "../image-row.vue";
import VideoRow from "../video-row.vue";

export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange, FiveImagesModal, EntryVideoModal, ImageRow, VideoRow},
  created() {
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
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

      this.$http
          .post("/laundryAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
  },

};
</script>

