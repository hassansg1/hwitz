<template>
  <div>
    <div class="row mb-54 staff-user" v-if="!loadedAsAComponentInDashboard">
      <div class="col-lg-6">
        <p class="text-normal-bold-md mb-12 color-secondary">Buildings</p>
        <div class="d-flex flex-row flex-wrap mb-28">
          <span
              class="text-normal-bold-md building-badge select-cursor"
              v-for="(building, index) in buildings"
              :class="{ active: buildingIndex === index }"
              @click="selectBuilding(index)"
          >{{ building.name }}</span
          >
        </div>
      </div>
    </div>
    <div class="rounded-lg pages-style">

      <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              class="active"
              id="staff-user-tab"
              data-bs-toggle="pill"
              data-bs-target="#staff-user"
              type="button"
              role="tab"
              aria-controls="staff-user"
              aria-selected="true"
          >
            Staff Users
          </div>
        </li>
        <li class="nav-item me-56 mb-12" role="presentation">
          <div
              id="admin-analytics-tab"
              data-bs-toggle="pill"
              data-bs-target="#staff-users-sub-roles"
              type="button"
              role="tab"
              aria-controls="staff-users-sub-roles"
              aria-selected="false"
              @click="loadSubUserStaffData()"
          >
            Staff User's Sub Roles
          </div>
        </li>
      </ul>
      <div class="tab-content border-0 bg-white" id="pills-tabContent">
        <div
            class="tab-pane fade show active"
            id="staff-user"
            role="tabpanel"
            aria-labelledby="staff-user-tab"
        >
          <div class="staff-user">

            <div class="row mb-54" v-if="!loadedAsAComponentInDashboard">
              <div class="col-lg-6">
                <p class="text-normal-bold-md mb-12 color-secondary">User</p>
                <div class="d-flex flex-row flex-wrap mb-28">
          <span
              class="text-normal-bold-md building-badge select-cursor"
              v-for="(userType, index) in usertypess"
              :class="{ active: userTypeIndex === index }"
              @click="selectUserType(index)"
          >{{ userType }}</span
          >
                </div>
              </div>
            </div>

            <div class="d-flex flex-column flex-md-row mb-36">
              <div class="me-44 align-self-md-center">
                <h3 class="h3 mb-12 mb-md-0" v-if="!loadedAsAComponentInDashboard">Staff Users -
                  {{ selectedBuildingObj ? selectedBuildingObj.name : '' }}</h3>
                <h3 class="h3 mb-0" v-else>Assigned Staff</h3>
              </div>
              <div>
                <router-link v-if="loadedAsAComponentInDashboard" :to="'add-staff-user/' + building_id"
                             class="btn bkg-success"> + Add new staff
                </router-link>
                <router-link v-else :to="'add-staff-user/' + selectedBuildingObj.id" class="btn bkg-success"> + Add new
                  staff
                </router-link>
              </div>
            </div>

            <div class="row gy-3 mb-54">
              <div class="col-xl-3 col-lg-4 col-md-6" v-for="(user,index) in staffUsers">
                <div class="card">
                  <div class="d-flex justify-content-between">
                    <div class="d-flex">
                      <div class="me-8">
                        <h3 class="h3">{{ user.firstname + " " + user.lastname }}</h3>
                      </div>
                      <div class="me-8">
                        <router-link
                            :to="
                    'edit-staff-user/' + selectedBuildingObj.id + '/' + user.id
                  "
                        ><img src="/images/icons/edit.png" alt=""
                        /></router-link>
                      </div>
                      <div>
                        <img
                            src="/images/icons/trash.png"
                            alt=""
                            class="select-cursor"
                            @click="deleteStaffUser(user.id)"
                        />
                      </div>
                    </div>
                    <div>
                      <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                          <div class="avatar-sm">
                            <img :src="'/images/test-avatar.png'" class="avatar-img"/>
                          </div>
                          <span class="verifiyIcon">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="10"
                        height="10"
                        viewBox="0 0 38 38"
                        fill="none"
                    >
                      <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
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
                    <p class="mb-0 text-normal color-primary">{{ user.mobile }}</p>
                  </div>

                  <div class="mb-8">
                    <p class="mb-0 text-normal color-secondary">
                      {{ user.email }}
                    </p>
                  </div>
                  <hr class="mt-0 mb-11"/>
                  <div class="mb-12">
                    <p class="mb-0 text-normal color-secondary">
                      {{ user.usertype_name }}
                    </p>
                  </div>
                  <hr class="mt-0 mb-20"/>

                  <div class="d-flex justify-content-between mb-44">
                    <div class="form-check form-switch p-0">


                      <!-- <label class="switch mb-0 mt-0">
                        <input type="checkbox" checked role="switch" />
                        <span class="slider round"></span>
                      </label> -->


                      <!-- <input
                        class="form-check-input switch-success-sm"
                        type="checkbox"
                        role="switch"
                        id="userSwitch"
                        checked
                      /> -->
                      <!-- <label
                        class="form-check-label text-normal color-secondary"
                        for="userSwitch"
                        >Allow tech permissions
                      </label> -->
                    </div>

                    <div class="icon-sm">
                      <router-link :to="{ name: 'messages' }"
                      ><img src="/images/icons/chats.png" class="icon-img" alt=""
                      /></router-link>
                    </div>
                  </div>

                  <div class="ms-auto">
                    <button class="ms-auto btn btn-dark" @click="gotoDetail(user.id)">
                      View Detail
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center mb-54" v-if="loadedAsAComponentInDashboard">
              <router-link class="a-link" :to="'/staff'">View All</router-link>
            </div>
            <div class="d-flex justify-content-center mb-54">
              <span v-if="!staffUsers || staffUsers.length == 0">No Data Found.</span>
            </div>

            <!-- <div class="mb-28">
              <div class="h3 mb-28">Work report</div>

              <div class="row">
                <div class="col-lg-4 col-md-6">
                  <div class="card">
                    <div class="d-flex justify-content-between mb-8">
                      <p class="mb-0 text-normal-bold-md color-secondary">
                        orders report
                      </p>
                      <p class="mb-0 text-medium color-primary">Print report</p>
                    </div>

                    <div class="d-flex justify-content-between">
                      <div>
                        <h3 class="h3">
                          Staff work order
                          <span class="color-primary">{{ selectedWorkorder }}</span>
                        </h3>
                      </div>

                      <div class="position-relative">
                        <img
                          class="select-cursor"
                          src="/images/icons/down-arrow.png"
                          alt=""
                          @click="toggledropdown()"
                        />

                        <div
                          class="position-absolute dropdown-chart"
                          v-if="isShowDropdown"
                        >
                          <div class="card">
                            <p class="mb-0 text-normal-bold-md color-secondary">
                              label
                            </p>
                            <p class="mb-0 text-normal-bold-lg">
                              {{ selectedWorkorder }}
                            </p>
                            <hr class="my-12" />
                            <p
                              class="mb-0 text-sm-bold select-cursor option"
                              :class="{
                                selectedOption: item === selectedWorkorder,
                              }"
                              v-for="(item, index) in staffWorkOrder"
                              @click="selectWorkorder(index)"
                            >
                              {{ item }}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <Bar
                        id="my-chart-id"
                        :ref="chartRef"
                        :options="options"
                        :data="chartData"
                        :plugins="[backgroundBar]"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
        <div
            class="tab-pane fade"
            id="staff-users-sub-roles"
            role="tabpanel"
            aria-labelledby="staff-users-sub-roles-tab"
        >
          <StaffUsersSubRoles ref="StaffUsersSubRoles"></StaffUsersSubRoles>
        </div>
        <div
            class="tab-pane fade"
            id="user-analytics"
            role="tabpanel"
            aria-labelledby="user-analytics-tab"
        >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import StaffUsersSubRoles from "./staff-users-sub-roles.vue";
import {ref} from "vue";
import {Bar} from "vue-chartjs";
import {config} from "../../config";

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

export default {
  name: "Admin",
  components: {DatePicker, Bar, StaffUsersSubRoles},

  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
    const dragValue = ref(null);
    const chartRef = ref();
    const backgroundBar = {
      id: "backgroundBar",
      beforeDatasetsDraw(chartRef, args, pluginOptions) {
        const {
          data,
          ctx,
          chartArea: {top, bottom, left, right, width, height},
          scales: {x, y},
        } = chartRef;
        ctx.save();
        const segment = width / data.datasets[0].data.length;
        const barWidth =
            segment *
            data.datasets[0].barPercentage *
            data.datasets[0].categoryPercentage;
        const borderRadius = 8;
        ctx.fillStyle = "#F2F5F9";
        data.labels.forEach((dataset, index) => {
          ctx.beginPath();
          ctx.moveTo(
              x.getPixelForValue(index) - barWidth / 2 + borderRadius,
              top
          );
          ctx.lineTo(
              x.getPixelForValue(index) + barWidth / 2 - borderRadius,
              top
          );
          ctx.quadraticCurveTo(
              x.getPixelForValue(index) + barWidth / 2,
              top,
              x.getPixelForValue(index) + barWidth / 2,
              top + borderRadius
          );
          ctx.lineTo(
              x.getPixelForValue(index) + barWidth / 2,
              bottom - borderRadius
          );
          ctx.quadraticCurveTo(
              x.getPixelForValue(index) + barWidth / 2,
              bottom,
              x.getPixelForValue(index) + barWidth / 2 - borderRadius,
              bottom
          );
          ctx.lineTo(
              x.getPixelForValue(index) - barWidth / 2 + borderRadius,
              bottom
          );
          ctx.quadraticCurveTo(
              x.getPixelForValue(index) - barWidth / 2,
              bottom,
              x.getPixelForValue(index) - barWidth / 2,
              bottom - borderRadius
          );
          ctx.lineTo(
              x.getPixelForValue(index) - barWidth / 2,
              top + borderRadius
          );
          ctx.quadraticCurveTo(
              x.getPixelForValue(index) - barWidth / 2,
              top,
              x.getPixelForValue(index) - barWidth / 2 + borderRadius,
              top
          );
          ctx.closePath();
          ctx.fill();
        });
      },
    };
    return {
      chartRef,
      backgroundBar,
      chartData: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Aug"],
        datasets: [
          {
            data: [41, 20, 12, 62, 71, 86, 76],
            label: "none",
            barPercentage: 0.8,
            barThickness: 28,
            categoryPercentage: 0.8,
            minBarLength: 2,
            backgroundColor: "#47c5fe",
            borderRadius: 8,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            display: true,
            grid: {
              display: false,
            },
          },
          y: {
            display: false,
            beginAtZero: true,
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
      isShowDropdown: false,
      buildingIndex: 0,
      selectedBuildingObj: "",
      buildings: [],
      staffUsers: [],
      usertypess: [
        "All",
        "Agent",
        "Custodian",
        "Mail",
        "Maintenance",
        "Manager",
        "First Responder",
      ],
      userTypeIndex: 0,
      selectedUserType: "",

      loadedAsAComponentInDashboard: false,
      building_id: ''
    };
  },
  props: ["loadedFromDashboard"],
  created() {
    this.getBuildings();
  },
  methods: {
    selectBuilding(index) {
      if (index < 0) index = 0;
      this.buildingIndex = index;
      this.selectedBuildingObj = this.buildings[index];
      this.getBuildingsStaffUsers();
      this.$refs.StaffUsersSubRoles.loadData();
    },

    selectWorkorder(index) {
      this.workorderIndex = index;
      this.selectedWorkorder = this.staffWorkOrder[index];
      console.log("what is this", this.selectedWorkorder[index]);
    },

    selectUserType(index) {
      this.userTypeIndex = index;
      this.selectedUserType = this.usertypess[index];
      this.getBuildingsStaffUsers();
    },
    gotoDetail(id) {
      this.$router.push("/staff-detail/" + id);
    },

    toggledropdown() {
      this.isShowDropdown = !this.isShowDropdown;
    },
    getBuildings() {
      if (!this.loadedFromDashboard) {
        this.showLoader();
      }
      this.$http
          .get("/buildings")
          .then((response) => {
            console.log(response);
            this.buildings = response.data.data;
            const index = this.buildings.findIndex(obj => obj.id === this.$gate.building_id);
            this.selectBuilding(index);
          })
          .catch((error) => {
            console.error(error);
          });
    },
    getBuildingsStaffUsers() {
      if (!this.loadedFromDashboard) {
        this.showLoader();
      }
      let building_id = this.selectedBuildingObj ? this.selectedBuildingObj.id : '';
      if (this.loadedAsAComponentInDashboard) building_id = this.building_id;

      this.$http
          .get(
              "/getBuildingStaffUsers?building_id=" +
              building_id +
              "&type=" +
              this.selectedUserType
          )
          .then((response) => {
            this.staffUsers = response.data.data;
            if (this.loadedAsAComponentInDashboard) {
              this.staffUsers = this.staffUsers.slice(0, 4);
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },

    deleteStaffUser(id) {
      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to delete this user?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();

          this.$http
              .post(
                  "/deleteStaffUser/" +
                  id +
                  "/building/" +
                  this.selectedBuildingObj.id
              )
              .then((response) => {
                Toast.fire({
                  icon: "success",
                  title: "Staff user deleted successfully.",
                });
                this.getBuildingsStaffUsers();
                this.removeLoader();
              })
              .catch((error) => {
                console.error(error);
              });
        }
      });
    },
    loadSubUserStaffData() {
      this.$refs.StaffUsersSubRoles.loadData();
    }
  },
};
</script>
