<template>
  <div class="laundry-section laundry-section-dashbord">
    <!-- <div class="d-flex justify-content-end mb-28 mt-6">
      <a href="" class="a-link me-30 color-secondary">Select</a>
      <a href="" class="a-link me-30 color-secondary">Select All</a>
      <a href="" class="a-link color-secondary">Delete All</a>
    </div> -->

    <div class="d-flex flex-row flex-wrap justify-content-between mb-70">
      <div>
        <button
            class="btn btn-primary mb-12"
            @click="addMoney()"
        >
          Add money
        </button>
        <a target="_blank" v-if="$gate.permissions.includes('carts')" href="/manage_carts">
          <button
              class="btn btn-primary mb-12"
          >
            Manage resident laundry accounts
          </button>
        </a>
        <!-- <button class="btn btn-dark mb-12" data-bs-target="#makeReservationModal" data-bs-toggle="modal">
          Make Reservation
        </button> -->
      </div>
      <div>
        <button class="btn bkg-success mb-12" @click="createLaundryProfileTemplate">
          + Create an appliance profile template
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between flex-row flex-wrap mb-18">
      <h3 class="h3 mb-0">Key Data and Performance</h3>
      <a class="a-link align-self-center" @click="toggleData()">{{
          isShow ? "Hide Data" : "Show Data"
        }}</a>
    </div>

    <div class="mb-70" v-if="isShow">

      <div class="table-responsive mb-28">

        <table>
          <thead>
          <tr>
            <th class="color-primary" style="font-size: 15px;">Current Washers Available :
              {{ selectedRoom ? selectedRoom.available_washers_count : 0 }}
            </th>
            <th class="color-primary" style="font-size: 15px;">Current Dryers Available :
              {{ selectedRoom ? selectedRoom.available_dryers_count : 0 }}
            </th>
            <th>
              <router-link to="#" style="font-size: 15px;">Real Time Image</router-link>
            </th>
          </tr>
          </thead>
          <!-- <tbody>
          <tr  class="border_bottom">
            <td class="text-medium color-primary">
              {{selectedRoom ? selectedRoom.available_washers_count : 0}}
            </td>
            <td class="text-medium color-secondary">
              {{ selectedRoom ? selectedRoom.available_dryers_count : 0 }}
            </td>
            <td class="text-medium">
              <router-link to="/">Click here to view</router-link>
            </td>
          </tr>
          </tbody> -->
        </table>
      </div>

      <!-- <div class="data-heading text-normal-bold-md mb-20">Appliance</div>

      <div class="d-flex flex-row flex-wrap mb-20">
        <div class="me-40 mb-12">
          <p class="text-medium-bold color-primary">Available Washers : {{selectedRoom ? selectedRoom.available_washers_count : 0}}</p>
          <div class="appience-camera">
            <img src="/images/test-Img.png" class="camera-img" alt="" />
          </div>
        </div>
        <div class="me-40 mb-12">
          <p class="text-medium-bold color-primary">Available Dryers :{{ selectedRoom ? selectedRoom.available_dryers_count : 0 }}</p>
          <div class="appience-camera">
            <img src="/images/test-Img.png" class="camera-img" alt="" />
          </div>
        </div>
      </div> -->

      <!-- <div class="row gy-4">
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card">
            <div class="d-flex justify-content-between flex-row flex-wrap">
              <div>
                <div>
                  <p class="mb-11 text-normal-bold-md color-secondary">
                    Order report
                  </p>
                </div>
                <div>
                  <h3 class="h3 mb-24">
                    Washer
                    <span class="color-primary">1254 cycles</span>
                  </h3>
                </div>
              </div>

              <div>
                <div>
                  <div>
                    <p class="mb-11 a-link">Print report</p>
                  </div>
                  <div>
                    <h3 class="h3 color-primary mb-24">$ 2,500</h3>
                  </div>
                </div>
              </div>
            </div>

            <Bar
              id="new2-chart-id"
              :ref="chartRef"
              :options="options"
              :data="chartData"
              :plugins="[backgroundBar]"
            />
          </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card">
            <div class="d-flex justify-content-between flex-row flex-wrap">
              <div>
                <div>
                  <p class="mb-11 text-normal-bold-md color-secondary">
                    Order report
                  </p>
                </div>
                <div>
                  <h3 class="h3 mb-24">
                    Dryer
                    <span class="color-primary">1254 cycles</span>
                  </h3>
                </div>
              </div>

              <div>
                <div>
                  <div>
                    <p class="mb-11 a-link">Print report</p>
                  </div>
                  <div>
                    <h3 class="h3 color-primary mb-24">$ 700</h3>
                  </div>
                </div>
              </div>
            </div>

            <Bar
              id="new2-chart-id"
              :ref="chartRef"
              :options="options"
              :data="chartData"
              :plugins="[backgroundBar]"
            />
          </div>
        </div>
      </div> -->
    </div>

    <div class="mb-70">
      <div class="d-flex flex-row flex-wrap mb-32">
        <h3 class="h3 me-40 mb-12 align-self-center">Laundry Rooms</h3>
        <!-- <button class="btn bkg-success mb-12">+ Create new laundry room</button> -->
      </div>

      <div class="row gy-3 mb-40">
        <div
            class="col-xl-3 col-lg-4 col-md-6"
            v-for="room in laundryRooms"
            @click="selectRoom(room)"
        >
          <div class="room-card" :class="{ activeroom: room.id == selectedRoom.id }">
            <h3 class="h3 mb-0">
              {{ room.name }}
              <span class="ms-2">
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
                      d="M11.8912 1.0481C12.5623 0.377012 13.4725 0 14.4216 0C16.3979 0 18 1.60212 18 3.57843C18 4.52748 17.623 5.43767 16.9519 6.10876L5.28033 17.7803C5.13968 17.921 4.94891 18 4.75 18H0.75C0.335786 18 0 17.6642 0 17.25V13.25C0 13.0511 0.0790176 12.8603 0.21967 12.7197L11.8912 1.0481ZM14.4216 1.5C13.8703 1.5 13.3417 1.71898 12.9519 2.10876L12.5607 2.5L15.5 5.43934L15.8912 5.0481C16.281 4.65832 16.5 4.12966 16.5 3.57843C16.5 2.43054 15.5695 1.5 14.4216 1.5ZM14.4393 6.5L11.5 3.56066L1.5 13.5607V16.5H4.43934L14.4393 6.5Z"
                      fill="black"
                      fill-opacity="0.2"
                  />
                </svg>
              </span>
            </h3>
          </div>
        </div>
      </div>

      <div class="mb-70" v-if="selectedRoom">
        <div
            class="d-flex justify-content-between mb-28 flex-column flex-lg-row"
        >
          <h3 class="align-self-lg-center h3 mb-12">
            Washers of {{ selectedRoom.name }}
          </h3>

          <!-- <div class="d-flex flex-row flex-wrap justify-content-lg-end">
            <button class="btn btn-dark me-20 mb-16 mb-lg-0" data-bs-toggle="modal" data-bs-target="#lDashboardModal">
              view history
            </button>
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Export .xls</a
            >
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Select</a
            >
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Select all</a
            >
            <a class="align-self-center a-link mb-16 mb-lg-0" href=""
              >Delete all</a
            >
          </div> -->
        </div>

        <div class="mb-28">
          <div>
            <div class="table-responsive">
              <table class="text-center">
                <tr>
                  <!-- <th></th> -->
                  <th>Appliance</th>
                  <!-- <th>Status</th> -->
                  <th>Reservation</th>
                  <th>Status</th>
                  <th>Reservable</th>
                  <th>Settings</th>
                </tr>

                <tr class="border_bottom" v-for="washer in selectedRoom.washer">
                  <!-- <td>
                    <div>
                      <i class="fa fa-info-circle custom-tooltip">
                        <span class="tooltip-text">Notification</span>
                      </i>
                    </div>
                  </td> -->
                  <td>
                    <i class="fa fa-info-circle custom-tooltip">
                      <span class="tooltip-text"># of Days : {{ washer.days_in_state }}</span>
                    </i>
                    <span class="text-medium-bold">{{ washer.name }}</span>
                  </td>
                  <!-- <td>
                    <div class="d-flex">
                      <p class="text-medium me-8 mt-20">ON</p>
                      <div class="form-check form-switch p-0 mt-20">
                        <label class="switch mb-0">
                          <input type="checkbox" checked role="switch" />
                          <span class="slider round"></span>
                        </label>
                      </div>
                    </div>
                  </td> -->
                  <td>
                    <div class="text-medium-bold color-primary select-cursor"
                         v-if="(washer.is_reservable == 1) && (washer.state == 0 || washer.state == 1)"
                         @click="seeReservations(washer)">
                      See reservations
                    </div>
                  </td>
                  <td class="text-center">
                    <div :class="washer.status_class" @click="machineAction(washer)">
                      {{ washer.status_label }}
                    </div>
                  </td>
                  <td>
                    <div :class="[{'cursor-not-allowed' : washer.state != 0 && washer.state != 1}]">
                      <input class="form-check-input me-12" type="checkbox" value="1"
                             :checked="(washer.is_reservable == 1) && (washer.state == 0 || washer.state == 1)"
                             :disabled="washer.state != 0 && washer.state != 1" @click="changeReservable(washer,$event)"
                             id="flexCheckDefault"/>
                    </div>
                  </td>

                  <td>
                    <button class="btn btn-dark" id="dropdownLaundry" data-bs-toggle="dropdown" aria-expanded="false">
                      Action
                      <span class="ms-1">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="8"
                            height="6"
                            viewBox="0 0 8 6"
                            fill="none"
                        >
                          <path
                              fill-rule="evenodd"
                              clip-rule="evenodd"
                              d="M4 6C3.68524 6 3.38885 5.85181 3.2 5.6L0.200005 1.6C-0.0272573 1.29698 -0.0638135 0.891571 0.105578 0.552786C0.27497 0.214003 0.621233 0 1 0H7C7.37877 0 7.72503 0.214003 7.89442 0.552786C8.06381 0.891571 8.02726 1.29698 7.79999 1.6L4.8 5.6C4.61115 5.85181 4.31476 6 4 6Z"
                              fill="white"
                          />
                        </svg>
                      </span>
                    </button>
                    <div class="dropdown">
                      <ul
                          class="dropdown-menu dropdown-menu-end dropdown-section px-2"
                          aria-labelledby="dropdownLaundry"
                      >
                        <li class="py-8 px-12 text-normal color-secondary font-weight-700">
                          Action
                        </li>
                        <li class="py-8 px-12 text-medium color-secondary font-weight-700 select-cursor active-action"
                            v-if="machineStates && machineStates.length > 0" @click="setAsOutOfOrder(washer)">
                          Set as Out of Order
                        </li>
                        <li class="py-8 px-12 text-medium color-secondary font-weight-700 select-cursor active-action"
                            v-if="machineStates && machineStates.length > 0"
                            @click="setNewState(washer.id, 1, 0)(washer)">
                          Set as Available
                        </li>
                        <li class="py-8 px-12 text-medium color-secondary font-weight-700 select-cursor"
                            @click="showHistory(washer.id)">
                          History
                        </li>
                        <li class="py-8 px-12 text-medium color-secondary font-weight-700 select-cursor"
                            @click="openCostPopup(washer)">
                          Cost
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-70" v-if="selectedRoom">
        <div
            class="d-flex justify-content-between mb-28 flex-column flex-lg-row"
        >
          <h3 class="align-self-lg-center h3 mb-12">
            Dryers of {{ selectedRoom.name }}
          </h3>

          <!-- <div class="d-flex flex-row flex-wrap justify-content-lg-end">
            <button
              class="btn btn-dark me-20 mb-16 mb-lg-0"
              data-bs-toggle="modal"
              data-bs-target="#lDashboardModal"
            >
              view history
            </button>
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Export .xls</a
            >
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Select</a
            >
            <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
              >Select all</a
            >
            <a class="align-self-center a-link mb-16 mb-lg-0" href=""
              >Delete all</a
            >
          </div> -->
        </div>
        <div class="mb-28">
          <div class="text-start">
            <div class="table-responsive">
              <table class="text-center">
                <tr>
                  <!-- <th></th> -->
                  <th>Appliance</th>
                  <th>Reservations</th>
                  <th>Status</th>
                  <th>Reservable</th>
                  <th>Settings</th>
                </tr>

                <tr class="border_bottom" v-for="dryer in selectedRoom.dryer">
                  <!-- <td>
                    <div>
                      <i class="fa fa-info-circle"></i>
                    </div>
                  </td> -->
                  <td>
                    <i class="fa fa-info-circle custom-tooltip">
                      <span class="tooltip-text"># of Days : {{ dryer.days_in_state }}</span>
                    </i>
                    <span class="text-medium-bold">{{ dryer.name }}</span>
                  </td>
                  <td>
                    <div class="text-medium-bold color-primary select-cursor"
                         v-if="(dryer.is_reservable == 1) && (dryer.state == 0 || dryer.state == 1)"
                         @click="seeReservations(dryer)">
                      See reservations
                    </div>
                  </td>
                  <td class="text-center">
                    <div :class="dryer.status_class" @click="machineAction(dryer)">{{
                        dryer.status_label
                      }}
                    </div>
                  </td>
                  <td>
                    <div :class="[{'cursor-not-allowed' : dryer.state != 0 && dryer.state != 1}]">
                      <input class="form-check-input me-12" type="checkbox" value="1"
                             :checked="(dryer.is_reservable == 1) && (dryer.state == 0 || dryer.state == 1)"
                             :disabled="dryer.state != 0 && dryer.state != 1" id="flexCheckDefault"
                             @click="changeReservable(dryer,$event)"/>
                    </div>
                  </td>

                  <td>
                    <button
                        class="btn btn-dark"
                        id="dropdownLaundry"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                      Action
                      <span class="ms-1">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="8"
                            height="6"
                            viewBox="0 0 8 6"
                            fill="none"
                        >
                          <path
                              fill-rule="evenodd"
                              clip-rule="evenodd"
                              d="M4 6C3.68524 6 3.38885 5.85181 3.2 5.6L0.200005 1.6C-0.0272573 1.29698 -0.0638135 0.891571 0.105578 0.552786C0.27497 0.214003 0.621233 0 1 0H7C7.37877 0 7.72503 0.214003 7.89442 0.552786C8.06381 0.891571 8.02726 1.29698 7.79999 1.6L4.8 5.6C4.61115 5.85181 4.31476 6 4 6Z"
                              fill="white"
                          />
                        </svg>
                      </span>
                    </button>
                    <div class="dropdown">
                      <ul
                          class="dropdown-menu dropdown-menu-end dropdown-section px-2"
                          aria-labelledby="dropdownLaundry"
                      >
                        <li class="py-8 px-12 text-normal color-secondary font-weight-700">
                          Action
                        </li>
                        <li class="p-2 text-medium color-secondary font-weight-700 select-cursor active-action"
                            v-if="machineStates && machineStates.length > 0" @click="setAsOutOfOrder(dryer)">
                          Set as Out of Order
                        </li>
                        <li class="p-2 text-medium color-secondary font-weight-700 select-cursor active-action"
                            v-if="machineStates && machineStates.length > 0" @click="setNewState(dryer.id, 1, 0)">
                          Set as Available
                        </li>
                        <!-- <template v-if="dryer.action_item_state == 1">
                          <template v-if="dryer.is_both == 0">
                            <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="setNewState(dryer.id, 1, 0)">
                              Set as {{ machineStates[1]['label'] }}: Fob Only
                            </li>
                          </template>
                          <template v-else>
                            <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="setNewState(dryer.id, 1, 1)">
                              Set as {{ machineStates[1]['label'] }}: Fob or Coin
                            </li>
                          </template>
                        </template>
                        <template v-else>
                          <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="setNewState(dryer.id, dryer.action_item_state, 1)"> 
                            Set as {{ machineStates[dryer.action_item_state] ? machineStates[dryer.action_item_state]['label'] : '' }}
                          </li>
                        </template> -->
                        <li class="p-2 text-medium color-secondary font-weight-700 select-cursor"
                            @click="showHistory(dryer.id)">
                          History
                        </li>
                        <li class="p-2 text-medium color-secondary font-weight-700 select-cursor"
                            @click="openCostPopup(dryer)">
                          Cost
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <history ref="laundryHistory"/>
    <outOfOrder ref="outOfOrder"/>
    <seeReservation ref="seeReservation"/>
    <addApplianceProfileTemplate ref="addProfileApplianceTemplate" :id="'dashboard'"/>
    <applianceProfileNameTemplatePopup ref="applianceProfileNameTemplatePopup" :id="'dashboard'"/>
    <costAppliancePopup ref="costAppliancePopup" :id="'costAppliance'"/>
    <!-- <costProfileTemplate /> -->
  </div>
</template>

<script>
import {ref} from "vue";
import {Bar} from "vue-chartjs";
import history from "./history.vue";
import outOfOrder from "./outOfOrder.vue";
import seeReservation from "./seeReservationModal.vue"
import addApplianceProfileTemplate from "./addApplianceProfileTemplate.vue";
import costProfileTemplate from "./costProfileTemplatePopup.vue";
import applianceProfileNameTemplatePopup from "./applianceProfileNameTemplatePopup.vue";
import costAppliancePopup from "./costAppliancePopup.vue"

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

export default {
  components: {
    Bar,
    history,
    outOfOrder,
    seeReservation,
    addApplianceProfileTemplate,
    costProfileTemplate,
    applianceProfileNameTemplatePopup,
    costAppliancePopup
  },

  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
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
      isShow: true,
      selectedRoom: 1,
      chartRef,
      backgroundBar,
      chartData: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Aug"],
        datasets: [
          {
            data: [41, 20, 12, 62, 71, 86, 76],
            label: "none",
            barPercentage: 0.8,
            barThickness: 37,
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


      //

      laundryRooms: [],
      machineStates: []
    };
  },
  created() {
    this.loadData();
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
  methods: {
    machineAction(machine) {
      this.showLoader()
      if (machine.status_label === "Available: FOB Only") {
        let params = {machineId: machine.id, buildingId: this.building_id};
        this.$http
            .post("/startAppliance", params)
            .then((response) => {
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      }
    },
    openCostPopup(item) {
      this.$refs.costAppliancePopup.loadData(this.selectedRoom, item);
      return;
      this.showLoader();
      this.$http.get("/laundry_washer_dryer_new_cost/" + this.selectedRoom.id + "/" + item.id)
          .then((response) => {
            let res = response.data;
            let ref = this.$refs.costAppliancePopup;
            ref.form.reset();

            ref.weeklyPlannerHtml = res.weeklyPlannerHtml;

            $('#costAppliancePopupcostAppliance').modal('show');
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
    },
    createLaundryProfileTemplate() {
      this.$refs.applianceProfileNameTemplatePopup.form.reset();
      $('#addApplianceProfileNameTemplatePopupdashboard').modal('show');
    },
    toggleData() {
      this.isShow = !this.isShow;
    },

    selectRoom(room) {
      this.selectedRoom = room;
    },
    showHistory(id) {
      this.$refs.laundryHistory.loadData(id);
    },
    seeReservations(machine) {
      this.$refs.seeReservation.loadData(machine.id)
      this.$refs.seeReservation.machine = machine;
      $('#seeReservation').modal('show');
      // return;

    },
    changeReservable(machine, event) {
      let value = 0;
      if (event.target.checked) value = 1;
      this.showLoader();
      this.$http.get("/laundry_change_reservable/" + machine.id + '/' + value)
          .then((response) => {
            Toast.fire({icon: "success", title: "Changes saved successfully."});
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
    },
    setAsOutOfOrder(machine) {
      this.$refs.outOfOrder.title = machine.name;
      this.$refs.outOfOrder.machine = machine;
      $('#outOfOrderLaundry').modal('show');
    },
    setNewState(appliance_id, state, is_both) {
      this.showLoader();
      this.$http.get("/laundries/setNewState/" + appliance_id + '/' + state + '/' + is_both)
          .then((response) => {
            this.loadData();
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
    },
    loadData() {
      if (this.building_id == 0) return;
      this.showLoader();
      this.$http.get("/loadLaundryData/" + this.building_id)
          .then((response) => {
            this.laundryRooms = response.data.laundries;
            this.machineStates = response.data.machine_states;
            if (this.laundryRooms.length > 0) this.selectRoom(this.laundryRooms[0])
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
    },
    addMoney() {
      if (this.building_id == 0) Toast.fire({
        icon: 'warning',
        title: 'No building selected. Please select building first and try again.'
      });
      this.showLoader();
      let params = {buildingId: this.building_id};
      this.$http.post("/addMoneyLaundry", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              var win = window.open(response.message, '_blank');
              if (win) {
                win.focus();
              } else {
                alert('Please allow popups for this website');
              }
            } else {
              Toast.fire({
                icon: 'warning',
                title: response.message
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
    }
  },
};
</script>
<style scoped>
.tooltip-text {
  left: 200%;
  width: 120px;
}
</style>
