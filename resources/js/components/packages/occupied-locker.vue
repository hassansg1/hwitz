<template>
  <div class="packages-section">
    <div class="row gy-3 mb-28">
      <div class="col-lg-3">
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="searchKeyword"
            @change="loadData()"
        />
      </div>
    </div>
    <entries-per-page></entries-per-page>
    <div class="row gy-4">
      <div class="col-xl-4 col-lg-6 col-md-6" v-for="data in dataArray.data">
        <div class="packages-card">
          <div class="d-flex justify-content-between">
            <div class="align-self-center">
              <h3 class="h3 mb-0 text-capitalize">{{ data.label }}</h3>
              <p class="text-medium-bold color-primary mb-0" v-if="data.package && data.package.receiver">
                {{ data.package.receiver.name }}</p>
              <p class="color-primary text-normal mb-0" v-if="data.locker_screen">{{ data.locker_screen.name }}</p>
            </div>

            <div v-if="data.package && data.package.receiver" class="align-self-center">
              <div class="position-relative text-center">
                <div class="avatar-sm">
                  <img :src="data.package.receiver.profile_picture" class="avatar-img" alt=""/>
                </div>
              </div>
            </div>
          </div>
          <hr class="mt-9 mb-16"/>
          <div v-if="data.package && data.package.receiver" class="row gy-3">
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="openLocker(data.package.id,1)">
                OPEN (maintenance)
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="openLocker(data.package.id,0)">
                OPEN (mark vacant)
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="openLocker(data.package.id,1)">
                OPEN (keep occupied)
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="lockerPackageExtendedForOneDay(data.package.id)">
                Extend 1-day: no limit
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="viewExtensionHistory(data.package.id)">
                View Extension History
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-dark w-100" @click="alertResident(data)">
                Alert Resident
              </button>
            </div>
          </div>
        </div>
      </div>
      <span>
      </span>
    </div>
    <pagination :pagination="dataArray"></pagination>
  </div>
</template>

<script>

import ExtensionHistoryModal from "./extension-history-modal.vue";
import AlertResident from "./alert-resident.vue";

export default {
  components: {
    ExtensionHistoryModal,
    AlertResident,
  },
  created() {
    if (this.building_id) {
      this.loadData();
    }
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      if (this.$parent.activeTab === 'occupiedLocker')
        this.loadData();
    }
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      searchKeyword: '',
    };
  },
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.buildingId = this.building_id;
      if (this.building_id === undefined) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "No building selected.",
        });
        return;
      }

      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.searchKeyword = this.searchKeyword;
      params.occupied = 1;

      this.$http
          .post("/lockers", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.dataArray = response.data;
            } else {
              Toast.fire({
                icon: "error",
                position: "top",
                title: response.message,
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    openLocker(packageId, keepOccupied) {
      this.showLoader();
      let params = {packageId: packageId, keepOccupied: keepOccupied, buildingId: this.building_id};
      this.$http
          .post("/openLocker", params)
          .then((response) => {
            Toast.fire({
              icon: response.data.icon,
              title: response.data.message,
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    lockerPackageExtendedForOneDay(packageId) {
      this.showLoader();
      let params = {packageId: packageId, hours: 24, manager: 1, buildingId: this.building_id};
      this.$http
          .post("/lockerPackageExtension", params)
          .then((response) => {
            Toast.fire({
              icon: response.data.icon,
              title: response.data.message,
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    viewExtensionHistory(packageId) {
      this.$parent.$refs.extensionHistory.viewExtensionHistory(packageId);
    },
    alertResident(locker) {
      this.$parent.$refs.alertResident.alertResident(locker);
    }
  }
};
</script>
