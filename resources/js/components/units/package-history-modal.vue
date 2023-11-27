<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="package-history-modal"
        tabindex="-1"
        aria-labelledby="unitOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0" v-if="this.unit">
            Package History of {{ this.unit.unit_no }} - {{ this.unit.building.name }}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>

          <div class="modal-body text-start">
            <PackageAnalytics ref="packageAnalytics"/>
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
import PackageAnalytics from "../../components/analytics/user/package-analytics.vue";

export default {
  components: {
    PackageAnalytics
  },
  data() {
    return {
      unit: null,
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: 0,
      buildingIndex: 0,
      currentBuilding: null,
    };
  },
  expose: ["loadData"],
  methods: {
    loadBuildingDetails() {
      let buildingId = this.buildingIndex;
      this.$http
          .get("/loadBuildingDetails" + "/" + buildingId)
          .then((response) => {
            this.currentBuilding = response.data.building;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    getDetails(unit) {
      this.buildingIndex = unit.building_id;
      this.unit = unit;
      this.loadBuildingDetails();
      this.$refs.packageAnalytics.loadData();
    },
  },
};
</script>
