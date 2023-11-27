<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="lease-history-modal"
        tabindex="-1"
        aria-labelledby="unitOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0" v-if="this.unit">
            Lease History of {{ this.unit.unit_no }} - {{ this.unit.building.name }}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>

          <div class="modal-body text-start">
            <leaseHistoryAnalytics ref="leaseHistoryAnalytics"></leaseHistoryAnalytics>
            <div class="d-flex justify-content-end">
              <button @click="printDiv('lease_history_div')" class="btn bkg-primary mr-10">Print Report</button>
              <button data-bs-dismiss="modal"
                      aria-label="Close"
                      class="btn bkg-danger">Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import leaseHistoryAnalytics from "../analytics/user/lease-history-analytics.vue";

export default {
  components: {leaseHistoryAnalytics},
  data() {
    return {
      unit: null,
    };
  },
  expose: ["loadData"],
  methods: {
    loadData(pageNumber = null, unit) {
      this.unit = unit;
      this.$refs.leaseHistoryAnalytics.unitId = unit.id;
      this.$refs.leaseHistoryAnalytics.loadData(pageNumber);
    }
  },
};
</script>
