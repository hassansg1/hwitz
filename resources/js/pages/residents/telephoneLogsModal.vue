<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="telephoneLogsModal"
        tabindex="-1"
        aria-labelledby="unitOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0" v-if="this.unit">
            Telephone Logs of {{ this.unit.unit_no }} - {{ this.unit.building.name }}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>

          <div class="modal-body text-start">
            <PhoneAnalytics ref="PhoneAnalytics"></PhoneAnalytics>
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
import PhoneAnalytics from "../../components/analytics/user/phone-analytics.vue";

export default {
  components: {PhoneAnalytics},
  data() {
    return {
      unit: null,
      unitId: null,
      buildingIndex: null,
      currentBuilding: null,
    };
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadBuildingDetails();
    }
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  expose: ["loadData"],
  methods: {
    loadBuildingDetails() {
      let buildingId = this.building_id;
      this.$http
          .get("/loadBuildingDetails" + "/" + buildingId)
          .then((response) => {
            this.currentBuilding = response.data.building;
            this.buildingIndex = this.building_id;
            this.unitId = this.unit.id;
            this.$refs.PhoneAnalytics.loadData();
            $('#telephoneLogsModal').modal('show');
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
