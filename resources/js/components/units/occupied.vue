<template>
  <div class="units-section">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-32">
      <span class="align-self-center mb-8">
        <img title="Unverified email or mobile"
             :src="'/images/warning.png'"
        />
        <span class="text-normal color-secondary me-8">Unverified users: </span>
        <a class="a-link me-20" @click="loadUnverifiedUsersModal" href="javascript:void(0)">{{ this.unverifiedUsers }}/ {{
            this.totalUsers
          }}</a>
      </span>
    </div>
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
    <div class="row gy-3">
      <div class="col-xl-4 col-lg-6 col-md-6" v-for="data in dataArray.data">
        <div class="card">
          <div style="min-height: 130px" class="d-flex justify-content-between">
            <div>
              <div class="d-flex cpointer" @click="openUnitTab(data)">
                <h3 class="h3 mb-2 me-8">{{ data.unit_no }}</h3>
                <div>
                  <img src="/images/icons/edit.png" alt=""/>
                </div>
              </div>
              <div>
                <p v-for="item in data.users" class="text-normal color-primary mb-0">
                  {{ item.name }}
                  <img title="Unverified email or mobile"
                       v-if="item.email_verified !== 1 || item.mobile_veridication === 'No'"
                       :src="'/images/warning.png'"
                  />
                </p>
              </div>
            </div>

            <div>
              <div :title="item.name" class="avatar-sm" v-for="item in data.users">
                <img
                    v-if="item.profile_picture"
                    :src="item.profile_picture"
                    class="avatar-img"
                />
                <img
                    v-else
                    :src="'/images/avatar.png'"
                    class="avatar-img"
                />
              </div>
            </div>
          </div>
          <unitDropdown :data="data"></unitDropdown>

          <div class="row gy-3 mb-20">
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_fob'" :name="data.id+'_fob'"
                           @change="updateUnitColumn('fob',data.id,'UNIT shut off')" :checked="data.fob"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  UNIT shut off
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_notify_at_entry'" :name="data.id+'_notify_at_entry'"
                           @change="updateUnitColumn('notify_at_entry',data.id,'Notify of Entry')"
                           :checked="data.notify_at_entry"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  Notify of Entry
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_is_internet'" :name="data.id+'_is_internet'"
                           @change="updateUnitColumn('is_internet',data.id,'Internet Service')"
                           :checked="data.is_internet"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  Internet Service
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_telephone'" :name="data.id+'_telephone'"
                           @change="updateUnitColumn('telephone',data.id,'Telephone Service')"
                           :checked="data.telephone"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  Telephone Service
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_tv'" :name="data.id+'_tv'"
                           @change="updateUnitColumn('tv',data.id,'Television Service')"
                           :checked="data.tv"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  Television Service
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
              <div class="d-flex">
                <div class="form-check form-switch p-0 me-10 align-self-center">
                  <label class="switch mb-0">
                    <input type="checkbox" :id="data.id+'_cable'" :name="data.id+'_cable'"
                           @change="updateUnitColumn('cable',data.id,'Cable Service')"
                           :checked="data.cable"/>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div
                    class="align-self-center text-normal-bold-md color-secondary"
                >
                  Cable Service
                </div>
              </div>
            </div>
          </div>


          <hr class="mb-20 mt-0"/>

          <div class="row gy-3 mb-20">
            <div class="col-lg-4 col-md-6">
              <div class="d-flex">
                <div class="icon-sm me-8">
                  <img src="/images/icons/bed.png" class="icon-img" alt=""/>
                </div>
                <div class="mt-6">
                  <p class="text-medium color-secondary mb-0" v-if="data.unit_type">{{ data.unit_type.name }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-2">
            <div class="col-lg-6">
              <button class="btn btn-dark w-100"
                      @click="relocateResident(data)"
              >Relocate
              </button>
            </div>
            <div class="col-lg-6">
              <button class="btn btn-dark w-100">Turn off access</button>
            </div>
            <div class="col-lg-6">
              <button
                  :disabled="!(data.start_onboarding_process === 1
                  && data.guarantor
                  && data.guarantor.mobile_verification === 'Yes'
                  && data.guarantor.email_verified === 1
                  && data.guarantor.is_picture_approved === 'Yes'
                  )"
                  @click="sendOnboarding(data)"
                  class="btn btn-dark w-100">Send onboarding
              </button>
            </div>
          </div>
        </div>
      </div>

      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>

    <unitOccupiedModal/>
    <unVerifiedUsersModal ref="unVerifiedUsersModal"/>
    <relocateResidentModal ref="relocateResidentModal"/>
    <sendOnboarding ref="sendOnboarding"/>
    <leaseHistoryModal ref="leaseHistoryModal"/>
    <packageHistoryModal ref="packageHistoryModal"/>
  </div>
</template>

<script>
import unitOccupiedModal from "./occupied-modal.vue";
import unVerifiedUsersModal from "./unverfied-users-modal.vue";
import relocateResidentModal from "./relocate-resident-modal.vue";
import sendOnboarding from "./onboarding.vue";
import leaseHistoryModal from "./lease-history-modal.vue";
import packageHistoryModal from "./package-history-modal.vue";
import unitDropdown from "./unitDropdown.vue";
import {config} from "../../config";

export default {
  components: {
    unitOccupiedModal,
    unVerifiedUsersModal,
    relocateResidentModal,
    sendOnboarding,
    leaseHistoryModal,
    packageHistoryModal,
    unitDropdown
  },
  data() {
    return {
      dataArray: {},
      unverifiedUsersList: {},
      unverifiedUsers: 0,
      totalUsers: 0,
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      searchKeyword: '',
    };
  },
  created() {
    if (this.building_id) {
      this.loadData();
      this.loadUnverifiedUsers();
    }
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadData();
      this.loadUnverifiedUsers();
    }
  },
  methods: {
    updateUnitColumn(column, id, label) {
      let params = {column: column, id: id, value: $('#' + id + "_" + column).is(":checked")};
      this.$http
          .post("/updateUnitColumn", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              Toast.fire({
                icon: "success",
                position: "top",
                title: label + " status changed successfully."
              });
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

      params.occupied = 1;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.searchKeyword = this.searchKeyword;

      this.$http
          .post("/units", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.dataArray = response.data;
              window.scrollTo({top: 0, behavior: 'smooth'});
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
    loadUnverifiedUsers() {
      let params = {};
      params.buildingId = this.building_id;
      this.$http
          .post("/loadUnverifiedUsers", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.unverifiedUsers = response.unverifiedUsers;
              this.totalUsers = response.totalUsers;
              this.unverifiedUsersList = response.unverifiedUsersList;
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
    loadUnitActivityData(unitId) {
      this.$refs.activityModal.loadUnitActivityData(unitId);
      $("#activityModal").modal("show");
    },
    loadUnverifiedUsersModal() {
      this.$refs.unVerifiedUsersModal.users = this.unverifiedUsersList;
      $("#unverified-users-modal").modal("show");
    },
    relocateResident(unit) {
      this.$refs.relocateResidentModal.relocateResident(unit);
    },
    sendOnboarding(unit) {
      this.$refs.sendOnboarding.sendOnboarding(unit);
    },
    leaseHistory(unit) {
      $("#lease-history-modal").modal("show");
      this.$refs.leaseHistoryModal.loadData(null, unit);
    },
    packageHistory(unit) {
      this.$refs.packageHistoryModal.unit = unit;
      $("#package-history-modal").modal("show");
      this.$refs.packageHistoryModal.getDetails(unit);
    },
    unitHistory(unit) {
      window.open("/unit_history/" + unit.id, '_blank')
    },
    vacateUnit(unit) {
      let usersCount = unit.users.length;
      if (usersCount > 0) {
        Swal.fire({
          title: config.confirmBoxTitle,
          text: 'By vacating the unit, you are agreeing to zero out the balances of all carts associated with this unit.  Balances of the vacated guarantor will remain their responsibility and pending charges will be available for payment at anytime.  NOTE: you may "write-off" balances in the "manual transactions tab" under payments? Are you sure you want to vacate unit?',
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: config.confirmButtonColor,
          cancelButtonColor: config.cancelButtonColor,
          confirmButtonText: config.confirmButtonText,
        }).then((result) => {
          if (result.value) {
            let params = {
              unitId: unit.id,
            };
            this.showLoader();
            this.$http
                .post("/vacateUnit", params)
                .then((response) => {
                  response = response.data;
                  if (response.status) {
                    Toast.fire({
                      icon: "success",
                      position: "top",
                      title: "The unit has been successfully vacated.",
                    });
                    this.loadData();
                  } else {
                    Toast.fire({
                      icon: "error",
                      position: "top",
                      title: response.message ?? "Something went wrong",
                    });
                  }
                  this.removeLoader();
                })
                .catch((error) => {
                  this.removeLoader();
                  console.error(error);
                });
          }
        });
      }
    },
    disableServices(unit) {
      Swal.fire({
        title: config.confirmBoxTitle,
        text: 'Are you sure you want to disable all services?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          let params = {
            unitId: unit.id,
          };
          this.showLoader();
          this.$http
              .post("/disableServices", params)
              .then((response) => {
                response = response.data;
                if (response.status) {
                  Toast.fire({
                    icon: "success",
                    position: "top",
                    title: "All services have been successfully disabled.",
                  });
                  this.loadData();
                } else {
                  Toast.fire({
                    icon: "error",
                    position: "top",
                    title: response.message ?? "Something went wrong",
                  });
                }
                this.removeLoader();
              })
              .catch((error) => {
                this.removeLoader();
                console.error(error);
              });
        }
      });
    },
    openUnitTab(data) {
      this.$router.push("unit-detail/" + data.id);
    }
  },
};
</script>

<style scoped>
@media screen and (max-width: 767px) {
  .card {
    padding: 16px;
  }
}
</style>
