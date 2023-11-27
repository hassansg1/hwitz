<template>
  <div class="storage-section">
    <!-- <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-72">
      <a class="a-link me-20" href="">Export to .xls</a>
      <a class="a-link me-20" href="">Select</a>
      <a class="a-link me-20" href="">Select all</a>
      <a class="a-link" href="">Delete all</a>
    </div> -->

    <div class="row mb-52">
      <div class="col-xl-3 col-lg-4 col-md-6 mb-12 mb-md-0">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-uppercase">Select Building</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
            v-model="building"
            placeholder=""
            label="name"
            track-by="id"
            :options="buildings"
            :multiple="false"
            @input="loadData"
          ></multiselect>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 col-md-6 text-md-end align-self-center">
          <a class="btn btn-primary" :href="'/building/manage/'+building.id" target="_blank" style="width :unset !important" v-if="building">Manage rent prices</a>
      </div>
    </div>
    <div class="row gy-4">
      <div class="col-xl-4 col-lg-6" v-for="(unit,index) in units">
        <div class="card">
          <div class="d-flex justify-content-between">
            <div class="me-12">
              <!-- <div class="d-flex"> -->
                <h3 class="h3 mb-4 me-4">
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
                
              <!-- </div> -->
              <div class="color-primary">
                <span class="me-9" style="display: block;" v-if="unit.user1 && unit.user1.length > 0" v-for="user in unit.user1">
                  <span v-if="!user.parent_unit_id || user.parent_unit_id == unit.id">{{user.name}} - (Non Resident)</span>
                  <span v-else>{{user.full_name}}</span>
                  <!-- {{user.full_name}} -->
                </span>
                <span class="me-9" v-else >Unoccupied</span>
                <div class="d-flex">
                  <div class="form-check form-switch p-0 me-10 align-self-center">
                    <label class="switch mb-0">
                      <input type="checkbox" role="switch" @change="grantAccess"/>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="align-self-center text-normal color-secondary">
                    Grant
                  </div>
                </div>
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

          <!-- <div class="d-flex justify-content-between"> -->

            <!-- <div class="d-flex">
              <div class="form-check form-switch p-0 me-10 align-self-center">
                <label class="switch mb-0">
                  <input type="checkbox" role="switch" @change="grantAccess"/>
                  <span class="slider round"></span>
                </label>
              </div>
              <div class="align-self-center text-normal color-secondary">
                Grant
              </div>
            </div> -->
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary me-4" v-if="unit.users && unit.users.length > 1" @click="vacateResident(unit)" >Vacate resident</button>
              <button class="btn btn-primary me-4" v-if="unit.users && unit.users.length > 0" @click="vacateUnit(unit)">Vacate unit</button>
              <button class="btn btn-primary" v-if="unit.user1 && unit.user1.length  < unit.maxOccupancy" @click="openModal(unit)">Assign</button>
            </div>
          <!-- </div> -->
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center" v-if="loadedAsAComponentInDashboard">
      <router-link class="a-link mt-64" :to="{ name: 'Storage' }">View All</router-link>
    </div>

    <unitHistory ref="unitHistory" :id="'occupied'" />
    <vacateResident ref="vacateResident" :id="'occupied'" />
    <storageModal ref="storageModal" :id="'occupied'"/>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import unitHistory from "../utils/unitHistory.vue";
import vacateResident from "../parking/vacateResident.vue";
import storageModal from "./storage-modal.vue";
import {config} from "../../config";
export default {
  components: {
    Multiselect,
    unitHistory,
    vacateResident,
    storageModal
  },
  props : ["loadedAsAComponentInDashboard"],
  data() {
    return {
      data : [],
      buildings : [],
      building : '',
      units : []
      
    };
  },
  created(){
    this.getBuildings();
  },
  methods : {
    openModal(unit){
      this.$refs.storageModal.building_id = this.$gate.building_id;
      this.$refs.storageModal.form.user = unit.user1;
      this.$refs.storageModal.form.unit_id = unit.id;
      this.$refs.storageModal.loadUsers();
      $('#storageModaloccupied').modal('show');
    },
    grantAccess(){
      this.showLoader();
      setTimeout(() => {
        Toast.fire({
            icon: 'success',
            title: 'Access granted.',
        });
        this.removeLoader();
      }, 2000);
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
    loadData(){

      let building_id = this.building ? this.building.id : 0;
      if (building_id == 0) {
        this.removeLoader();
        return;
      }

      if(!this.loadedAsAComponentInDashboard) this.showLoader();

      this.$http
        .get("/storage?building_id="+building_id)
        .then((response) => {
          this.units = response.data;
          this.units = this.units.filter(unit => unit.occupied === 1);
          this.units = this.units.filter(unit => unit.is_physical === 1);
          if(this.loadedAsAComponentInDashboard){
            this.units = this.units.slice(0,3);
          }
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
        
    },
    getBuildings(){
      if(!this.loadedAsAComponentInDashboard) this.showLoader();
      this.$http
        .get("/getLinkedBuildings/"+this.$gate.building_id)
        .then((response) => {
          this.buildings = response.data.storage;
          this.building = this.buildings[0];
          this.loadData();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
    },
    vacateResident(unit){

      this.$refs.vacateResident.users = unit.user1;
      this.$refs.vacateResident.unit = unit;
      $('#vacateResidentoccupied').modal('show');
      // Swal.fire({
      //   title: "Are you sure?",
      //   text: "Are you sure you want to vacate the resident?",
      //   icon: "warning",
      //   showCancelButton: true,
      //   confirmButtonColor: "#3085d6",
      //   cancelButtonColor: "#d33",
      //   confirmButtonText: "Yes, vacate!",
      // }).then((result) => {
      //   if (result.value) {
      //     this.showLoader();
      //     this.$http
      //       .get("/vacate/storage/"+unit.id)
      //       .then((response) => {
      //         this.loadData();
      //         Toast.fire({
      //             icon: 'success',
      //             title: 'Resident vacated successfully.',
      //         });
      //         this.loadData();
      //         this.removeLoader();
      //       })
      //       .catch((error) => {
      //         Toast.fire({
      //             icon: 'warning',
      //             title: 'Something went wrong. Please try again later.',
      //         });
      //         this.removeLoader();
      //         console.error(error);
      //       });
      //   }
      // });
      
    },
    entryActivity(fob){
      this.$refs.entryModal.building_id = this.building_id;
      this.$refs.entryModal.id = fob.token_idd;
      this.$refs.entryModal.title = 'Entry Controller history for FOB ' + fob.card_id;
      this.$refs.entryModal.loadData();
      $('#fobEntryActivityModalinUse').modal('show');

    }
  }
};
</script>


<style scoped>
@media screen and (min-width: 300px) and (max-width: 400px) {
  .card {
    padding: 26px 16px;
  }
  .btn {
    padding: 6px 14px !important;
    font-size: 12px;
  }
}
</style>
