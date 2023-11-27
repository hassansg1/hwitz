<template>
  <div>
    <div id="all-cameras" v-if="this.moduleAvailable && !this.buildingShutoff">
      <div class="d-flex justify-content-between mb-28 flex-column flex-lg-row">
        <h3 class="h3 mb-12 mb-md-0" v-if="loadedAsAComponentInDashboard">Cameras ({{ dataArray.length }})</h3>
        <div
            class="align-self-lg-center text-normal color-secondary mb-16 mb-lg-0"
        >
          View live feeds of your cameras as well as past recordings
        </div>
      </div>

      <div class="row gy-3">
        <div class="col-xl-3 col-lg-4 col-md-6" v-for="camera in this.dataArray">
          <div class="card">
            <div class="position-relative">
              <div class="top-image">
                <img :src="'/cameras/camview/'+camera.id" class="header-img" alt=""/>
              </div>
              <div class="active-camera">
                <div class="d-flex">
                  <div class="green-dot me-8 align-self-center"></div>
                  <div class="text-normal text-white align-self-center">
                    Active
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h3 style="font-size: 14px" class="h3">{{ camera.name }}</h3>
                </div>
              </div>
              <div style="text-align: center">
                <span class="green" :class="{red: camera.snapshot_age > 3600}">
                  {{ formatDateTime(camera.snapshot_last) }} ({{ camera.snapshot_age }})
                </span>
              </div>
              <div style="text-align: center" v-if="camera.video_last && camera.video_last > '1900-01-01 00:00:00'">
                <span class="green" :class="{red: camera.video_age > 86400}">
                  Last Video: {{ formatDateTime(camera.video_last) }} ({{ camera.video_age }})
                </span>
              </div>
              <br v-else>
              <div class="d-flex justify-content-between mt-20">
                <a target="_blank" :href="camera.video_recordings+'/'" style="margin-right: 10px">
                  <button class="btn btn-dark">Recordings</button>
                </a>
                <router-link :to="'camera/'+camera.id">
                  <button class="btn btn-dark">View</button>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-center" v-if="loadedAsAComponentInDashboard">
        <router-link class="a-link mt-64" :to="'/cameras'">View All</router-link>
      </div>
    </div>
    <div v-else-if="this.buildingShutoff">
      <Building_shutoff></Building_shutoff>
    </div>
    <div v-else>
      <Not_available></Not_available>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import Not_available from "../../components/error/not_available.vue";
import Building_shutoff from "../../components/error/building_shutoff.vue";

export default {
  components: {Not_available, Building_shutoff},
  created() {
    this.loadData();
  },
  props: ["loadedAsAComponentInDashboard"],
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
  data() {
    return {
      moduleAvailable: true,
      buildingShutoff: false,
      dataArray: {},
    };
  },
  methods: {
    loadData() {
      let selectedBuilding = this.$gate.user.selectedBuilding;
      this.moduleAvailable = selectedBuilding.has_cameras;
      this.buildingShutoff = selectedBuilding.building_shutoff;
      let buildingId = this.building_id;
      if (buildingId && this.moduleAvailable && !this.buildingShutoff) {
        if (!this.loadedAsAComponentInDashboard) this.showLoader();
        this.$http
            .get("/cameras/" + buildingId)
            .then((response) => {
              response = response.data;
              if (response.status)
                this.dataArray = response.cameras;

              if (this.loadedAsAComponentInDashboard) {
                this.dataArray = this.dataArray.splice(0, 3);
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