<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Entry Controllers</h3>
      </div>
    </div>
    <div class="row mb-28">
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
        <select v-model="assetId"
                v-if="this.$parent.currentBuilding"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Entry Controller (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.assets" :value="optionItem.id">
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
              Address
              <Sorting :sortColumn="'buildings.address1'"/>
            </th>
            <th>Entry Controller Name
              <Sorting :sortColumn="'assets.name'"/>
            </th>
            <th>Unit #
              <Sorting :sortColumn="'units.unit_no'"/>
            </th>
            <th>Action
              <Sorting :sortColumn="'action_name'"/>
            </th>
            <th>Date
              <Sorting :sortColumn="'triggered_at'"/>
            </th>
            <th>Performed By
              <Sorting :sortColumn="'triggered_by'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.address1 ?? '' }} , {{ data.city ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.asset">{{ data.asset.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.unit">{{ data.unit.unit_no ?? '' }} </span>
            </td>
            <td>
              <span class="text-medium">{{ data.action_name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.triggered_at) }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.triggered_by">
                {{ data.triggered_by.name }}
              </span>
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
import SearchKeyword from "../../search-keyword.vue";

export default {
  components: {
    Sorting,
    EntriesPerPage,
    BuildingDateRange
    , SearchKeyword},
  created() {
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      assetId: "",
      unitId: "",
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
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;
      params.assetId = this.assetId;
      params.unitId = this.unitId;

      this.$http
          .post("/entryControllerAnalytics", params)
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

