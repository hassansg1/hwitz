<template>
  <div id="camera-detail">
    <div class="row gy-3" v-if="selectedCamera">
      <div class="col-lg-5" style="height: 500px;">
        <div style="height: 100%" class="main-section-img position-relative">
          <iframe :src="'https://rtsp2webrtc.myurbansky.us/?address='+selectedCamera.rtsp_url " class="main-img"/>
          <div class="left-overlay">
            <div>
              <div class="d-flex">
                <div class="align-self-center green-dot me-8"></div>
                <div class="text-medium text-white">Live</div>
              </div>
              <div class="h3 text-white d-none d-lg-block">
                {{ selectedCamera.name }}
              </div>
            </div>
          </div>
          <!--          <div class="right-overlay text-medium">Report incident</div>-->
        </div>
      </div>
      <div class="col-lg-7">

        <div class="mb-64" v-if="selectedBadge === 1">
          <div class="table-responsive mb-28">
            <table>
              <tr>
                <th>
                  Timestamp
                  <Sorting :sortColumn="'timestamp'"/>
                </th>
                <th>
                  Uptime
                  <Sorting :sortColumn="'uptime'"/>
                </th>
                <th>
                  Status
                  <Sorting :sortColumn="'status'"/>
                </th>
              </tr>
              <tr v-for="data in dataArray.data" class="border_bottom">
                <td>
                  <span class="text-medium">{{
                      formatDateTime(data.timestamp)
                    }}</span>
                </td>
                <td>
                  <span class="text-medium">{{ data.up_time_formatted }}</span>
                </td>
                <td>
                  <span
                      class="text-medium"
                      v-if="data.status"
                      style="color: green; font-weight: bold"
                  >Online</span
                  >
                  <span
                      class="text-medium"
                      v-else
                      style="color: red; font-weight: bold"
                  >Offline</span
                  >
                </td>
              </tr>
            </table>
          </div>
          <span>
            <pagination :pagination="dataArray"></pagination>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import router from "../../router";

export default {
  created() {
    this.cameraId = this.$route.params.id;
    this.getCameraDetails();
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      router.push('/cameras');
    }
  },
  data() {
    return {
      cameraId: 0,
      selectedCamera: null,
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      device: 3,
      selectedBadge: 1,
    };
  },

  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.buildingId = this.building_id;
      params.date_filter_radio = "last_year";
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.device = this.device;
      params.deviceId = this.selectedCamera.id;

      this.$http
          .post("/deviceAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    getCameraDetails() {
      let cameraId = this.cameraId;
      if (cameraId) {
        this.showLoader();
        this.$http
            .get("/cameraDetails/" + cameraId)
            .then((response) => {
              response = response.data;
              if (response.status) {
                this.selectedCamera = response.camera;
                this.loadData();
              }
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      }
    },
  },
};
</script>
