<template>
  <div class="dashboard-section">
    <div class="mb-70">
      <vitalSigns :loadedAsAComponentInDashboard="true" />
    </div>
    <!-- <div class="mb-32">
      <div class="row gy-3">
        <div class="col-xl-4 col-lg-6">
          <h3 class="h3 mb-28">Monthly income</h3>
          <div>
            <areachart />
          </div>
        </div>
        <div class="col-xl-4 d-none d-xl-block"></div>
        <div class="col-xl-4 col-lg-6">
          <div class="building-view">
            <img src="/images/test-bg.jpg" class="building-img" alt="" />
          </div>
        </div>
      </div>
    </div> -->

    <hr class="mt-0 mb-44" />

    <div class="row gy-3 mb-70">
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card p-24">
          <p class="text-normal-bold-md color-secondary mb-8">Total income</p>
          <h3 class="h3 color-primary mb-8">$25,495</h3>
          <p class="text-normal mb-0">
            <span class="color-primary">+20 </span>form past month
          </p>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card p-24">
          <p class="text-normal-bold-md color-secondary mb-8">Total expense</p>
          <h3 class="h3 color-danger mb-8">$25,495</h3>
          <p class="text-normal mb-0">
            <span class="color-danger">-20 </span>form past month
          </p>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card p-24">
          <p class="text-normal-bold-md color-secondary mb-8">
            Expense analtics
          </p>
          <div class="row">
            <div class="col-8">
              <div class="progress mb-8">
                <div
                  class="progress-bar"
                  role="progressbar"
                  style="width: 0%"
                  aria-valuenow="0"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
              <div class="d-flex mb-8">
                <div class="align-self-center dot bg-gray-light me-8"></div>
                <div class="align-self-center text-sm">Employee</div>
              </div>
              <p class="text-normal mb-0">$5,000</p>
            </div>
            <div class="col-4">
              <div class="progress mb-8">
                <div
                  class="progress-bar"
                  role="progressbar"
                  style="width: 100%"
                  aria-valuenow="100"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
              <div class="d-flex mb-8">
                <div class="align-self-center dot bkg-primary me-8"></div>
                <div class="align-self-center text-sm">Bills</div>
              </div>
              <p class="text-normal mb-0">$5,000</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card p-18">
          <p class="text-normal-bold-md color-secondary mb-8 lh-1">
            Total income
          </p>
          <h3 class="h3 color-primary mb-8 lh-1">$25,495</h3>
          <p class="text-normal mb-8 lh-1">used form 02.04.2020</p>
          <div class="progress mb-8">
            <div
              class="progress-bar"
              role="progressbar"
              style="width: 75%"
              aria-valuenow="75"
              aria-valuemin="0"
              aria-valuemax="100"
            ></div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between flex-column flex-md-row mb-34">
      <div class="align-self-md-center">
        <h3 class="h3 mb-12 mb-md-0">Doors ({{ totalDoors }})</h3>
      </div>
    </div>

    <div v-observe-visibility="doorsVisibilityChanged" >
      <doors v-if="isDoorsComponentVisible" :loadedAsAComponentInDashboard="true" />
    </div>

    <div v-observe-visibility="staffVisibilityChanged" >
      <staffUsers ref="staffUsers" v-if="isStaffComponentVisible" :loadedFromDashboard="true" />
    </div>
    <!-- <div class="mb-70">
      <div class="d-flex flex-column flex-md-row mb-36">
        <div class="me-44 align-self-md-center">
          <h3 class="h3 mb-12 mb-md-0">Assigned staff</h3>
        </div>
        <div>
          <button
            class="btn bkg-success"
            data-bs-toggle="modal"
            data-bs-target="#dashboardModal"
          >
            + Add staff
          </button>
        </div>
      </div>
      <div class="row gy-3 mb-28">
        <div class="col-xl-3 col-lg-4 col-md-6" v-for="staff in visibleStaff">
          <div class="card p-24">
            <div class="d-flex justify-content-between">
              <div class="d-flex">
                <div class="me-8">
                  <h3 class="h3">{{ staff.name }}</h3>
                </div>
                <div class="me-8">
                  <img src="/images/icons/edit.png" alt="" />
                </div>
                <div>
                  <img
                    src="/images/icons/trash.png"
                    alt=""
                    class="select-cursor"
                  />
                </div>
              </div>
              <div>
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <div class="avatar-sm">
                      <img
                        :src="'/images/test-avatar.png'"
                        class="avatar-img"
                      />
                    </div>
                    <span class="verifiyIcon">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="10"
                        height="10"
                        viewBox="0 0 38 38"
                        fill="none"
                      >
                        <circle cx="19" cy="19" r="19" fill="#C2FF69" />
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M24.9228 14.761C25.2285 15.0455 25.2367 15.5148 24.9413 15.8091L17.5005 23.2214C17.3536 23.3677 17.1507 23.4495 16.9393 23.4474C16.728 23.4454 16.5268 23.3598 16.383 23.2106L13.0475 19.7515C12.7582 19.4516 12.7762 18.9826 13.0878 18.704C13.3993 18.4255 13.8863 18.4428 14.1756 18.7428L16.9583 21.6286L23.8344 14.7788C24.1299 14.4845 24.6172 14.4765 24.9228 14.761Z"
                          fill="black"
                        />
                      </svg>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="mb-0 text-normal color-primary">123 456 7890</p>
            </div>

            <div class="mb-8">
              <p class="mb-0 text-normal color-secondary">johndoe@mail.com</p>
            </div>
            <hr class="mt-0 mb-11" />
            <div class="mb-12">
              <p class="mb-0 text-normal color-secondary">Developer</p>
            </div>
            <hr class="mt-0 mb-20" />

            <div class="d-flex justify-content-between mb-44">
              <div class="form-check form-switch p-0">
                <label class="switch mb-0 mt-0">
                  <input type="checkbox" checked role="switch" />
                  <span class="slider round"></span>
                </label>
              </div>

              <div class="icon-sm">
                <img src="/images/icons/chats.png" class="icon-img" alt="" />
              </div>
            </div>

            <div class="ms-auto">
              <button class="btn bg-dark text-white">View Detail</button>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <a class="a-link" @click="loadMoreStaff">{{
          !showAllStaff ? "View More" : "View less"
        }}</a>
      </div>
    </div> -->

    <div class="mb-70">
      <div class="d-flex justify-content-between flex-column flex-md-row mb-28">
        <div class="align-self-md-center">
          <h3 class="h3 mb-12 mb-md-0">Transaction</h3>
        </div>
        <div class="d-flex align-self-md-center flex-wrap flex-row">
          <div class="align-self-center">
            <button class="btn bg-dark text-white me-32 mb-12 mb-md-0">
              View history
            </button>
          </div>
          <div class="a-link align-self-center me-32 mb-12 mb-md-0">
            Export to .xls
          </div>
          <div class="a-link align-self-center me-32 mb-12 mb-md-0">Select</div>
          <div class="a-link align-self-center me-32 mb-12 mb-md-0">
            Select all
          </div>
          <div class="a-link align-self-center mb-12 mb-md-0">Delete all</div>
        </div>
      </div>

      <div class="">
        <div class="text-start">
          <div class="table-responsive">
            <table>
              <tr>
                <th>some</th>
                <th>Invoice</th>
                <th>Date</th>
                <th>Period</th>
                <th>Submited by</th>
                <th>Proof</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>

              <tr class="border_bottom">
                <td>
                  <div>
                    <input
                      class="form-check-input me-12"
                      type="checkbox"
                      value=""
                      id="flexCheckDefault"
                    />
                  </div>
                </td>
                <td>
                  <span class="text-medium-bold">10-216</span>
                </td>
                <td>
                  <div class="text-medium color-secondary">16.01.2023</div>
                </td>
                <td>
                  <div class="text-medium color-secondary">May 2023</div>
                </td>
                <td>
                  <div class="d-flex">
                    <div class="avatar-sm me-9">
                      <img
                        src="/images/worker.png"
                        class="avatar-img"
                        alt="card"
                      />
                    </div>
                    <div class="align-self-center">
                      <div class="text-medium">John Doe</div>
                    </div>
                  </div>
                </td>

                <td>
                  <div>
                    <a class="a-link">View invoice</a>
                  </div>
                </td>
                <td>
                  <div class="text-medium-bold">$120</div>
                </td>

                <td class="w-25">
                  <div class="status paid">Paid</div>
                </td>
              </tr>
              <tr class="border_bottom">
                <td>
                  <div>
                    <input
                      class="form-check-input me-12"
                      type="checkbox"
                      value=""
                      id="flexCheckDefault"
                    />
                  </div>
                </td>
                <td>
                  <span class="text-medium-bold">10-216</span>
                </td>
                <td>
                  <div class="text-medium color-secondary">16.01.2023</div>
                </td>
                <td>
                  <div class="text-medium color-secondary">May 2023</div>
                </td>
                <td>
                  <div class="d-flex">
                    <div class="avatar-sm me-9">
                      <img
                        src="/images/worker.png"
                        class="avatar-img"
                        alt="card"
                      />
                    </div>
                    <div class="align-self-center">
                      <div class="text-medium">John Doe</div>
                    </div>
                  </div>
                </td>

                <td>
                  <div>
                    <a class="a-link">View invoice</a>
                  </div>
                </td>
                <td>
                  <div class="text-medium-bold">$120</div>
                </td>

                <td class="w-25">
                  <div class="status open">Open</div>
                </td>
              </tr>
              <tr class="border_bottom">
                <td>
                  <div>
                    <input
                      class="form-check-input me-12"
                      type="checkbox"
                      value=""
                      id="flexCheckDefault"
                    />
                  </div>
                </td>
                <td>
                  <span class="text-medium-bold">10-216</span>
                </td>
                <td>
                  <div class="text-medium color-secondary">16.01.2023</div>
                </td>
                <td>
                  <div class="text-medium color-secondary">May 2023</div>
                </td>
                <td>
                  <div class="d-flex">
                    <div class="avatar-sm me-9">
                      <img
                        src="/images/worker.png"
                        class="avatar-img"
                        alt="card"
                      />
                    </div>
                    <div class="align-self-center">
                      <div class="text-medium">John Doe</div>
                    </div>
                  </div>
                </td>

                <td>
                  <div>
                    <a class="a-link">View invoice</a>
                  </div>
                </td>
                <td>
                  <div class="text-medium-bold">$120</div>
                </td>

                <td class="w-25">
                  <div class="status draft">Draft</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-70" v-observe-visibility="cameraVisibilityChanged">
      <cameras ref="cameras" :loadedAsAComponentInDashboard="true" v-if="isCameraComponentVisible" />
    </div>
    <div class="row gy-3 mb-28" v-observe-visibility="fobsVisibilityChanged">
      <div class="col-xl-12 col-lg-12">
        <div class="mb-64">
          <h3 class="h3 mb-28">FOBs assigned</h3>
          <fobs :loadedAsAComponentInDashboard="true" ref="fobs" v-if="isFobsComponentVisible" />
        </div>
      </div>

    </div>
    <div class="row gy-3 mb-28" v-observe-visibility="parkingVisibilityChanged">
      <div class="col-xl-12 col-lg-12">
        <div class="mb-64">
          <h3 class="h3 mb-28">Parking Assigned</h3>
          <parking :loadedAsAComponentInDashboard="true" v-if="isParkingComponentVisible" ></parking>
        </div>
      </div>
    </div>
    <div class="row gy-3 mb-28" v-observe-visibility="storageVisibilityChanged">
      <div class="col-xl-12 col-lg-12">
        <div class="mb-64">
          <h3 class="h3 mb-28">Storage Assigned</h3>
          <storage :loadedAsAComponentInDashboard="true" v-if="isStorageComponentVisible" ></storage>
        </div>
      </div>
    </div>

    

    <!-- <div class="row gy-3 mb-28">
      <div class="col-lg-7">
        <h3 class="h3 mb-28">Devices assigned</h3>
        <div class="mb-28">
          <div class="text-start">
            <div class="table-responsive">
              <table>
                <tr>
                  <th>Id</th>
                  <th>Type</th>
                  <th>Devices</th>
                  <th>Location</th>
                  <th>Bypass PSWD</th>
                  <th>Status</th>
                </tr>

                <tr class="border_bottom">
                  <td>
                    <span class="text-medium-bold color-primary">1045216</span>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">Wired</div>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">Smart Tv</div>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">LR</div>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">On</div>
                  </td>
                  <td>
                    <div>
                      <div class="d-flex mt-11">
                        <div class="text-medium color-secondary me-12">On</div>
                        <div class="form-check form-switch p-0">
                          <label class="switch mb-0">
                            <input type="checkbox" checked role="switch" />
                            <span class="slider round"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td colspan="6">
                    <div class="d-flex justify-content-center">
                      <button class="btn btn-table bkg-primary text-white">
                        + Assign new Device
                      </button>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</template>

<script>
import areachart from "../utils/area-chart.vue";
import doors from "../doors/door.vue";
import staffUsers from "../staff/staff-users.vue";
import cameras from "../../pages/cameras/all-camera.vue"
import fobs from "../fobs/inuse.vue"
import parking from "../parking/resident.vue";
import storage from "../storage/occupied.vue";
import vitalSigns from "./mainMenuDashboard.vue";

import { ObserveVisibility } from "vue-observe-visibility";

export default {
  directives: {
    ObserveVisibility
  },
  components: {
    areachart,
    doors,
    staffUsers,
    cameras,
    fobs,
    parking,
    storage,
    vitalSigns
  },
  data() {
    return {
      doors: [],
      staff: [],
      cameras: [],

      visibleDoors: [],
      visibleStaff: [],
      visibleCamers: [],

      showAllDoors: false,
      showAllStaff: false,
      showAllCamers: false,
      
      totalDoors : 0,

      isDoorsComponentVisible : false,
      isStaffComponentVisible : false,
      isCameraComponentVisible : false,
      isFobsComponentVisible : false,
      isParkingComponentVisible : false,
      isStorageComponentVisible : false
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadStaffComponent();
    }
  },

  methods: {
    doorsVisibilityChanged(isVisible){
      if(isVisible && !this.isDoorsComponentVisible) this.isDoorsComponentVisible = isVisible;
    },
    staffVisibilityChanged(isVisible){
      if(isVisible && !this.isStaffComponentVisible) {
        this.isStaffComponentVisible = isVisible;
        this.$nextTick(() => {
          this.loadStaffComponent();
        });
      }
    },
    visibilityChanged(isVisible){
      console.log('IS VISIBLE NOW',isVisible);
    },
    cameraVisibilityChanged(isVisible){
      if(isVisible && !this.isCameraComponentVisible) this.isCameraComponentVisible = isVisible;
    },
    fobsVisibilityChanged(isVisible){
      if(isVisible && !this.isFobsComponentVisible) this.isFobsComponentVisible = isVisible;
    },
    parkingVisibilityChanged(isVisible){
      if(isVisible && !this.isParkingComponentVisible) this.isParkingComponentVisible = isVisible;
    },
    storageVisibilityChanged(isVisible){
      if(isVisible && !this.isStorageComponentVisible) this.isStorageComponentVisible = isVisible;
    },
    loadMoreDoors() {
      if (!this.showAllDoors) {
        this.visibleDoors = this.doors; // Load all elements
        this.showAllDoors = true;
      } else {
        const visibleDoorCount = Math.ceil(this.doors.length / 2);
        this.visibleDoors = this.doors.slice(0, visibleDoorCount);
        this.showAllDoors = false;
      }
    },
    loadMoreStaff() {
      if (!this.showAllStaff) {
        this.visibleStaff = this.staff; // Load all elements
        this.showAllStaff = true;
      } else {
        const visibleStaffCount = Math.ceil(this.staff.length / 2);
        this.visibleStaff = this.staff.slice(0, visibleStaffCount);
        this.showAllStaff = false;
      }
    },
    loadMoreCameras() {
      if (!this.showAllCamers) {
        this.visibleCamers = this.cameras; // Load all elements
        this.showAllCamers = true;
      } else {
        const visibleCameraCount = Math.ceil(this.cameras.length / 2);
        this.visibleCamers = this.cameras.slice(0, visibleCameraCount);
        this.showAllCamers = false;
      }
    },
    loadStaffComponent(){
      this.$refs.staffUsers.loadedAsAComponentInDashboard = true;
      this.$refs.staffUsers.selectedUserType = 'All';
      this.$refs.staffUsers.building_id = this.building_id;
      this.$refs.staffUsers.getBuildingsStaffUsers();
    }
  },

  beforeMount() {


    // Calculate the number of visible items (half of the array length)
    const visibleDoorCount = Math.ceil(this.doors.length / 2);

    // Slice the array to get the initial visible elements
    this.visibleDoors = this.doors.slice(0, visibleDoorCount);

    // Calculate the number of visible items (half of the array length)
    const visibleStaffCount = Math.ceil(this.staff.length / 2);

    // Slice the array to get the initial visible elements
    this.visibleStaff = this.staff.slice(0, visibleStaffCount);

    // Calculate the number of visible items (half of the array length)
    const visibleCameraCount = Math.ceil(this.cameras.length / 2);

    // Slice the array to get the initial visible elements
    this.visibleCamers = this.cameras.slice(0, visibleCameraCount);
  },
  mounted(){
    // this.loadStaffComponent();
  }
};
</script>
