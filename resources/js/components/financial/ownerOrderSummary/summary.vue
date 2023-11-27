<template>
  <div class="f-summary">
    <div>
      <div class="row mb-28" v-if="!selectedBuildingId">
        <div class="col-lg-3 col-md-6">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Building</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="building"
                label="name"
                track-by="id"
                :options="buildings"
                :multiple="false"
                placeholder=""
                @input="loadData()"
            ></multiselect>
          </div>
        </div>
      </div>

      <div class="row gy-3 mb-34" v-if="summaryData">
        <div id="container_building_order_summary_top">
          <dl class="dl-horizontal">
            <dt>Package Name</dt>
            <dd v-if="summaryData.buildingPackage && summaryData">
              {{ summaryData.buildingPackage.package_name }}
            </dd>

            <dt>ROE Service Terms</dt>
            <dd v-if="summaryData.buildingPackage">
              {{ summaryData.buildingPackage.package_year }}
              Year
            </dd>

          </dl>
        </div>
        <div id="container_building_order_summary">
          <dl v-for="buildingPackageGroup in summaryData.buildingPackageGroups">
            <dt>{{ buildingPackageGroup.group_name }} (<span v-if="buildingPackageGroup.accessFor === 0">Off</span>
              <span v-else-if="buildingPackageGroup.accessFor === 1">Owner Bulk</span>
              <span v-else-if="buildingPackageGroup.accessFor === 2">3rd Party Billing</span>
              <span v-else-if="buildingPackageGroup.accessFor === 3">Resident Choice</span>)
            </dt>
            <dd>
              <table class="table table-striped">
                <thead>
                <tr>
                  <th width="60%">Name</th>
                  <th width="10%">Status</th>
                  <th width="10%">Cart Type</th>
                  <th width="10%">Owner Price</th>
                  <th width="10%">Resident Price</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="addon in buildingPackageGroup.addons">
                  <td>{{ addon.addons_name }}</td>
                  <td>{{ addon.addonStatus }}</td>
                  <td>{{ addon.cartLabel }}</td>
                  <td>{{ addon.addonsPrice }}</td>
                  <td>{{ addon.addonsPriceResident }}</td>
                </tr>
                </tbody>
              </table>
            </dd>
            <hr>
          </dl>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
      buildings: [],
      building: '',
      selectedBuildingId: '',
      summaryData: null,
    };
  },
  created() {
    this.getBuildings();
  },
  methods: {
    loadData(selectedBuildingId = null) {
      let params = {};
      if (selectedBuildingId) {
        this.selectedBuildingId = selectedBuildingId;
        params.buildingId = selectedBuildingId;
      } else
        params.buildingId = this.building ? this.building.id : 0;
      if (params.buildingId === 0) {
        Toast.fire({icon: 'warning', title: 'No building selected. Please select building first and try again.'});
        return;
      }
      this.$http
          .post("/loadOwnerOrderSummary", params)
          .then((response) => {
            this.summaryData = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
