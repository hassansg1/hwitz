<template>
  <div class="parking-section">
    <div class="row mb-52">
      <div class="col-lg-3 col-md-4">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-uppercase">Select Building</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
            v-model="building"
            placeholder=""
            label="name"
            track-by="id"
            :options="parkingBuildings"
            :multiple="false"
          ></multiselect>
        </div>
      </div>
      <div class="col-lg-9 col-md-8 text-right">
        <a class="btn btn-primary" :href="'/building/manage/'+building.id" target="_blank" style="width :unset !important" v-if="building">Manage rent prices</a>
      </div>
    </div>

    <div class="row gy-3">
      <div class="col-lg-6 col-xl-4" v-for="(unit,index) in units">
        <div class="card">

          <div class="d-flex justify-content-between">
            <div class="me-12">
              <h3 class="h3 mb-4">
                {{unit.unit_no}}
                <i class="fa fa-ellipsis-h color-secondary select-cursor" id="dropdownVacate" data-bs-toggle="dropdown" aria-expanded="false"></i>

                <div class="dropdown">
                  <ul class="dropdown-menu dropdown-menu-end border-standered" aria-labelledby="dropdownVacate">
                    <!-- <li class="px-2 py-1 border-bottom text-sm-bold color-secondary select-cursor" @click="vacateUnit(unit)">
                      Vacate storage unit
                    </li> -->
                    <li class="px-2 py-1 text-sm-bold color-secondary select-cursor" @click="openHistoryModal(unit)">
                      View history
                    </li>
                  </ul>
                </div>
              </h3>
              <div class="color-primary" v-if="unit.user1 && unit.user1.length > 0">
                <span class="me-9" style="display: block;"  v-for="user in unit.user1">
                  <span v-if="!user.parent_unit_id || user.parent_unit_id == unit.id">{{user.name}} - (Non Resident)</span>
                  <span v-else>{{user.full_name}}</span>
                </span>
              </div>
              <div class="color-primary" v-else>
                <span class="me-9">Unoccupied</span>
              </div>
            </div>
            <div>
              <div class="text-normal text-right">

                <div class="d-flex flex-row justify-content-end mb-8"  v-if="unit.user1 && unit.user1.length > 0">
                  <div class="avatar-sm" v-for="user in unit.user1">
                    <img :src="user.profile_picture ? user.profile_picture : '/images/default-user.jpg'" class="avatar-img" :title="user.full_name" />
                  </div>
                </div>
                <div class="d-flex flex-row justify-content-end mb-8" v-else>
                  <div class="avatar-sm" >
                    <img src="/images/default-user.jpg" class="avatar-img" title="Unoccupied" />
                  </div>
                </div>
                <div>
                  <span v-if="unit.user1 && unit.user1.length >= unit.maxOccupancy" class="color-danger">Not Available</span>
                  <span v-else class="color-primary">Available</span>
                </div>
                <div>
                  <span class="color-secondary">{{unit.description ? unit.description : ' '}}</span>
                  <span class="color-secondary" v-if="unit.rent_amt">(${{ unit.rent_amt }})</span>
                </div>

              </div>
            </div>
          </div>
          <hr />

          <div class="d-flex justify-content-end">
            <button class="btn btn-primary" v-if="unit.user1 && unit.user1.length < unit.maxOccupancy" @click="openAddResidentPopup(unit)" style="padding: 2px !important; margin-right: 1px !important;">Add non resident</button>
            <button class="btn btn-primary" v-if="unit.user1 && unit.user1.length > 1" @click="vacateResident(unit)" style="padding: 2px !important;margin-right: 2px !important;">Vacate resident</button>
            <button class="btn btn-primary" v-if="unit.users && unit.users.length > 0" @click="vacateUnit(unit)" style="margin-right: 2px !important;">Vacate unit</button>
            <button class="btn bkg-primary text-white" v-if="unit.user1.length == 0" @click="openModal(unit)" style="">Assign</button>
            <button class="btn bkg-primary text-white" v-else-if="unit.user1 && unit.user1.length < unit.maxOccupancy" @click="openModal(unit)" style="width: 100px; padding: 2px !important;">Assign</button>
          </div>


        </div>
      </div>
    </div>
    <assignParkingModal ref="assignParkingModal" :id="'nonresident'" />
    <vacateResident ref="vacateResident" :id="'nonresident'" />
    <addResidentModal ref="addNewResidentModal" :loadedFromParking="true" :id="'nonresidentParking'" />
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import assignParkingModal from "./assignParkingModal";
import vacateResident from "./vacateResident.vue";
import addResidentModal from "../../pages/residents/addResidentModal";
import {config} from "../../config";
export default {
  components: {
    Multiselect,
    assignParkingModal,
    vacateResident,
    addResidentModal
  },
  data() {
    return {
      buildings : [],
      parkingBuildings : [],
      building : '',
      units : '',
      users : [],
    };
  },
  methods : {
    reloadData(){
      this.loadData();
    },
    openAddResidentPopup(unit){
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings(this.building, unit);
      this.$refs.addNewResidentModal.form.fromParking = true;
      this.$refs.addNewResidentModal.title = 'Add non resident leasee';
      $('#addNewResidentModalnonresidentParking').modal('show');
    },
    vacateUnit(unit){
      // this.$parent.vacateUnit(data);
      let usersCount = unit.users.length;
      if (usersCount > 0) {
          Swal.fire({
          title: config.confirmBoxTitle,
          text: 'Are you sure you want to vacate the unit?',
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: config.confirmButtonColor,
          cancelButtonColor: config.cancelButtonColor,
          confirmButtonText: config.confirmButtonText,
          }).then((result) => {
            if (result.value) {
                let params = {
                unitId: unit.id,
                };
                this.showLoader();
                this.$http
                    .post("/vacateUnit", params)
                    .then((response) => {
                        response = response.data;
                        if (response.status) {
                            Toast.fire({
                              icon: "success",
                              title: "The unit has been successfully vacated.",
                            });
                            this.loadData();
                        } else {
                            Toast.fire({
                              icon: "error",
                              title: response.message ?? "Something went wrong",
                            });
                            this.removeLoader();
                        }
                    })
                    .catch((error) => {
                        this.removeLoader();
                        console.error(error);
                    });
            }
          });
      }else{
        Toast.fire({
          icon: "error",
          position: "top",
          title: "Unit already vacant",
        });
      }
    },
    openHistoryModal(unit){
      this.$parent.showLeaseHistory(unit);
    },
    openModal(unit){
      this.$refs.assignParkingModal.building_id = this.$gate.building_id;
      this.$refs.assignParkingModal.form.unit_id = unit.id;

      if(unit.user1 && unit.user1.length > 0){
        this.$refs.assignParkingModal.user = unit.user1[0];
      }else{
        this.$refs.assignParkingModal.user = '';
      }
      this.$refs.assignParkingModal.loadUsers();
      $('#assignParkingModalnonresident').modal('show');
    },
    loadData(){
      let building_id = this.building ? this.building.id : 0;
      if (building_id == 0) return;

      if(!this.loadedAsAComponentInDashboard) this.showLoader();

      this.$http
        .get("/parking?building_id="+building_id)
        .then((response) => {
          this.units = response.data;
          if(this.loadedAsAComponentInDashboard){
            this.units = this.units.slice(0,4);
          }
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
    },
    vacateResident(unit){
      this.$refs.vacateResident.users = unit.user1;
      this.$refs.vacateResident.unit = unit;
      $('#vacateResidentnonresident').modal('show');
    },
  }
};
</script>
