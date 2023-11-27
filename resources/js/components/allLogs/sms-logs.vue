<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Sms Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Timestamp
              <Sorting :sortColumn="'timestamp'"/>
            </th>
            <th>
              User
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Mobile
              <Sorting :sortColumn="'sms_log.mobile'"/>
            </th>
            <th>
              Type
            </th>
            <th>
              Message
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ formatDateTime(timestamp) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.firstname }} {{ data.lastname }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.s_mobile }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.is_incoming === 1 ? 'Incoming' : 'Outgoing' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.message }}</span>
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
          .post("/smsAnalytics", params)
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
