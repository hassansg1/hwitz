<template>
  <div>
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Login Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Datetime
              <Sorting :sortColumn="'attempt_at'"/></th>
            <th>Success
              <Sorting :sortColumn="'success'"/></th>
            <th>IP Address
              <Sorting :sortColumn="'ip_address'"/></th>
            <th>User Agent</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{formatDateTime(data.attempt_at) }}</span>
            </td>
            <td>
              {{ data.success ? "Yes" : "No" }}
            </td>
            <td>
              {{ data.ip_address }}
            </td>
            <td>
              {{ data.user_agent }}
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

import EntriesPerPage from "../entries-per-page.vue";

export default {
  components: {EntriesPerPage},
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
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;

      this.$http
          .post("/loginLogs", params)
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

