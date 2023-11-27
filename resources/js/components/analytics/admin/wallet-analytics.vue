<template>
  <div>
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Wallet</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Nickname
              <Sorting :sortColumn="'name_on_card'" />
            </th>
            <th>
              Payment Type
              <Sorting :sortColumn="'payment_type'" />
            </th>
            <th>Details</th>
            <th>Type</th>
            <th>Activity info</th>
            <th>Building Assigned</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.nick_name ?? "" }}</span>
            </td>
            <td>
              <span class="text-medium">{{
                data.payment_type === 1 ? "Credit Card" : "ACH"
              }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.payment_type === 1">
                <span class="text-medium" v-if="data.card_type === 4"
                  >Visa :</span
                >
                <span class="text-medium" v-if="data.card_type === 5"
                  >Master :</span
                >
                <span class="text-medium" v-if="data.card_type === 6"
                  >Discover :</span
                >
              </span>
              <span class="text-medium" v-else>
                <span class="text-medium" v-if="data.check_type === 1"
                  >Checking :</span
                >
                <span class="text-medium" v-else>Savings :</span>
              </span>
              <span class="text-medium">{{ data.card_number }}</span>
              <br />
              <span class="text-medium">Exp : {{ data.card_expiry }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.wallet_relation">
                {{
                  data.wallet_relation.default_area_status === 4
                    ? "Payable"
                    : "Receivable"
                }}
              </span>
            </td>

            <td>
              <span
                style="cursor: pointer"
                v-on:click="walletActivityModal(data.id)"
                class="text-medium-md color-primary"
              >
                View
              </span>
            </td>
            <td>
              <span class="text-medium" v-if="data.buildings">
                <li v-for="building in data.buildings">
                  {{ building.name }}
                </li>
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <WalletActivityModal ref="walletActivityModal" />
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import Vue from "vue";
import WalletActivityModal from "./wallet-activity-modal.vue";

import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import SearchKeyword from "../../search-keyword.vue";
export default {
  components: { Sorting, WalletActivityModal, EntriesPerPage, BuildingDateRange, SearchKeyword },
  created() {},
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
      params.current_page = pageNumber;
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;

      this.$http
        .post("/walletAnalytics", params)
        .then((response) => {
          this.dataArray = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    },

    walletActivityModal(id) {
      this.$refs.walletActivityModal.walletActivityModal(id);
      $("#walletActivityModal").modal("show");
    },
  },
};
</script>
