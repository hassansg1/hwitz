<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Payment Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              User
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Payment Type
            </th>
            <th>
              Amount
            </th>
            <th>
              Payment Link Id
            </th>
            <th>
              Origin
            </th>
            <th>
              IP address
            </th>
            <th>
              User agent
            </th>
            <th>
              Status
            </th>
            <th>
              Performed By
            </th>
            <th>
              Timestamp
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">
                <span v-if="data.user">{{ data.user.name ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.payment_type }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatMoneyWithCommas(data.amount) }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.payment_link_id }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.origin }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.ip_address }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.user_agent }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span v-if="data.status === 1">Success</span>
                <span v-else>Failure</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ data.user.name }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatDateTime(data.triggered_at) }}</span>
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
          .post("/paymentLogs", params)
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
