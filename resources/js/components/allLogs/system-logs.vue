<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">System Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Building
              <Sorting :sortColumn="'building_id'"/>
            </th>
            <th>
              Unit
              <Sorting :sortColumn="'unit_id'"/>
            </th>
            <th>
              Performed By
              <Sorting :sortColumn="'triggered_by'"/>
            </th>
            <th>
              Action
              <Sorting :sortColumn="'action_name'"/>
            </th>
            <th>
              Timestamp
              <Sorting :sortColumn="'triggered_at'"/>
            </th>
            <th>
              Ip Address
            </th>
            <th>
              User Agent
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">
                <span v-if="data.building">{{ data.building.name ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span v-if="data.unit">{{ data.unit.unit_no ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span v-if="data.triggered_by">{{ data.triggered_by.name ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.action_name }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ formatDateTime(data.triggered_at) }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.ip_address }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.user_agent }}
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>
import Sorting from "../sorting.vue";
import EntriesPerPage from "../entries-per-page.vue";
import SearchKeyword from "../search-keyword.vue";

export default {
  components: {
    Sorting,
    EntriesPerPage,
    SearchKeyword
  },
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
  expose: ["loadData"],
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;

      this.$http
          .post("/systemLogs", params)
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
