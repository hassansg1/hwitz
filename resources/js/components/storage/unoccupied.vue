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
        <a class="btn btn-primary" :href="'/building/manage/'+building.id" target="_blank" style="width :unset !important">Manage rent prices</a>
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
                  <i class="fa fa-ellipsis-h color-secondary select-cursor" id="dropdownVacate" data-bs-toggle="dropdown" aria-expanded="false" style="margin:6px;"></i>
                  
                  <div class="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end border-standered" aria-labelledby="dropdownVacate">
                      <li class="px-2 py-1 text-sm-bold color-secondary select-cursor" @click="openHistoryModal(unit)">
                        View history
                      </li>
                    </ul>
                  </div>
                </h3>
               
              <!-- </div> -->
              <div class="color-danger">
                <span class="me-9">Unoccupied</span>
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
              <!-- <div class="avatar-sm">
                <img
                  src="/images/default-user.jpg"
                  class="avatar-img"
                  alt="user"
                />
              </div> -->
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
                  <span v-if="unit.users && unit.users.length > 0" class="color-danger">Occupied</span>
                  <span v-else class="color-primary">Unoccupied</span>
                </div>
                <div>
                  <span class="color-secondary">{{unit.description ? unit.description : ' '}}</span>
                  <span class="color-secondary" v-if="unit.rent_amt">(${{ unit.rent_amt }})</span>
                </div>

              </div>
            </div>
          </div>
          <hr />

          <!-- <div class="d-flex justify-content-between mb-12">
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
          
          </div> -->

          <div class="flex justify-content-end">
            <div class="">
              <!-- <div class="d-flex"> -->
              <!-- <button class="btn btn-primary" style="margin-right: 2px !important;" @click="grantAccess">Grant Access</button> -->
              <button class="btn btn-dark me-4" @click="openAddResidentPopup(unit)">Add non resident</button>
            <!-- </div> -->
            </div>
            <div class="">
              <button class="btn btn-dark" @click="openModal(unit)">Assign</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <storageModal ref="storageModal" :id="'unoccupied'"/>
    <unitHistory ref="unitHistory" :id="'unoccupied'" />
    <addResidentModal ref="addNewResidentModal" :loadedFromParking="true" :id="'residentParking'" />
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import unitHistory from "../utils/unitHistory.vue";
import storageModal from "./storage-modal.vue";
import addResidentModal from "../../pages/residents/addResidentModal";
import {config} from "../../config";
export default {
  components: {
    Multiselect,
    storageModal,
    unitHistory,
    addResidentModal
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
      $('#addNewResidentModalresidentParking').modal('show');
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
    openHistoryModal(unit){
      this.$parent.showLeaseHistory(unit);
    },
    openModal(unit){
      this.$refs.storageModal.building_id = this.$gate.building_id;
      this.$refs.storageModal.form.unit_id = unit.id;
      this.$refs.storageModal.loadUsers();
      $('#storageModalunoccupied').modal('show');
    },
    loadData(){

      let building_id = this.building ? this.building.id : 0;
      if (building_id == 0) return;

      this.showLoader();

      this.$http
        .get("/storage?building_id="+building_id)
        .then((response) => {
          this.units = response.data;
          this.units = this.units.filter(unit => unit.occupied === 0);
          this.units = this.units.filter(unit => unit.is_physical === 1);
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
        
    },
    getBuildings(){
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
      

      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to vacate the resident?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();
          this.$http
            .get("/remove_and_recycle/"+fob.token_idd)
            .then((response) => {
              this.loadData();
              Toast.fire({
                  icon: 'success',
                  title: 'Fob recycled succesfully',
              });
              this.removeLoader();
            })
            .catch((error) => {
              console.error(error);
            });
        }
      });
      
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
    padding: 9px 14px !important;
    font-size: 12px;
  }
}
</style>
