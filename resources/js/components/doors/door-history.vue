<template>
  <div class="doors-section">
    <EntryControllerAnalytics ref="EntryControllerAnalytics"></EntryControllerAnalytics>
  </div>
</template>

<script>
import EntryControllerAnalytics from "../analytics/admin/entry-controller-analytics.vue";

export default {
  components: {EntryControllerAnalytics},
  created() {
    this.loadBuildingDetails();
  },
  props: ["loadedAsAComponentInDashboard"],
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadBuildingDetails();
    }
  },
  data() {
    return {
      buildingIndex: null,
      currentBuilding: null,
    };
  },
  methods: {
    loadBuildingDetails() {
      let buildingId = this.building_id;
      if (buildingId)
        this.$http
            .get("/loadBuildingDetails" + "/" + buildingId)
            .then((response) => {
              this.currentBuilding = response.data.building;
              this.buildingIndex = buildingId;
              this.$refs.EntryControllerAnalytics.loadData();
            })
            .catch((error) => {
              console.error(error);
            });
    },
  },
};
</script>
