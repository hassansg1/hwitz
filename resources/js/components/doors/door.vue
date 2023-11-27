<template>
  <div class="doors-section">
    <!-- <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <div class="d-flex mt-8">
        <div class="form-check form-switch ps-20 me-8">
          <label class="switch mb-0">
            <input type="checkbox" role="switch" />
            <span class="slider round"></span>
          </label>
        </div>
        <div class="text-medium color-primary mt-2">Lock all</div>
      </div>
      <div class="d-flex mt-8 me-20">
        <div class="form-check form-switch me-8 ps-20">
          <label class="switch mb-0">
            <input type="checkbox" checked role="switch" />
            <span class="slider round"></span>
          </label>
        </div>
        <div class="text-medium color-primary mt-2">Unlock all</div>
      </div>
      <a class="align-self-center a-link me-20" href="">Select all</a>
    </div> -->

    <!-- <div class="page-number d-flex text-normal color-secondary mb-12">
      <div class="align-self-center me-12">Entries per page:</div>

      <select name="" id="">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div> -->
    <div class="table-responsive">
      <table class="mb-28">
        <tr>
          <th>Doors Name</th>
          <!-- <th>Authorized to</th> -->
          <th>Current Status</th>
          <th>Grant</th>
          <th>Unlock Schedule <a href="/sso/schedules" target="_blank">(Manage)</a></th>
          <!-- <th>Data</th>
          <th>Access</th> -->
        </tr>

        <tr v-for="door in doors">
          <td>
            <div class="text-medium-bold">{{door.name}}</div>
          </td>
          <!-- <td>
            <div class="d-flex">
              <div class="avatar-sm">
                <img src="/images/test-avatar.png" class="avatar-img" alt="" />
              </div>
              <div class="avatar-sm">
                <img src="/images/test-avatar.png" class="avatar-img" alt="" />
              </div>
              <div class="avatar-sm">
                <img src="/images/test-avatar.png" class="avatar-img" alt="" />
              </div>
              <div class="avatar-sm">
                <img src="/images/test-avatar.png" class="avatar-img" alt="" />
              </div>
            </div>
          </td> -->
          <td>
            <div>
              <div class="d-flex mt-8">
                <div class="text-medium color-secondary me-12" v-if="door.locked == 1">Locked</div>
                <div class="text-medium color-secondary me-12" v-else>Unlocked</div>
                <div class="form-check form-switch p-0">
                  <label class="switch mb-0">
                    <input type="checkbox" v-model="door.locked" role="switch" @click="doorAction(door.id , door.locked == 1 ? 'unlock' : 'lock')" />
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </td>
          <td>
            <i class="fa fa-key select-cursor" style="color: deepskyblue;" @click="doorAction(door.id, 'grant')"></i>
          </td>
          <td>
            <div class="multiselect-border">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize">Unlock Schedule</label>
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="door.schedule"
                  placeholder=""
                  label="name"
                  track-by="id"
                  :options="schedules"
                  :multiple="false"
                  @input="changeDoorSchedule(door)"
                ></multiselect>
              </div>
            </div>
          </td>
          <!-- <td>
            <div>
              <button
                class="btn btn-primary w-136"
                data-bs-toggle="modal"
                data-bs-target="#doorsModal"
              >
                Show Data
              </button>
            </div>
          </td>

          <td>
            <div>
              <button class="btn bkg-gray-light text-white px-9 w-160">
                Add Exception
              </button>
            </div>
          </td> -->
        </tr>
      </table>

    </div>

    <doorModal />
  </div>
</template>

<script>
import doorModal from "./door-modal.vue";
export default {
  components: {
    doorModal,
  },
  props : ["loadedAsAComponentInDashboard"],
  data() {
    return {
      doors : '',
      schedules : []
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadData();
    }
  },
  created(){
    this.loadData();
  },
  methods : {
    loadData(){
      if(this.building_id == 0) return;
      console.log(this.loadedAsAComponentInDashboard,'if(!this.loadedAsAComponentInDashboard) {')
      if(!this.loadedAsAComponentInDashboard) {
        this.showLoader();
      }
      this.$http
        .get("/doors/"+this.building_id)
        .then((response) => {
          this.doors = response.data.assets;
          this.schedules = response.data.schedules;

          let createSchedule = {id : 0 , name : 'Create new (template)'};
          this.schedules.unshift(createSchedule);
          this.$parent.totalDoors = this.doors.length;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
        
    },
    doorAction(id,action){
      // this.showLoader();
      // asset/door_status/265/unlock
      this.$http
        .get("/asset/door_status/"+id+'/'+action)
        .then((response) => {
          Toast.fire({
              icon: 'success',
              title: this.capitalizeFirstLetter(action) + 'action performed succesfully',
          });
          // this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    changeDoorSchedule(door){
      if(door.schedule){
        let schedule_id = door.schedule.id;
        if(schedule_id == 0){
          console.log('REDIRECT');
        }else{
          
          this.showLoader();
          this.$http
            .get("/asset/schedule/"+door.id+'/'+schedule_id)
            .then((response) => {
              Toast.fire({
                  icon: response.data.status,
                  title: response.data.message,
              });
              this.removeLoader();
            })
            .catch((error) => {
              console.error(error);
            });
        }
      }
    }
  }
};
</script>

<style scoped></style>
