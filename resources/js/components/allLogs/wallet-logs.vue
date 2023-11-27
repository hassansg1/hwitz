<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Wallet Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Wallet
              <Sorting :sortColumn="'wallet_id'"/>
            </th>
            <th>
              Action Type
              <Sorting :sortColumn="'change_flag'"/>
            </th>
            <th>
              Action Details
              <Sorting :sortColumn="'wallet_logs'"/>
            </th>
            <th>
              Timestamp
              <Sorting :sortColumn="'created'"/>
            </th>
            <th>
              Performed By
              <Sorting :sortColumn="'created_by'"/>
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
              <span class="text-medium" v-if="data.wallet">{{ data.wallet.card_number }} {{
                  data.wallet.nick_name
                }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.change_flag }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.wallet_logs }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.created) }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.created_by">{{ data.created_by.name }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.ip_address }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.user_agent }}</span>
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
          .post("/walletLogs", params)
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
