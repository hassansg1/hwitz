<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Cron Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Cron Job Name
              <Sorting :sortColumn="'cron_category_id'"/>
            </th>
            <th>
              Building
              <Sorting :sortColumn="'building_id'"/>
            </th>
            <th>
              Unit
              <Sorting :sortColumn="'unit_id'"/>
            </th>
            <th>
              User
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Comment
            </th>
            <th>
              Notes
            </th>
            <th>
              Cron Run Date / Time
              <Sorting :sortColumn="'ran_at'"/>
            </th>
            <th>
              Created At
              <Sorting :sortColumn="'created_at'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom" :class="'log_status_'+data.status">
            <td>
              <span class="text-medium">{{ data.category.value ?? '' }}</span>
            </td>
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
                <span v-if="data.user">{{ data.user.name ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.comment }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.comment }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatDateTime(data.ran_at) }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatDateTime(data.created_at) }}</span>
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
          .post("/cronLogs", params)
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
<style :scoped>
.log_status_0 {
  color: #28a745;
  background-color: #fbfffc;
  font-weight: bold;
}

.log_status_1 {
  color: red;
  background-color: #fcf5f5;
  font-weight: bold;
}

.log_status_2 {
  color: #bd9210;
  background-color: #f2efe7;
  font-weight: bold;
}
</style>