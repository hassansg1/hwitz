<template>
  <div class="f-summary">
    <div>

      <h3 class="h3 mb-34">Last Transactions</h3>
      <BuildingDateRange ref="BuildingDateRange"></BuildingDateRange>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>Transaction Type</th>
              <th>Cart Type</th>
              <th>ACH Status</th>
              <th>Reconciled By</th>
              <th>Transaction Date</th>
              <th>Account</th>
            </tr>

            <tr class="border_bottom" v-for="data in this.dataArray.data">
              <td>
                <span v-if="data.receiver_id">Receivable</span>
                <span v-else>Payable</span>
              </td>
              <td class="text-medium color-secondary">{{ data.cart_label }}</td>

              <td v-if="data.receiver_id">
                <span v-if="data.is_cleared" class="reconciled">Reconciled</span>
                <span v-else class="pending">Pending</span>
              </td>
              <td v-else>-</td>

              <td v-if="data.receiver_id">
                <span v-if="data.is_cleared && data.reconciled_person">{{
                    data.reconciled_person.name
                  }}</span>
                <span style="font-size: 12px">{{ formatDateTime(data.reconciled_timestamp) }}</span>
              </td>
              <td v-else>-</td>

              <td>
                <span class="text-medium color-secondary">{{ formatDate(data.created_at) }}</span>
              </td>
              <td>
                <span v-if="data.amount < 0" class="text-medium-bold color-danger">{{
                    formatMoneyWithCommas(data.amount * -1)
                  }}</span>
                <span v-else class="text-medium-bold color-primary">{{ formatMoneyWithCommas(data.amount) }}</span>
              </td>
            </tr>
          </table>
        </div>
        <span>
          <pagination :pagination=" dataArray"></pagination>
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import BuildingDateRange from "../../building-date-range.vue";
import EntriesPerPage from "../../entries-per-page.vue";

export default {
  components: {BuildingDateRange, EntriesPerPage},
  data() {
    return {
      dataArray: [],
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      params: {},
    };
  },
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.building ? this.$parent.building.id : 0;
      params.cartRadio = this.$parent.cartRadio;
      this.$http
          .post("/financeLastTransactions", params)
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
