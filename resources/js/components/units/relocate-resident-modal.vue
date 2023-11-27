<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="relocate-resident-modal"
        tabindex="-1"
        aria-labelledby="unitOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header border-0">
            Relocate One/All Residents
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>

          <div class="modal-body text-start">
            <div class="mb-29">
              <div class="row mb-29">
                <div class="col-lg-6 col-md-6">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-uppercase">Select Building</label>
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="buildingId"
                        tag-placeholder="Select Building"
                        placeholder="Select Building"
                        label="name"
                        track-by="id"
                        :options="buildings"
                        :multiple="false"
                        @input="loadUnitsForBuilding()"
                    ></multiselect>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-uppercase">Select Unit</label>
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="unitId"
                        tag-placeholder="Select Unit"
                        placeholder="Select Unit"
                        label="unit_no"
                        track-by="id"
                        :options="units"
                        :multiple="false"
                        @input="loadUnitDetails()"
                    ></multiselect>
                  </div>
                </div>
              </div>
              <div class="row mb-29">
                <div v-if="this.currentUnit" class="table-responsive mb-28">
                  <table>
                    <thead>
                    <tr>
                      <th></th>
                      <th>Sr.</th>
                      <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in this.currentUnit.users">
                      <td>
                        <input
                            v-model="selectedResidents"
                            :id="user.id+'_resident'"
                            :disabled="nonGuarantorExist && (user.id === currentUnit.guarantor_user_id
                            || user.id === currentUnit.co_guarantor_user_id)"
                            type="checkbox" name="selectedUsers[]" :value="user.id">
                      </td>
                      <td>
                        {{ index + 1 }}
                      </td>
                      <td>
                        {{ user.firstname }} {{ user.lastname }}
                        <span v-if="user.id === currentUnit.guarantor_user_id"><strong>(Guarantor)</strong></span>
                        <span v-if="user.id === currentUnit.co_guarantor_user_id"><strong>(Co-Guarantor)</strong></span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <br>
                <p style="font-size: 13px">
                  Note: Guarantor/Co-Guarantor cannot be moved if there are other residents still inside unit.
                  To remove Guarantor/Co-Guarantor, please select different resident as Guarantor/Co-Guarantor first.
                </p>
              </div>
              <hr>
              <div v-if="selectedUnit" class="row">
                <p><strong>Maximum remaining residents ({{ this.selectedUnit.unit_no }}):
                  <span v-if="this.selectedUnit.unit_type">
                    {{ this.selectedUnit.unit_type.num_users - this.selectedUnitUsers.length }}
                  </span>
                </strong></p>
                <div class="table-responsive mb-28">
                  <table>
                    <thead>
                    <tr>
                      <th>Sr.</th>
                      <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in this.selectedUnitUsers">
                      <td>
                        {{ index + 1 }}
                      </td>
                      <td>
                        {{ user.firstname }} {{ user.lastname }}
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button @click="submitRelocationRequest()" class="btn bkg-primary">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      currentUnitId: null,
      selectedUnit: null,
      currentUnit: null,
      nonGuarantorExist: null,
      selectedUnitUsers: [],
      buildings: [],
      selectedResidents: [],
      buildingId: null,
      units: [],
      unitId: null,
      "users": {},
    };
  },
  methods: {
    relocateResident(currentUnit) {
      this.currentUnitId = currentUnit.id;
      if (!this.currentUnitId) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No unit selected",
        });
        return;
      }
      this.loadCurrentUnitDetails();
      this.loadBuildingsByUnit();
      $('#relocate-resident-modal').modal('show');

    },
    submitRelocationRequest() {
      if (!this.buildingId) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No building selected.",
        });
        return;
      }
      if (!this.unitId) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No unit selected.",
        });
        return;
      }
      if (this.selectedResidents.length === 0) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No residents selected.",
        });
        return;
      }

      let params = {
        currentUnitId: this.currentUnitId,
        unitId: this.unitId.id,
        buildingId: this.buildingId.id,
        users: this.selectedResidents
      };
      this.$http
          .post("/relocateResident", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              $('#relocate-resident-modal').modal('hide');
              Toast.fire({
                icon: "success",
                position: "top",
                title: "The user(s) has/have been successfully moved.",
              });
              this.$parent.loadData();
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message ?? "Something went wrong",
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadBuildingsByUnit() {
      let params = {unitId: this.currentUnitId};
      this.$http
          .post("/loadBuildingsByUnit", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.buildings = response.buildings;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message ?? "Something went wrong",
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadUnitsForBuilding() {
      let params = {isPhysical: 1};
      this.$http
          .get("/building/" + this.buildingId.id + "/units", {params})
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.units = response.units;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message ?? "Something went wrong",
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadUnitDetails() {
      this.$http
          .get("/unit/" + this.unitId.id)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.selectedUnit = response.unit;
              this.selectedUnitUsers = response.unit.users;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message ?? "Something went wrong",
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadCurrentUnitDetails() {
      this.nonGuarantorExist = false;
      this.unitId = false;
      this.buildingId = false;
      this.$http
          .get("/unit/" + this.currentUnitId)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.currentUnit = response.unit;
              this.nonGuarantorExist = response.nonGuarantorUser;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message ?? "Something went wrong",
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    }
  }
};
</script>
