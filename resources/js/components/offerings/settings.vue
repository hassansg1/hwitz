<template>
  <div class="offering-residents">
    <div class="">
      <div class="row gy-3 mt-10 mb-28">
        <em>* For changes call Tech Support.</em>
        <h3 class="h3">Settings And Data</h3>
        <financeSummary ref="financeSummary"></financeSummary>
      </div>
    </div>
  </div>
</template>

<script>
import financeSummary from "../financial/ownerOrderSummary/summary.vue";

export default {
  components: {financeSummary},
  data() {
    return {};
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      if (newBuildingId !== oldBuildingId && oldBuildingId !== 0)
        this.loadData();
    }
  },
  methods: {
    loadData() {
      let buildingId = this.building_id;
      if (buildingId) {
        this.$refs.financeSummary.loadData(buildingId);
      }
    },
  },
};
</script>
