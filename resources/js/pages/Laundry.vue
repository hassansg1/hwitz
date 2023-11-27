<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white mb-28" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="laundry-dashboard-tab"
              data-bs-toggle="pill"
              data-bs-target="#laundry-dashboard"
              type="button"
              role="tab"
              aria-controls="laundry-dashboard"
              aria-selected="true"
          >
            Dashboard
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              id="appliance-profile-template-tab"
              data-bs-toggle="pill"
              data-bs-target="#appliance-profile-template"
              type="button"
              role="tab"
              aria-controls="appliance-profile-template"
              aria-selected="false"
              @click="loadApplianceProfileData"
          >
            Appliance Profile Template
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              id="laundry-advertisement-tab"
              data-bs-toggle="pill"
              data-bs-target="#laundry-advertisement"
              type="button"
              role="tab"
              aria-controls="laundry-advertisement"
              aria-selected="false"
              @click="getAdvertisementData"
          >
            Advertisement
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              id="laundry-transactions-tab"
              data-bs-toggle="pill"
              data-bs-target="#laundry-transactions"
              type="button"
              role="tab"
              aria-controls="laundry-transactions"
              aria-selected="false"
          >
            Transactions
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="laundry-dashboard"
            role="tabpanel"
            aria-labelledby="laundry-dashboard-tab"
        >
          <laundryDashboard/>
        </div>
        <div
            class="tab-pane fade"
            id="laundry-advertisement"
            role="tabpanel"
            aria-labelledby="laundry-advertisement-tab"
        >
          <laundryAdvertisements ref="advertisement"/>
        </div>
        <div
            class="tab-pane fade"
            id="appliance-profile-template"
            role="tabpanel"
            aria-labelledby="appliance-profile-template-tab"
        >
          <applianceProfileTemplate ref="applianceProfileTemplate"/>
        </div>
        <div
            class="tab-pane fade"
            id="laundry-transactions"
            role="tabpanel"
            aria-labelledby="laundry-transactions-tab"
        >
          <laundryTransactions/>
        </div>
      </div>

      <laundryDashboardModal/>
      <laundryAddMoneyModal/>
      <makeReservationModal/>
    </div>
    <div v-else-if="this.buildingShutoff">
      <Building_shutoff></Building_shutoff>
    </div>
    <div v-else>
      <Not_available></Not_available>
    </div>
  </div>
</template>

<script>
import laundryDashboard from "../components/laundry/laundry-dashboard.vue";
import laundryAdvertisements from "../components/laundry/laundry-advertisements.vue";
import laundryTransactions from "../components/laundry/laundry-transactions.vue";
import laundryDashboardModal from "../components/laundry/laundry-dashboard-modal.vue";
import laundryAddMoneyModal from "../components/laundry/laundry-add-money-modal.vue";
import makeReservationModal from "../components/laundry/make-reservation-modal.vue";
import applianceProfileTemplate from "../components/laundry/applianceProfileTemplate.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    laundryDashboard,
    laundryAdvertisements,
    laundryTransactions,
    laundryDashboardModal,
    laundryAddMoneyModal,
    makeReservationModal,
    Not_available,
    Building_shutoff,
    applianceProfileTemplate
  },
  data() {
    return {
      moduleAvailable: true,
      buildingShutoff: false,
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadData();
    }
  },
  created() {
    this.loadData();
  },
  methods: {
    loadData() {
      let selectedBuilding = this.$gate.user.selectedBuilding;
      this.moduleAvailable = selectedBuilding.has_laundry;
      this.buildingShutoff = selectedBuilding.building_shutoff;
      let buildingId = this.building_id;
    },
    loadApplianceProfileData() {
      this.$refs.applianceProfileTemplate.loadData();
    },
    getAdvertisementData() {
      this.$refs.advertisement.loadData();
    }
  }
};
</script>
