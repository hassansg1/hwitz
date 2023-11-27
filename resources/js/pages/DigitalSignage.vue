<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="template-library-tab"
              data-bs-toggle="pill"
              data-bs-target="#template-library"
              type="button"
              role="tab"
              aria-controls="template-library"
              aria-selected="true"
          >
            Template Library
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              id="room-display-tab"
              data-bs-toggle="pill"
              data-bs-target="#room-display"
              type="button"
              role="tab"
              aria-controls="room-display"
              aria-selected="false"
          >
            Room Display
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="template-library"
            role="tabpanel"
            aria-labelledby="template-library-tab"
        >
          <templateLibrary/>
        </div>
        <div
            class="tab-pane fade"
            id="room-display"
            role="tabpanel"
            aria-labelledby="room-display-tab"
        >
          <displayRoom/>
        </div>
      </div>

      <selectDisplayModal/>
      <addNewDisplayModal/>
      <editDisplayModal/>
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
import displayRoom from "../components/digital-signage/display-room.vue";
import templateLibrary from "../components/digital-signage/template-library.vue";
import selectDisplayModal from "../components/digital-signage/select-display-modal.vue";
import addNewDisplayModal from "../components/digital-signage/add-new-display-modal.vue";
import editDisplayModal from "../components/digital-signage/edit-display-modal.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    displayRoom,
    templateLibrary,
    selectDisplayModal,
    addNewDisplayModal,
    editDisplayModal,
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
      this.moduleAvailable = selectedBuilding.has_digital_signage;
      this.buildingShutoff = selectedBuilding.building_shutoff;
    },
  }
};
</script>
