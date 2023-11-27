<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Appliance State</h3>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <select v-model="laundry_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Laundry Room (all)</option>
          <option v-for="laundry in this.$parent.currentBuilding.laundry" :value="laundry.id">
            {{ laundry.name }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="appliance_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Appliance (all)</option>
          <option v-for="appliance in this.$parent.currentBuilding.appliance" :value="appliance.id">
            {{ appliance.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Appliance
            </th>
            <th>Laundry Room
            </th>
            <th>Date
            </th>
            <th>Status
            </th>
            <th>Days in that status
            </th>
            <th>Initiated By
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium" v-if="data.laundry_machine">{{ data.laundry_machine.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium"
                    v-if="data.laundry_machine && data.laundry_machine.laundry_name">{{
                  data.laundry_machine.laundry_name.name ?? ''
                }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.action_date) }}</span>
            </td>
            <td>
              <span class="text-medium"
                    v-if="data.laundry_machine_state">{{ data.laundry_machine_state.short_description ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.days_in_state }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name }}</span>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import Vue from "vue";

import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";

export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange},
  created() {
  },
  mounted() {
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      laundry_id: "",
      appliance_id: "",
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.laundry_id = "";
        this.appliance_id = "";
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
      params.appliance_id = this.appliance_id;

      this.$http
          .post("/applianceStateAnalytics", params)
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

