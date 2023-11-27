<template>
  <div class="f-summary">
    <div>
      <div class="row mb-28">
        <div class="col-lg-3 col-md-6">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Building</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="building"
                label="name"
                track-by="id"
                :options="buildings"
                :multiple="false"
                placeholder=""
                @input="this.loadData"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-6">
          <p class="text-normal-bold-md mb-12 color-secondary">Cart Type</p>
          <div class="d-flex flex-row flex-wrap mb-28 building-date-range">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                :class="{ active: cartRadio === 2 }"
                v-on:click="cartFilter(2)"
            >Amenities</span
            >
            <span
                class="text-normal-bold-md building-badge select-cursor"
                :class="{ active: cartRadio === 3 }"
                v-on:click="cartFilter(3)"
            >Rent</span
            >
            <span
                class="text-normal-bold-md building-badge select-cursor"
                :class="{ active: cartRadio === 4 }"
                v-on:click="cartFilter(4)"
            >Laundry</span
            >
          </div>
        </div>
      </div>
      <BuildingDateRange ref="BuildingDateRange"></BuildingDateRange>
      <div class="mb-32 text-end text-medium color-primary">
        <span class="mb-0 border-bottom">Print report</span>
      </div>

      <div class="row gy-3 mb-34" v-if="this.summaryData">
        <div class="col-lg-3">
          <div class="card expense-card">
            <p class="text-normal-bold-md mb-4">Total Expense</p>
            <hr>

            <div class="detail-section mt-16" v-if="isShowExpense">
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_laundry_cart) || !this.showLaundry }">
                <p class="text-normal-bold-md mb-4">Laundry</p>
                <h4 v-if="this.summaryData.expenseLaundry" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.expenseLaundry) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && this.summaryData.building.disable_amenities_subscription) || !this.showAmenities }">
                <p class="text-normal-bold-md mb-4">
                  Amenities
                </p>
                <h4 v-if="this.summaryData.expenseAmenities" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.expenseAmenities) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_rent_cart) || !this.showRent }">
                <p class="text-normal-bold-md mb-4">Rent</p>
                <h4 v-if="this.summaryData.expenseRent" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.expenseRent) }}</h4>
                <h4 class="h4-bold" v-else>-</h4></div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card income-card">
            <p class="text-normal-bold-md mb-4">Total Income (Est.)</p>
            <hr>

            <div class="detail-section mt-16" v-if="isShowExpense">
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_laundry_cart) || !this.showLaundry }">
                <p class="text-normal-bold-md mb-4">Laundry</p>
                <h4 v-if="this.summaryData.incomeLaundry" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.incomeLaundry) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && this.summaryData.building.disable_amenities_subscription) || !this.showAmenities }">
                <p class="text-normal-bold-md mb-4">
                  Amenities
                </p>
                <h4 v-if="this.summaryData.incomeAmenities" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.incomeAmenities) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_rent_cart) || !this.showRent }">
                <p class="text-normal-bold-md mb-4">Rent</p>
                <h4 v-if="this.summaryData.incomeRent" class="h4-bold">
                  {{ formatMoneyWithCommas(this.summaryData.incomeRent) }}</h4>
                <h4 class="h4-bold" v-else>-</h4></div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="card collected">
            <p class="text-normal-bold-md mb-4 color-secondary">Collected</p>
            <hr>

            <div class="detail-section mt-16" v-if="isShowCollected">
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_laundry_cart) || !this.showLaundry }">
                <p class="text-normal-bold-md mb-4 color-secondary">Laundry</p>
                <h4 v-if="this.summaryData.collectedLaundry" class="h4-bold color-secondary">
                  {{ formatMoneyWithCommas(this.summaryData.collectedLaundry) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && this.summaryData.building.disable_amenities_subscription) || !this.showAmenities }">
                <p class="text-normal-bold-md mb-4 color-secondary">
                  Amenities
                </p>
                <h4 v-if="this.summaryData.collectedAmenities" class="h4-bold color-secondary">
                  {{ formatMoneyWithCommas(this.summaryData.collectedAmenities) }}</h4>

                <h4 class="h4-bold" v-else>-</h4></div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_rent_cart) || !this.showRent }">
                <p class="text-normal-bold-md mb-4 color-secondary">Rent</p>
                <h4 v-if="this.summaryData.collectedRent" class="h4-bold color-secondary">
                  {{ formatMoneyWithCommas(this.summaryData.collectedRent) }}</h4>
                <h4 class="h4-bold" v-else>-</h4></div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="card uncollected">
            <p class="text-normal-bold-md mb-4 color-secondary">Uncollected</p>
            <hr>

            <div class="detail-section mt-16" v-if="isShowUncollected">
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_laundry_cart) || !this.showLaundry }">
                <p class="text-normal-bold-md mb-4 color-secondary">Laundry</p>
                <h4 class="h4-bold color-danger">{{ formatMoneyWithCommas(this.summaryData.uncollectedLaundry) }}</h4>
              </div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && this.summaryData.building.disable_amenities_subscription) || !this.showAmenities }">
                <p class="text-normal-bold-md mb-4 color-secondary">
                  Amenities
                </p>
                <h4 class="h4-bold color-danger">{{ formatMoneyWithCommas(this.summaryData.uncollectedAmenities) }}</h4>
              </div>
              <div class="mb-4"
                   :class="{ hide: (this.summaryData.building && !this.summaryData.building.enable_rent_cart) || !this.showRent }">
                <p class="text-normal-bold-md mb-4 color-secondary">Rent</p>
                <h4 class="h4-bold color-danger">{{ formatMoneyWithCommas(this.summaryData.uncollectedRent) }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <last-transactions ref="lastTransactions"></last-transactions>
    </div>
  </div>
</template>
<script>
import BuildingDateRange from "../../building-date-range.vue";
import lastTransactions from "./lastTransactions.vue";

export default {
  components: {BuildingDateRange, lastTransactions},
  data() {
    return {
      buildings: [],
      building: '',
      summaryData: null,
      dataArray: [],
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      params: {},
      isShowCollected: true,
      isShowIncome: true,
      isShowUncollected: true,
      isShowExpense: true,
      showRent: true,
      showAmenities: true,
      showLaundry: true,
      cartRadio: 2,
    };
  },
  created() {
    this.getBuildings();
  },
  methods: {
    cartFilter(cartRadio) {
      this.cartRadio = cartRadio;
      this.loadData();
    },
    loadData() {
      this.showAmenities = this.cartRadio === null || this.cartRadio === 2;
      this.showRent = this.cartRadio === null || this.cartRadio === 3;
      this.showLaundry = this.cartRadio === null || this.cartRadio === 4;
      this.$refs.lastTransactions.loadData();
      let params = {};
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.buildingId = this.building ? this.building.id : 0;
      params.cartRadio = this.cartRadio;
      this.$http
          .post("/loadFinanceSummary", params)
          .then((response) => {
            this.summaryData = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            this.buildings.unshift({"id": 0, "name": "Portfolio Wide"});
            if (this.building == '') {
              this.building = this.buildings.find(obj => obj.id === this.$gate.building_id);
            }
          })
          .catch((error) => {
            console.error(error);
          });
    },
    showDetail(value) {
      if (value === "c") {
        this.isShowCollected = !this.isShowCollected;
      }
      if (value === "expense") {
        this.isShowExpense = !this.isShowExpense;
      }
      if (value === "i") {
        this.isShowIncome = !this.isShowIncome;
      }
      if (value === "u") {
        this.isShowUncollected = !this.isShowUncollected;
      }
    },
  },
};
</script>
<style :scoped>
.hide {
  display: none;
}
</style>
