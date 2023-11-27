<template>
  <div>
    <div class="rounded-lg" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white mb-28" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="doors-tab"
              data-bs-toggle="pill"
              data-bs-target="#doors"
              type="button"
              role="tab"
              aria-controls="doors"
              aria-selected="true"
          >
            Doors
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              id="door-history-tab"
              data-bs-toggle="pill"
              data-bs-target="#door-history"
              type="button"
              role="tab"
              aria-controls="door-history"
              aria-selected="false"
          >
            History
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="doors"
            role="tabpanel"
            aria-labelledby="doors-tab"
        >
          <door/>
        </div>
        <div
            class="tab-pane fade"
            id="door-history"
            role="tabpanel"
            aria-labelledby="door-history-tab"
        >
          <doorHistory/>
        </div>
      </div>

      <!-- <parkingModal /> -->
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
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";
import door from "../components/doors/door.vue";
import doorHistory from "../components/doors/door-history.vue";

export default {
  components: {
    door,
    doorHistory,
    Not_available,
    Building_shutoff
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
      this.moduleAvailable = selectedBuilding.has_doors;
      this.buildingShutoff = selectedBuilding.building_shutoff;
    },
  }
};
</script>

<style scoped>
.nav-pills .active {
  padding-bottom: 5px;
  border-bottom: 5px solid #47c5fe !important;
  background-color: transparent;
  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  line-height: 20px;
  color: #262626;
}

.nav-pills {
  padding-bottom: 5px;
  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  line-height: 20px;
  color: rgba(38, 38, 38, 0.4) !important;
  margin-right: 49px !important;
}

.tab-content {
  padding: 0px;
}
</style>
