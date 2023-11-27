<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Activity Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Log Name
              <Sorting :sortColumn="'log_name'"/>
            </th>
            <th>
              Description
              <Sorting :sortColumn="'description'"/>
            </th>
            <th>
              Subject Id
              <Sorting :sortColumn="'subject_id'"/>
            </th>
            <th>
              Subject Type
              <Sorting :sortColumn="'subject_type'"/>
            </th>
            <th>
              Causer
              <Sorting :sortColumn="'causer_id'"/>
            </th>
            <th>
              Timestamp
              <Sorting :sortColumn="'created_at'"/>
            </th>
            <th>
              Properties
              <Sorting :sortColumn="'properties'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">
                {{ data.log_name }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.description }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.subject_id }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.subject_type }}
              </span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">
                {{ data.user.name }}
              </span>
              <span v-else>-</span>
            </td>
            <td>
              <span class="text-medium">
                {{ formatDateTime(data.created_at) }}
              </span>
            </td>
            <td>
              <span class="text-medium">
                {{ data.properties }}
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
          .post("/activityLogs", params)
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
