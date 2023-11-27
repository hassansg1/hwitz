<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation" @click="loadResidentData">
          <div
              class="active"
              id="resident-tab"
              data-bs-toggle="pill"
              data-bs-target="#resident"
              type="button"
              role="tab"
              aria-controls="resident"
              aria-selected="true"
          >
            Resident
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation" @click="loadNonResidentData">
          <div
              id="non-resident-tab"
              data-bs-toggle="pill"
              data-bs-target="#non-resident"
              type="button"
              role="tab"
              aria-controls="non-resident"
              aria-selected="false"
          >
            Non Resident
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="resident"
            role="tabpanel"
            aria-labelledby="resident-tab"
        >
          <resident ref="residentparking"/>
        </div>
        <div
            class="tab-pane fade"
            id="non-resident"
            role="tabpanel"
            aria-labelledby="non-resident-tab"
        >
          <nonResident ref="nonresidentparking"/>
        </div>
      </div>

      <parkingModal/>

      <unitHistory ref="unitHistory" :id="'residentParking'"/>
      <leaseHistoryModal ref="leaseHistoryModal"/>
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
import resident from "../components/parking/resident.vue";
import nonResident from "../components/parking/non-resident.vue";
import parkingModal from "../components/parking/parking-modal.vue";
import unitHistory from "../components/utils/unitHistory.vue";
import leaseHistoryModal from "../components/units/lease-history-modal.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    resident,
    nonResident,
    parkingModal,
    unitHistory,
    Not_available,
    Building_shutoff,
    leaseHistoryModal
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.getBuildings();
    }
  },
  data() {
    return {
      moduleAvailable: true,
      buildingShutoff: false,
    };
  },
  created() {
    this.getBuildings();
  },
  methods: {
    loadResidentData() {
      this.$refs.residentparking.loadData();
    },
    loadNonResidentData() {
      this.$refs.nonresidentparking.loadData();
    },
    getBuildings() {
      let selectedBuilding = this.$gate.user.selectedBuilding;
      this.moduleAvailable = selectedBuilding.has_parking;
      this.buildingShutoff = selectedBuilding.building_shutoff;
      let buildingId = this.building_id;
      if (buildingId && this.moduleAvailable && !this.buildingShutoff) {

        this.showLoader();
        this.$http.get("/getLinkedBuildings/" + this.$gate.building_id)
            .then((response) => {
              let parkingBuildings = response.data.parking;
              this.$refs.residentparking.parkingBuildings = parkingBuildings;
              this.$refs.residentparking.building = parkingBuildings[0];
              this.$refs.nonresidentparking.parkingBuildings = parkingBuildings;
              this.$refs.nonresidentparking.building = parkingBuildings[0];
              this.$refs.residentparking.loadData();
            })
            .catch((error) => {
              console.error(error);
              this.removeLoader();
            });
      }
    },
    showLeaseHistory(unit) {
      $("#lease-history-modal").modal("show");
      this.$refs.leaseHistoryModal.loadData(null, unit);
    },

  }
};
</script>
