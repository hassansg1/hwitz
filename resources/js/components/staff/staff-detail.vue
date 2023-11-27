<template>
  <div class="staff-detail">
    <div>
      <div class="row">
        <div class="col-12">
          <div class="d-flex flex-column flex-lg-row">
            <div></div>
          </div>
        </div>
      </div>

      <div class="mb-60">
        <div class="row gap-3">
          <div class="col-xl-9 col-lg-12">
            <div class="d-flex flex-column flex-lg-row">
              <div class="me-40 mx-auto mx-lg-0">
                <div class="d-inline mx-auto mx-lg-0">
                  <div class="position-relative text-center me-45">
                    <div class="profile-picture">
                      <img
                        :src="
                          user.profile_pic
                            ? user.profile_pic
                            : '/images/default-user.jpg'
                        "
                        class="profile-img"
                      />
                    </div>
                    <span class="profile-verifiyIcon">
                      <svg
                        class=""
                        xmlns="http://www.w3.org/2000/svg"
                        width="38"
                        height="38"
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

              <div>
                <div class="row">
                  <div class="col-12">
                    <div class="d-flex mb-36">
                      <div class="me-12 mb-28">
                        <h3 class="h3 mb-0">
                          {{ user.firstname + " " + user.lastname }}
                        </h3>
                        <div class="color-primary text-normal">
                          <span>{{ user.mobile }} </span>
                        </div>
                      </div>

                      <!-- <div class="me-69">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 18 18"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M11.7297 1.48979C12.3635 0.856039 13.223 0.5 14.1193 0.5C15.9857 0.5 17.4987 2.01299 17.4987 3.87936C17.4987 4.77562 17.1426 5.63518 16.5089 6.26893L5.48659 17.2912C5.35376 17.4241 5.17361 17.4987 4.98576 17.4987H1.20828C0.817107 17.4987 0.5 17.1816 0.5 16.7904V13.0129C0.5 12.8251 0.574622 12.6449 0.70745 12.5121L11.7297 1.48979ZM14.1193 1.91656C13.5987 1.91656 13.0995 2.12335 12.7314 2.49145L12.3619 2.86093L15.1377 5.63675L15.5072 5.26727C15.8753 4.89918 16.0821 4.39993 16.0821 3.87936C16.0821 2.79533 15.2033 1.91656 14.1193 1.91656ZM14.1361 6.63841L11.3603 3.86258L1.91656 13.3063V16.0821H4.69238L14.1361 6.63841Z"
                            fill="#999999"
                          />
                        </svg>
                      </div> -->
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-12">
                      <p class="mb-0 text-normal-bold-md color-secondary">
                        Email
                      </p>
                      <p class="mb-0 text-normal color-primary">
                        {{ user.email }}
                      </p>
                    </div>
                    <div class="mb-12">
                      <p class="mb-0 text-normal-bold-md color-secondary">
                        Date of Birth
                      </p>

                      <p class="text-normal">{{ user.dob }}</p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-12">
                      <p class="mb-0 text-normal-bold-md color-secondary">
                        Employee number assigned
                      </p>
                      <p class="mb-0 text-normal">
                        232 
                        <!-- | <span class="color-primary">Verify via OTP</span> -->
                      </p>
                    </div>
                    <div class="mb-12">
                      <p class="mb-0 text-normal-bold-md color-secondary">
                        Status
                      </p>

                      <p class="text-normal">
                        {{ user.usertype ? user.usertype.name : "" }}
                        <!-- |<span class="color-primary">Switch position</span> -->
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div id="profile-btn" class="ms-lg-auto d-flex flex-column gap-2">
                <!-- <button class="btn btn-dark">Send Onboarding</button> -->
                <router-link :to="'/messages'" class="btn bkg-primary text-white">Message</router-link>
                <!-- <button class="btn bkg-danger text-white">Deactivate</button> -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-54">
        <div class="d-flex flex-column flex-md-row mb-29">
          <div class="me-23 align-md-self-center">
            <h3 class="h3">Buildings assigned</h3>
          </div>
          <div>
            <button class="btn bkg-success" @click="assignNewBuildingPopup">
              Assign new building
            </button>
          </div>
        </div>

        <div class="mb-66">
          <div class="table-responsive mb-28">
            <table>
              <tr>
                <th>Building</th>
                <!-- <th>Date Assigned</th> -->
                <th>Email</th>
                <th>Entrance</th>
                <th>Action</th>
              </tr>
              <tr
                class="border_bottom"
                v-for="buildingData in staffUserBuildings"
              >
                <td>
                  <span class="text-medium-bold color-primary">{{
                    buildingData.name
                  }}</span>
                </td>
                <!-- <td>
                  <span class="text-medium-md color-secondary">
                    12.02.2022
                  </span>
                </td> -->
                <td>
                  <span class="text-medium-md color-secondary"
                    >{{ user.email }}
                  </span>
                </td>

                <td>
                  <span class="text-medium-md color-primary"> View </span>
                </td>
                <td>
                  <button
                    class="btn bkg-gray-light text-white"
                    @click="unAssignBuilding(buildingData.id)"
                  >
                    Unassign building
                  </button>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <!-- <div>
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
                    <span class="color-primary">{{ selectedBuilding }}</span>
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
                        {{ selectedBuilding }}
                      </p>
                      <hr class="my-12" />
                      <p
                        class="mb-0 text-sm-bold select-cursor option"
                        :class="{
                          selectedOption: item === selectedBuilding,
                        }"
                        v-for="(item, index) in buildings"
                        @click="selectBuilding(index)"
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
    <StaffModal ref="staffModal" />
  </div>
</template>

<script>
import StaffModal from "./staff-modal.vue";
import { ref } from "vue";
import { Bar } from "vue-chartjs";

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
  components: { Bar, StaffModal },

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
          chartArea: { top, bottom, left, right, width, height },
          scales: { x, y },
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
      selectedBuilding: "",
      buildings: [],
      user: "",
      staffUserBuildings: [],
      assigned_building_ids: "",
    };
  },
  methods: {
    unAssignBuilding(building_id) {
      const pluck = [
        {
          building_id: building_id,
          is_assigned_to_user: false,
        },
      ];
      this.showLoader();
      this.$http
        .post("/assignBuildings/" + this.$route.params.id, pluck)
        .then((response) => {
          Toast.fire({
            icon: "success",
            title: "Changes saved successfully.",
          });
          this.fetchAssignedBuildings();
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    assignNewBuildingPopup() {
      this.$refs.staffModal.assigned_building_ids = this.staffUserBuildings.map(
        (item) => item.id
      );
      this.showLoader();
      this.$http
        .get("/buildings?assign_building_flag=" + this.$route.params.id)
        .then((response) => {
          this.buildings = response.data.data;
          this.$refs.staffModal.buildings = this.buildings;
          $("#staffModal").modal("show");
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    selectBuilding(index) {
      this.buildingIndex = index;
      this.selectedBuilding = this.buildings[index];
    },

    gotoDetail() {
      this.$router.push("/staff-detail");
    },

    toggledropdown() {
      this.isShowDropdown = !this.isShowDropdown;
    },
    fetchUserDetails() {
      this.showLoader();
      this.$http
        .get("/fetchUserData/" + this.$route.params.id)
        .then((response) => {
          this.user = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchAssignedBuildings() {
      this.showLoader();
      this.$http
        .get("/getAssignedBuildingsOfAStaffUser/" + this.$route.params.id)
        .then((response) => {
          this.staffUserBuildings = response.data.buildings;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  created() {
    this.fetchUserDetails();
    this.fetchAssignedBuildings();
  },
};
</script>
