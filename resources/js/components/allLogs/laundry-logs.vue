<template>
  <div>
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Laundry Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Appliance
            </th>
            <th>Laundry Room
            </th>
            <th>Date
            </th>
            <th>Status
            </th>
            <th>Days in that status
            </th>
            <th>Initiated By
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium" v-if="data.laundry_machine">{{ data.laundry_machine.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium"
                    v-if="data.laundry_machine && data.laundry_machine.laundry_name">{{
                  data.laundry_machine.laundry_name.name ?? ''
                }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.action_date) }}</span>
            </td>
            <td>
              <span class="text-medium"
                    v-if="data.laundry_machine_state">{{ data.laundry_machine_state.short_description ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.days_in_state }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name }}</span>
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
      laundry_id: "",
      appliance_id: "",
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.laundry_id = "";
        this.appliance_id = "";
      }
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.laundry_id = this.laundry_id;
      params.appliance_id = this.appliance_id;

      this.$http
          .post("/applianceStateAnalytics", params)
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

