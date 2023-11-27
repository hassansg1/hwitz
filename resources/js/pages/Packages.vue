<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white mb-44" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              @click="changeActiveTab('occupiedLocker')"
              class="active"
              id="occupied-locker-tab"
              data-bs-toggle="pill"
              data-bs-target="#occupied-locker"
              type="button"
              role="tab"
              aria-controls="occupied-locker"
              aria-selected="true"
          >
            Occupied Locker
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              @click="changeActiveTab('unoccupiedLocker')"
              id="unoccupied-locker-tab"
              data-bs-toggle="pill"
              data-bs-target="#unoccupied-locker"
              type="button"
              role="tab"
              aria-controls="unoccupied-locker"
              aria-selected="false"
          >
            Unoccupied Locker
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              @click="changeActiveTab('manageLocker')"
              id="manage-locker-tab"
              data-bs-toggle="pill"
              data-bs-target="#manage-locker"
              type="button"
              role="tab"
              aria-controls="manage-locker"
              aria-selected="false"
          >
            Manage Locker
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              @click="changeActiveTab('packagesAnalytics')"
              id="packages-analytics-tab"
              data-bs-toggle="pill"
              data-bs-target="#packages-analytics"
              type="button"
              role="tab"
              aria-controls="packages-analytics"
              aria-selected="false"
          >
            Analytics
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="occupied-locker"
            role="tabpanel"
            aria-labelledby="occupied-locker-tab"
        >
          <occupiedLocker ref="occupiedLocker"/>
        </div>
        <div
            class="tab-pane fade"
            id="unoccupied-locker"
            role="tabpanel"
            aria-labelledby="unoccupied-locker-tab"
        >
          <unoccupiedLocker ref="unoccupiedLocker"/>
        </div>
        <div
            class="tab-pane fade"
            id="manage-locker"
            role="tabpanel"
            aria-labelledby="manage-locker-tab"
        >
          <manageLocker ref="manageLocker"/>
        </div>
        <div
            class="tab-pane fade"
            id="packages-analytics"
            role="tabpanel"
            aria-labelledby="packages-analytics-tab"
        >
          <PackageAnalytics ref="packagesAnalytics"/>
        </div>
      </div>
      <extension-history-modal ref="extensionHistory"></extension-history-modal>
      <alert-resident ref="alertResident"></alert-resident>
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
import occupiedLocker from "../components/packages/occupied-locker.vue";
import unoccupiedLocker from "../components/packages/unoccupied-locker.vue";
import manageLocker from "../components/packages/manage-locker.vue";
import ExtensionHistoryModal from "../components/packages/extension-history-modal.vue";
import AlertResident from "../components/packages/alert-resident.vue";
import PackageAnalytics from "../components/analytics/user/package-analytics.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    AlertResident,
    occupiedLocker,
    unoccupiedLocker,
    PackageAnalytics,
    manageLocker,
    ExtensionHistoryModal,
    Not_available,
    Building_shutoff
  },
  created() {
    if (this.building_id) {
      this.loadBuildingDetails();
    }
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadBuildingDetails();
    }
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  data() {
    return {
      activeTab: "occupiedLocker",
      currentBuilding: null,
      buildingIndex: null,
      params: null,
      moduleAvailable: true,
      buildingShutoff: false,
    };
  },
  methods: {
    loadBuildingDetails() {
      let selectedBuilding = this.$gate.user.selectedBuilding;
      this.moduleAvailable = selectedBuilding.has_package;
      this.buildingShutoff = selectedBuilding.building_shutoff;
      let buildingId = this.building_id;
      if (buildingId && this.moduleAvailable && !this.buildingShutoff) {
        this.$http
            .get("/loadBuildingDetails" + "/" + buildingId)
            .then((response) => {
              this.currentBuilding = response.data.building;
              this.buildingIndex = this.building_id;
              this.$refs.packagesAnalytics.loadData();
            })
            .catch((error) => {
              console.error(error);
            });
      }
    },
    changeActiveTab(activeTab) {
      this.activeTab = activeTab;
      if (this.activeTab === "occupiedLocker") {
        this.$refs.occupiedLocker.loadData();
      }
      if (this.activeTab === "unoccupiedLocker") {
        this.$refs.unoccupiedLocker.loadData();
      }
      if (this.activeTab === "manageLocker") {
        this.$refs.manageLocker.loadData();
      }
      if (this.activeTab === "packagesAnalytics") {
        this.params = {};
        this.params.buildingId = this.building_id;
        this.$refs.packagesAnalytics.loadData();
      }
    },
    viewExtensionHistory(packageId) {
      this.$refs.extensionHistory.viewExtensionHistory(packageId);
    },
    alertResident(locker) {
      this.$refs.alertResident.alertResident(locker);
    }
  }
};
</script>
