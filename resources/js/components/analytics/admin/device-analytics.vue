<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Device</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="mb-20">
              <div class="table-responsive mb-28">
                <table>
                  <tr>
                    <th>Devices</th>
                    <th>Select Device</th>
                  </tr>
                  <tr
                    :class="{ active_device_filter: device === devices.id }"
                    style="background: white"
                    v-for="(devices, index) in devicesArray.devices"
                    class="border_bottom"
                  >
                    <td>
                      {{ index }}
                    </td>
                    <td>
                      <select
                        class="form-select device_drop_down"
                        aria-label="Default select example"
                        :id="'device_' + devices.id"
                        @change="setFilters(devices.id)"
                      >
                        <option value="" selected>
                          Select {{ index }} Device
                        </option>
                        <option
                          v-for="elements in devices.items"
                          :value="elements.id"
                        >
                          {{ elements.name }}
                        </option>
                      </select>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="table-responsive mb-28">
            <table>
              <tr>
                <th>
                  Timestamp
                  <Sorting :sortColumn="'timestamp'" />
                </th>
                <th>
                  Uptime
                  <Sorting :sortColumn="'uptime'" />
                </th>
                <th>
                  Status
                  <Sorting :sortColumn="'status'" />
                </th>
              </tr>
              <tr v-for="data in dataArray.data" class="border_bottom">
                <td>
                  <span class="text-medium">{{
                    formatDateTime(data.timestamp)
                  }}</span>
                </td>
                <td>
                  <span class="text-medium">{{ data.up_time_formatted }}</span>
                </td>
                <td>
                  <span
                    class="text-medium"
                    v-if="data.status"
                    style="color: green; font-weight: bold"
                    >Online</span
                  >
                  <span
                    class="text-medium"
                    v-else
                    style="color: red; font-weight: bold"
                    >Offline</span
                  >
                </td>
              </tr>
            </table>
          </div>
          <span>
            <pagination :pagination="dataArray"></pagination>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import Vue from "vue";

import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
export default {
  components: { Sorting , EntriesPerPage, BuildingDateRange},
  created() {},
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      devicesArray: {},
      device: null,
      deviceId: null,
    };
  },
  expose: ["loadData"],
  methods: {
    setFilters(device) {
      this.device = device;
      this.deviceId = $("#device_" + device).val();
      $(".device_drop_down").val("");
      $("#device_" + device).val(this.deviceId);
      this.loadData();
    },
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
      params.device = this.device;
      params.deviceId = this.deviceId;

      this.$http
        .post("/deviceAnalytics", params)
        .then((response) => {
          this.dataArray = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    },
    loadFiltersForDevices() {
      this.showLoader();
      let params = {};
      params.buildingId = this.$parent.buildingIndex;

      this.$http
        .post("/loadFiltersForDevices", params)
        .then((response) => {
          this.devicesArray = response.data;
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
