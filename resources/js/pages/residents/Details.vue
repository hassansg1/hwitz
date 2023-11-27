<template>
  <div class="residents-section" v-if="showResidentDetailSection">
    <div class="d-flex flex-row flex-wrap mb-28" style="justify-content: space-between;">
      <div>
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="search"
            @change="loadData()"/>
      </div>
      <div>
        <button class="btn bkg-success me-20 mb-12 mb-md-0" @click="openAddResidentPopup">
          + Add new resident
        </button>
        <button class="btn bkg-primary me-20 mb-12 mb-md-0" @click="loadListing">
          <i class="fa fa-arrow-left"></i>
          Back
        </button>
      </div>
    </div>
    <div>
      <div class="row gy-3 mb-24">
        <div class="col-lg-4 col-md-6">
          <div class="border mb-28">
            <div class="d-flex header border-bottom">
              <p class="text-normal-bold-md color-secondary align-self-center ps-100 mb-0">
                Name
              </p>
            </div>
            <div class="d-flex border-bottom py-7 select-cursor" v-for="(unit_users,index) in data"
                 :class="{ userActive: userIndex === index }" @click="showDetail(index,unit_users)"
                 v-if="unit_users.user">
              <div class="align-self-center px-24">
                <!-- <input class="form-check-input me-12" type="checkbox" value="" id="flexCheckDefault"/> -->
              </div>
              <div>
                <div class="d-flex">
                  <div class="align-self-center avatar-sm me-8">
                    <img src="/images/worker.png" class="avatar-img" alt="worker"/>
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">
                      {{ unit_users.user.firstname + ' ' + unit_users.user.lastname }}</p>
                    <p class="text-normal mb-0 color-primary">{{ unit_users.mobile }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-center">
            <pagination :pagination="pagination"></pagination>
          </div>
        </div>

        <div class="col-lg-8 col-md-6">
          <div class="mb-60">
            <div class="row gap-3">
              <div class="col-lg-12">
                <div class="d-flex flex-column flex-lg-row">
                  <div class="me-40 mx-auto mx-lg-0 mb-12 mb-lg-0">
                    <div class="d-inline mx-auto mx-lg-0">
                      <div class="position-relative text-center me-45">
                        <div class="profile-picture">
                          <img :src="user.profile_picture ? user.profile_picture : '/images/default-user.jpg'"
                               class="profile-img"/>
                        </div>
                        <span class="profile-verifiyIcon">
								<svg class=""
                     xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
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

                  <div>
                    <div class="row">
                      <div class="col-12">
                        <div class="d-flex">
                          <div class="me-12 mb-28">
                            <div class="d-flex">
                              <h3 class="h3 mb-0 me-8">{{ user.name }}</h3>
                              <div>
                                <img src="/images/icons/edit.png" class="select-cursor" @click="editResidentPopup(user)"
                                     alt=""/>
                              </div>
                            </div>
                            <div class="color-primary text-normal">
                              <resident-mobile :user="user"></resident-mobile>
                            </div>
                          </div>
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
                          <p v-if="!user.email_verified" class="text-normal color-primary mb-0">
                            <span class="red">Unverified</span>
                          </p>
                        </div>
                        <div class="mb-12">
                          <p class="mb-0 text-normal-bold-md color-secondary">
                            Date of Birth
                          </p>

                          <p class="text-normal">{{ user.dob ? user.dob : '-' }}</p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-12">
                          <p class="mb-0 text-normal-bold-md color-secondary">
                            Intercom assigned
                          </p>
                          <intercom-assigned :unit="user.parentUnit" :user="user"></intercom-assigned>
                        </div>
                        <div class="mb-12">
                          <p class="mb-0 text-normal-bold-md color-secondary">
                            Status
                          </p>

                          <p class="text-normal">
                            {{ userStatus }}
                            <!-- |<span class="color-primary select-cursor" @click="switchPosition(user.id)">Switch position</span> -->
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                      id="profile-btn"
                      class="ms-lg-auto d-flex flex-column gap-2 align-items-center"
                  >
                    <!-- <button class="btn btn-dark mb-18">Send Onboarding</button> -->
                    <router-link :to="'/messages'" class="btn bkg-primary text-white mb-18">
                      Message
                    </router-link>
                    <button
                        class="btn bkg-danger text-white mb-18"
                        @click="deactivateResidentFob(user.tokens)"
                    >
                      Deactivate
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <residentFobs :tokens="user.tokens" :user="user" :building_id="building_id"
                        :unit_id="activeUnitUsers.unit_id"/>

          <connectedDevices :macs="user.macs" :user="user" :building_id="building_id"
                            :unit_id="activeUnitUsers.unit_id"/>

          <residentInternet :units="user.units" :user="user"/>

          <div class="row">
            <residentStorage :storages="user.storageParkingData" :user="user"/>
            <residentParking :parkings="user.storageParkingData" :user="user"/>
          </div>
        </div>
      </div>
    </div>

    <residentModal ref="residentModal"/>
    <Modal :id="0" :body="popupHtml" :title="'User Entrances'"></Modal>
    <addResidentModal ref="addNewResidentModal" :id="'detail'"></addResidentModal>
  </div>
</template>

<script>
import residentModal from "./resident-modal.vue";
import addResidentModal from "./addResidentModal.vue";
import residentFobs from './residentFobs.vue';
import connectedDevices from "./connectedDevices.vue";
import residentInternet from "./residentInternet.vue";
import residentStorage from "./residentStorage.vue";
import residentParking from "./residentParking.vue";
import IntercomAssigned from "../../shared/resident/intercomAssigned.vue";
import ResidentMobile from "../../shared/resident/residentMobile.vue";

export default {
  components: {
    residentModal,
    addResidentModal,
    residentFobs,
    connectedDevices,
    residentInternet,
    residentStorage,
    IntercomAssigned,
    ResidentMobile,
    residentParking
  },
  // props :["pagination","data"],
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadData();
    }
  },
  data() {
    return {
      userIndex: 1,
      showResidentDetailSection: false,
      activeUserId: '',
      data: '',
      user: '',
      activeUnitId: '',
      popupHtml: '',

      //pagination start
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      search: '',
      params: {},

      //pagination end

      activeIndex: '',
      activeUnitUsers: ''
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
    userStatus() {

      let status = 'Resident';
      if (this.user) {
        this.user.units.forEach(item => {
          if (item.id == item.id) {
            if (item.guarantor_user_id == this.user.id) {
              status = 'Guarantor';
            }
            if (item.co_guarantor_user_id == this.user.id) {
              status = 'Co guarantor';
            }
          }
        });
      }

      return status;
    }
  },
  methods: {
    switchPosition(user_id) {
      this.showLoader();
      setTimeout(() => {
        this.removeLoader();
      }, 1000);
    },
    verifyMobileOtp(user_id) {
      this.showLoader();
      this.$http
          .get("/verifyMobileByOtp/" + user_id)
          .then((response) => {
            console.log(response);
            let status = response.data.status;
            let message = response.data.message;
            if (status == 'success') message = 'Token (' + response.data.token + ') sent successfully. Expires at ' + this.formatDateTime(response.data.expire_at)

            Toast.fire({
              icon: status,
              title: message,
              timer: 13000
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    openAddResidentPopup() {
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      $('#addNewResidentModalresident').modal('show');
    },

    editResidentPopup(user) {
      this.$refs.addNewResidentModal.title = 'Edit resident';
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      this.$refs.addNewResidentModal.form.user_id = user.id;
      this.$refs.addNewResidentModal.form.unit_id = this.activeUnitUsers.unit;
      this.$refs.addNewResidentModal.loadDetails(user, false);
      $('#addNewResidentModaldetail').modal('show');
    },
    getStorageAndParkingUnits() {

      let user_id = this.activeUnitUsers.user ? this.activeUnitUsers.user.id : 0;
      this.$http
          .get("/getStorageAndParkingUnits/" + user_id + "/" + this.building_id + "/Parking")
          .then((response) => {
            this.user = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },

    reloadData() {
      this.showDetail(this.activeIndex, this.activeUnitUsers);
    },
    showDetail(index, unit_users) {
      this.activeIndex = index;
      this.activeUnitUsers = unit_users;

      let user_id = unit_users.user ? unit_users.user.id : 0;
      this.activeUnitId = unit_users.unit_id;
      this.showLoader();
      this.userIndex = index;
      this.$http
          .get("/resident/" + user_id)
          .then((response) => {
            this.user = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadData(pageNumber = null) {
      if (pageNumber == null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = this.building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.search = this.search;
      this.params.sortBy = this.sortColumn;

      this.showLoader();
      this.$http
          .post("/residents", this.params)
          .then((response) => {
            this.pagination = response.data;
            this.data = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadListing() {
      this.showResidentDetailSection = false;
      // this.$refs.residentDetailSection.showDetail(id,unit_users);
      this.$parent.showResidentsListingSection = true;
      // this.residentDetailSection.data = this.data;
      // this.$refs.residentDetailSection.pagination = this.pagination;
    },
    deactivateResidentFob(tokens) {
      this.$refs.residentModal.tokens = tokens;
      $('#residentsModal').modal('show');
    }
  },
};
</script>
<style scoped>
.pagination_block {
  display: block !important;
}
</style>
