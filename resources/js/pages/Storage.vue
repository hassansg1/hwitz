<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="occupied-tab"
              data-bs-toggle="pill"
              data-bs-target="#occupied"
              type="button"
              role="tab"
              aria-controls="occupied"
              aria-selected="true"
              @click="loadOccupied"
          >
            Occupied Storage Units
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              id="unoccupied-tab"
              data-bs-toggle="pill"
              data-bs-target="#unoccupied"
              type="button"
              role="tab"
              aria-controls="unoccupied"
              aria-selected="false"
              @click="loadUnoccupied"
          >
            Unoccupied Storage Units
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="occupied"
            role="tabpanel"
            aria-labelledby="occupied-tab"
        >
          <occupied ref="occupied"/>
        </div>
        <div
            class="tab-pane fade"
            id="unoccupied"
            role="tabpanel"
            aria-labelledby="unoccupied-tab"
        >
          <unoccupied ref="unoccupied"/>
        </div>
        <leaseHistoryModal ref="leaseHistoryModal"/>
      </div>

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
import occupied from "../components/storage/occupied.vue";
import unoccupied from "../components/storage/unoccupied.vue";
import leaseHistoryModal from "../components/units/lease-history-modal.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    occupied,
    unoccupied,
    Not_available,
    Building_shutoff,
    leaseHistoryModal
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
  methods: {
    loadData() {
      let selectedBuilding = this.$gate.user.selectedBuilding;
      this.moduleAvailable = selectedBuilding.has_storage;
      this.buildingShutoff = selectedBuilding.building_shutoff;
    },
    loadOccupied() {
      this.loadData();
      this.$refs.occupied.getBuildings();
    },
    loadUnoccupied() {
      this.loadData();
      this.$refs.unoccupied.getBuildings();
    },
    showLeaseHistory(unit) {
      $("#lease-history-modal").modal("show");
      this.$refs.leaseHistoryModal.loadData(null, unit);
    },
  }
};
</script>
