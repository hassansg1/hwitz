<template>
  <div>
    <div class="rounded-lg pages-style" v-if="building_id && building_id > 0">
      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-44" role="presentation">
          <div
              class="active"
              id="dashboard-tab"
              data-bs-toggle="pill"
              data-bs-target="#dashboard"
              type="button"
              role="tab"
              aria-controls="dashboard"
              aria-selected="true"
          >
            Dashboard
          </div>
        </li>
        <li v-if="$gate.permissions.includes('building_settings')" class="nav-item me-56 mb-12" role="presentation">
          <div
              id="dash-settings-tab"
              data-bs-toggle="pill"
              data-bs-target="#dash-settings"
              type="button"
              role="tab"
              aria-controls="dash-settings"
              aria-selected="false"
              @click="loadSettingsTab"
          >
            Building Settings
          </div>
        </li>
        <li v-if="$gate.permissions.includes('building_settings')" class="nav-item mb-12" role="presentation">
          <div
              id="dash-settings-tab"
              data-bs-toggle="pill"
              data-bs-target="#dash-settings"
              type="button"
              role="tab"
              aria-controls="dash-settings"
              aria-selected="false"
              @click="loadMoreSettingsTab"
          >
            More Settings
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="dashboard"
            role="tabpanel"
            aria-labelledby="dashboard-tab"
        >
          <dashboard/>
        </div>
        <div
            class="tab-pane fade"
            id="dash-settings"
            role="tabpanel"
            aria-labelledby="dash-settings-tab"
        >
          <settings v-if="showSettingsTab"/>
        </div>
      </div>

      <dashboardModal/>
    </div>
    <div v-else>
      <Building ref="Building" @setBuildingInSession="setBuildingInSession" />
    </div>
  </div>
</template>

<script>
import dashboard from "../components/dashboard/dashboard.vue";
import dashboardModal from "../components/dashboard/dashboard-modal.vue";
import settings from "../components/dashboard/settings.vue";
import MainMenuDashboard from "../components/dashboard/mainMenuDashboard.vue";
import Building from "./Building.vue";

export default {
  components: {
    dashboard,
    dashboardModal,
    settings,
    Building,
    MainMenuDashboard
  },
  data() {
    return {
      showSettingsTab: false,
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      // this.loadData();
    }
  },
  methods: {
    loadSettingsTab() {
      this.showSettingsTab = !this.showSettingsTab;
      console.log(this.showSettingsTab, 'setting');
    },
    loadMoreSettingsTab() {
      window.open('/more_building_settings', "_blank");
      return;
    },
    setBuildingInSession(id){
      this.$emit('setBuildingInSession',id);
    }
  }
};
</script>
