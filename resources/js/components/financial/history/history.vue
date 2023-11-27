<template>
  <div class="f-history">
    <BuildingDateRangeFilter />
    <EntriesPerPage />
    <TransactionHistory
      ref="TransactionHistory"
      :building="this.currentBuilding"
    />
    <div
      class="d-flex flex-column align-items-start flex-lg-row justify-content-between mb-27"
    >
      <div class="d-flex flex-column align-items-start flex-lg-row">
        <h3 class="h3 align-self-center mb-24 me-30">Payment history</h3>
        <div class="has-search align-self-center mb-24">
          <span class="form-control-feedback">
            <img src="/images/icons/search.png" alt="" />
          </span>
          <input type="text" class="search form-control" placeholder="Search" />
        </div>
      </div>

      <div id="download-btn" class="mb-24 px-32 py-6">
        <button class="align-self-center btn btn-primary">Download all</button>
      </div>
    </div>
    <div>
      <div class="text-start">
        <div class="table-responsive">
          <table class="border">
            <tr>
              <th></th>
              <th>Payment Invoice</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
            </tr>

            <tr class="border_bottom">
              <td>
                <div>
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />
                </div>
              </td>
              <td>
                <span class="text-medium-bold color-secondary"
                  >Invoice 0001</span
                >
              </td>
              <td>
                <span class="text-medium color-secondary">$2,406.99</span>
              </td>
              <td>
                <span class="text-medium color-secondary">04.05.2023</span>
              </td>

              <td>
                <div class="d-flex">
                  <div class="approved">Approved</div>
                  <a class="a-link ms-auto align-self-center">Download</a>
                </div>
              </td>
            </tr>

            <tr class="border_bottom">
              <td>
                <div>
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />
                </div>
              </td>
              <td>
                <span class="text-medium-bold color-secondary"
                  >Invoice 0001</span
                >
              </td>
              <td>
                <span class="text-medium color-secondary">$2,406.99</span>
              </td>
              <td>
                <span class="text-medium color-secondary">04.05.2023</span>
              </td>

              <td>
                <div class="d-flex">
                  <div class="Rejected">Rejected</div>
                  <a class="a-link ms-auto align-self-center">Download</a>
                </div>
              </td>
            </tr>

            <tr class="border_bottom">
              <td>
                <div>
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />
                </div>
              </td>
              <td>
                <span class="text-medium-bold color-secondary"
                  >Invoice 0001</span
                >
              </td>
              <td>
                <span class="text-medium color-secondary">$2,406.99</span>
              </td>
              <td>
                <span class="text-medium color-secondary">04.05.2023</span>
              </td>

              <td>
                <div class="d-flex">
                  <div class="approved">Approved</div>
                  <a class="a-link ms-auto align-self-center">Download</a>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BuildingDateRangeFilter from "../../building-date-range.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import TransactionHistory from "./transaction-history.vue";
import { ref } from "vue";

export default {
  components: {
    BuildingDateRangeFilter,
    EntriesPerPage,
    TransactionHistory,
  },
  data() {
    return {
      params: {},
      buildingIndex: 0,
      entriesPerPageSelected: null,
      dateFilterRadio: "last_seven",
      date_filter_value: "",
      currentBuilding: "",
    };
  },
  created() {},
  methods: {
    selectBuilding(index) {
      this.buildingIndex = index;
      this.selectInfo();
    },
    selectInfo() {
      this.params = {};
      this.params.buildingId = this.buildingIndex;
      this.params.date_filter_radio = this.dateFilterRadio;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.date_filter_value = this.date_filter_value;
      this.$refs.TransactionHistory.loadData();
    },
    selectEntries(index) {
      this.entriesPerPageSelected = index;
      this.selectInfo();
    },
    dateFilter() {
      this.selectInfo();
    },
    dateRange(index) {
      this.selectInfo();
    },
  },
};
</script>
