<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Task</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Name
              <Sorting :sortColumn="'name'"/>
            </th>
            <th>
              Unit
              <Sorting :sortColumn="'unit_id'"/>
            </th>
            <th>
              Status
              <Sorting :sortColumn="'task_state'"/>
            </th>
            <th>
              Assigned To
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Created By
              <Sorting :sortColumn="'created_by'"/>
            </th>
            <th>
              Created At
              <Sorting :sortColumn="'created'"/>
            </th>
            <th>
              Viewed At
              <Sorting :sortColumn="'viewed_on'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.unit">{{ data.unit.unit_no ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.deleted_at">Deleted</span>
              <span class="text-medium" v-else-if="data.task_state === 'O'">Pending</span>
              <span class="text-medium" v-else-if="data.task_state === 'I'">In Progress</span>
              <span class="text-medium" v-else-if="data.task_state === 'C'">Completed</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.created_by">{{ data.created_by.name ?? 'System' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.created) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.viewed_on) }}</span>
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
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import SearchKeyword from "../../search-keyword.vue";

export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange, SearchKeyword},
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
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;

      this.$http
          .post("/taskAnalytics", params)
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

