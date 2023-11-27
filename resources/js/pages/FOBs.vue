<template>
  <div>
    <div class="rounded-lg pages-style" v-if="this.moduleAvailable && !this.buildingShutoff">
      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="inuse-tab"
              data-bs-toggle="pill"
              data-bs-target="#inuse"
              type="button"
              role="tab"
              aria-controls="inuse"
              aria-selected="true"
              @click="loadInUseFobs"
          >
            FOBs in Use
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              id="discontinued-tab"
              data-bs-toggle="pill"
              data-bs-target="#discontinued"
              type="button"
              role="tab"
              aria-controls="discontinued"
              aria-selected="false"
              @click="loadDiscontinuedFobs"
          >
            FOBs Discontinued
          </div>
        </li>
        <li class="nav-item mb-12" role="presentation">
          <div
              id="unassigned-tab"
              data-bs-toggle="pill"
              data-bs-target="#unassigned"
              type="button"
              role="tab"
              aria-controls="unassigned"
              aria-selected="false"
              @click="loadUnAssignedFobs"
          >
            FOBs Unassigned
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="inuse"
            role="tabpanel"
            aria-labelledby="inuse-tab"
        >
          <inuse ref="inuse" :activeTab="activeTab"/>
        </div>
        <div
            class="tab-pane fade"
            id="discontinued"
            role="tabpanel"
            aria-labelledby="discontinued-tab"
        >
          <discontinued ref="discontinued" :activeTab="activeTab"/>
        </div>
        <div
            class="tab-pane fade"
            id="unassigned"
            role="tabpanel"
            aria-labelledby="unassigned-tab"
        >
          <unassigned ref="unassigned" :activeTab="activeTab"/>
        </div>
      </div>

      <inusemodal/>
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
import inuse from "../components/fobs/inuse.vue";
import unassigned from "../components/fobs/unassigned.vue";
import discontinued from "../components/fobs/discontinued.vue";
import inusemodal from "../components/fobs/inuse-modal.vue";
import Not_available from "../components/error/not_available.vue";
import Building_shutoff from "../components/error/building_shutoff.vue";

export default {
  components: {
    inuse,
    unassigned,
    discontinued,
    inusemodal,
    Not_available,
    Building_shutoff
  },
  data() {
    return {
      activeTab: 'inUse',
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
      this.moduleAvailable = selectedBuilding.has_fobs;
      this.buildingShutoff = selectedBuilding.building_shutoff;
    },
    loadInUseFobs() {
      this.$refs.discontinued.activeTab == 'inUse';
      this.$refs.inuse.loadData();
    },
    loadDiscontinuedFobs() {
      this.$refs.discontinued.activeTab = 'discontinued';
      this.$refs.discontinued.loadData();
    },
    loadUnAssignedFobs() {
      this.$refs.unassigned.activeTab = 'unassigned';
      this.$refs.unassigned.loadData();
    }
  }
};
</script>
