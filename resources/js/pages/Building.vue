<template>
  <div class="building-section">
    <!-- <div class="row mb-5">
      <div class="col-12 text-center">
        <a class="me-2" href="">Select</a>
        <a class="me-2" href="">Select All</a>
        <a class="me-2" href="">Delete All</a>
      </div>
    </div> -->
    <div class="row g-4">
      <div class="col-xl-3 col-lg-4 col-md-6 col-12" v-for="building in buildings" :key="building.id">
        <div class="card">
          <div class="custom-container" style="position: relative;">
            <div class="card-img-container">
              <img :src="building.picture ? building.picture :'/images/card-head.png'" class="card-img card-img-top" alt="..." />
            </div>
            <label class="building-img-icon color-secondary mb-12" type="button" v-if="$gate.permissions.includes('edit_building_image')">
              <div class="bg-white py-1 px-2 rounded">
                <input
                type="file"
                id="myFile"
                name="filename"
                class="d-none"
                multiple
                @change="uploadBuildingImage(building.id)"
              />
              <i class="fa fa-camera select-cursor color-secondary-dark" title="Edit Image"></i>
              </div>
            </label>
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="d-flex">
                <h3 class="h3 mb-8 me-14 text-truncate card-name" :title="building.name">
                  {{ building.name }}
                </h3>
                <div class="me-12">
                  <img
                      v-if="building.urgentTasks"
                      class="select-cursor"
                      src="/images/icons/warning.png"
                      alt="warning"
                      id="dropdownMenuOffset"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                  />
                  <img
                      v-else-if="building.unverifiedUsers || building.overDue"
                      class="select-cursor"
                      src="/images/icons/yellow_warning.png"
                      alt="warning"
                      id="dropdownMenuOffset"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      style="height: 25px;"
                  />

                  <div class="dropdown">
                    <ul
                        class="dropdown-menu dropdown-menu-end border-standered"
                        aria-labelledby="dropdownMenuOffset"
                    >
                      <li
                          class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                      >
                        Attention Needed
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold">
                        <span class="color-danger">{{ building.unverifiedUsers ? building.unverifiedUsers : 0 }}</span> Unverified users
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold">
                        <span class="color-danger">{{ building.urgentTasks ? building.urgentTasks : 0 }}</span> Urgent tasks
                      </li>
                      <li class="px-2 py-1 border-bottom text-sm-bold">
                        <span class="color-danger">{{ building.overDue ? building.overDue : 0 }}</span> Overdue regular
                        tasks
                      </li>
                      <!--                      <li class="px-2 py-1 border-bottom text-sm-bold">-->
                      <!--                        <span class="color-danger">07</span> Documents pending-->
                      <!--                        to approve-->
                      <!--                      </li>-->
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="text-normal-bold-md color-secondary mb-4">
                Staff assigned
              </p>
              <div class="mb-32"> 
                <div class="d-flex flex-row flex-warp mb-33 min-h-39 overflow-hidden">
                  <div
                      class="avatar-sm"
                      v-if="
                      building.staff_users && building.staff_users.length > 0
                    "
                      v-for="users in building.staff_users"
                  >
                    <img
                        :src="
                        users.profile_picture
                          ? users.profile_picture
                          : '/images/default-user.jpg'
                      "
                        class="avatar-img"
                        :title="users.firstname + ' ' + users.lastname"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <div class="mt-2">
                <button class="btn btn-dark" @click="$emit('setBuildingInSession',building.id)">View all features</button>
              </div>
              <!-- <div>
                <div class="form-check form-switch mb-0">
                  <input
                      class="form-check-input switch-success building-lockdown-checkbox"
                      type="checkbox"
                      role="switch"
                      id="flexSwitchCheckChecked"
                      v-model="building.lockdown"
                      @change="changeLockdownStatus($event, building.id)"
                  />
                </div>
                <label class="text-normal-bold-md color-secondary mb-0">{{
                    building.lockdown == 1 ? "locked" : "unlocked"
                  }}</label>
              </div> -->

              <div>
                  <!-- <div class="d-flex mt-11"> -->
                  <div class="form-check ps-0">
                      <div class="text-normal font-weight-600 color-secondary text-center">Lockdown</div>
                      <div class="form-check form-switch p-0 text-center" v-if="$gate.permissions.includes('edit_lockdown_status')">
                          <label class="switch mb-0">
                          <input type="checkbox" role="switch" v-model="building.lockdown" @change="changeLockdownStatus($event, building)"/>
                          <span class="slider building-lockdown-slider round"></span>
                          </label>
                      </div>
                      <div class="text-normal font-weight-600 color-secondary text-center">{{building.lockdown == 1 ? 'On' : 'Off'}}</div>
                  </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
</template>
<script>
import {config} from "../config";
export default {
  data() {
    return {
      buildings: [],
      windowEventObj : Event,
      timer : null
    };
  },
  methods: {
    uploadBuildingImage(id){
      console.log(id);
      let file = event.target.files[0];
      let self = this;
      
      if (file) {
          const reader = new FileReader();

          reader.onload = function (e) {
              const base64Image = e.target.result;
              self.showLoader();
              self.$http
                .post("/updateBuildingImage/"+id, {picture : base64Image})
                .then((response) => {
                  const obj = self.buildings.find(obj => obj.id == id);
                  obj.picture = base64Image;
                  self.removeLoader();
                })
                .catch((error) => {
                  console.error(error);
                });
          };

          reader.readAsDataURL(file);

          
      }
    },  
    fetchBuildings(showLoader = true) {
      if(showLoader) this.showLoader();
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;

            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    changeLockdownStatus(event, building) {
      let value = building.lockdown ? 1 : 0;
      let id = building.building_id;
      let text = 'deactivate';
      if(value == 1) text = 'activate';
      Swal.fire({
              title: config.confirmBoxTitle,
              text: "Are you sure you want to "+text+" the lockdown?",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: config.confirmButtonColor,
              cancelButtonColor: config.cancelButtonColor,
              confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
            this.showLoader();
            this.$http
            .get("/activateOrDeactivateLockdown/"+building.id+"/"+value)
            .then((response) => {
                // this.loadData();
                Toast.fire({
                    icon: 'success',
                    title: 'Lockdown '+text+'d succesfully',
                });
                this.removeLoader();
            })
            .catch((error) => {
                console.error(error);
            });
        }else{
            const obj = this.buildings.find(obj => obj.id == building.id);
            obj.lockdown = !building.lockdown;
        }
      });
    },
  },
  created() {
    console.log(this.$root.tasksCount,'ROOT');
    this.fetchBuildings();
  },
  mounted() {
    this.timer = setInterval(function () {
      this.fetchBuildings(false);
    }.bind(this), 10000); 
  },
  beforeDestroy(){
    clearInterval(this.timer);
  }
};
</script>
<style scoped>
  .fobs-section .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #c2ff69;
    /* background-color: #ff6969; */
    -webkit-transition: 0.4s;
    transition: 0.4s;
  }
  input:checked + .slider {
  /* background-color: #c2ff69; */
    background-color: #ff6969;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #ff6969;
    /* box-shadow: 0 0 1px #c2ff69; */
  }
</style>
