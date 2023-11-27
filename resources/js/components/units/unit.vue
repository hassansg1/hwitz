<template>
  <div class="units-section">
    <div v-if="this.unit">
      <div>
        <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-32">
          <div class="align-self-center">
            <button
                class="btn btn-top bkg-success me-20 mb-8"
                @click="openAddResidentPopup"
            >
              + Add user
            </button>
          </div>
          <span class="align-self-center mb-8">
        <a class="a-link me-20" href="javascript:void(0)"
           @click="redirectToUnit(previousUnit)"
           v-if="previousUnit">Previous Unit</a>
      </span>

          <span class="align-self-center mb-8">
        <a class="a-link" href="javascript:void(0)"
           @click="redirectToUnit(nextUnit)"
           v-if="nextUnit">Next Unit</a>
      </span>
        </div>

        <div class="mb-64">
          <div class="d-flex">
            <div class="align-self-center me-16 icon-sm">
              <img @click="redirectToUnitsTab" src="/images/icons/house.png" class="icon-img cpointer" alt=""/>
            </div>
            <div class="unit-select align-self-center mt-16">
              <h3 class="h3">Unit - {{ this.unit.unit_no }} </h3>
              <!--            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"-->
              <!--                v-model="value"-->
              <!--                tag-placeholder="Add this as new tag"-->
              <!--                placeholder="Search or add a tag"-->
              <!--                label="name"-->
              <!--                track-by="code"-->
              <!--                :options="options"-->
              <!--                :multiple="false"-->
              <!--            ></multiselect>-->
            </div>

            <unitDropdown :data="unit"/>
            <!-- <div class="align-self-center">
              <i
                  class="fa fa-ellipsis-h color-secondary"
                  id="dropdownVacate"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              ></i>

              <div class="dropdown">
                <ul
                    class="dropdown-menu dropdown-menu-end border-standered"
                    aria-labelledby="dropdownVacate"
                >
                  <li class="px-2 py-1 border-bottom text-sm-bold">option 1</li>
                  <li class="px-2 py-1 border-bottom text-sm-bold">option 2</li>
                  <li class="px-2 py-1 border-bottom text-sm-bold">option 3</li>
                  <li class="px-2 py-1 border-bottom text-sm-bold">option 4</li>
                </ul>
              </div>
            </div> -->
          </div>
        </div>

        <div class="mb-64">
          <h3 class="h3 mb-28">Carts</h3>
          <div class="row gy-3">
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="card">
                <p class="text-normal color-secondary mb-8">Laundry Balance</p>
                <h3 v-if="this.unit.laundry_balance >= 0" class="h3 mb-8 color-primary">
                  {{ formatMoneyWithCommas(this.unit.laundry_balance) }}</h3>
                <h3 v-else class="h3 mb-8 color-danger">{{ chargeAmountFormatter(this.unit.laundry_balance) }}</h3>
                <p class="text-normal mb-6">No extra fee</p>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="card">
                <p class="text-normal color-secondary mb-8">Rent Balance</p>
                <h3 v-if="this.unit.rent_balance >= 0" class="h3 mb-8 color-primary">
                  {{ formatMoneyWithCommas(this.unit.rent_balance) }}</h3>
                <h3 v-else class="h3 mb-8 color-danger">{{ chargeAmountFormatter(this.unit.rent_balance) }}</h3>
                <p class="text-normal mb-6">No extra fee</p>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="card">
                <p class="text-normal color-secondary mb-8">Amenities Balance</p>
                <h3 v-if="this.unit.amenity_balance >= 0" class="h3 mb-8 color-primary">
                  {{ formatMoneyWithCommas(this.unit.amenity_balance) }}</h3>
                <h3 v-else class="h3 mb-8 color-danger">{{ chargeAmountFormatter(this.unit.amenity_balance) }}</h3>
                <p class="text-normal mb-6">No extra fee</p>
              </div>
            </div>
            <!--          Ask Silas-->
            <!--          <div class="col-xl-3 col-lg-4 col-md-6">-->
            <!--            <div class="card">-->
            <!--              <p class="text-normal color-secondary mb-8">Spending History</p>-->
            <!--              <h3 class="h3 mb-8 color-primary">$19,445.00</h3>-->
            <!--              <div class="progress mb-13">-->
            <!--                <div-->
            <!--                    class="progress-bar"-->
            <!--                    role="progressbar"-->
            <!--                    style="width: 25%"-->
            <!--                    aria-valuenow="25"-->
            <!--                    aria-valuemin="0"-->
            <!--                    aria-valuemax="100"-->
            <!--                ></div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--          </div>-->
          </div>
        </div>

        <div class="mb-64">
          <h3 class="h3 mb-28" v-if="this.unit.unit_type">
            Allowed residents <span class="color-primary">{{
              this.unit.users.length
            }} 0f {{ this.unit.unit_type.num_users }}</span>
          </h3>
          <h3 v-else></h3>

          <div class="d-flex flex-row flex-wrap">
            <div v-for="resident in this.unit.users" class="d-flex me-20 mb-8">
              <div class="avatar-sm me-8">
                <img
                    v-if="resident.profile_picture"
                    :src="resident.profile_picture"
                    class="avatar-img"
                />
                <img
                    v-else
                    :src="'/images/avatar.png'"
                    class="avatar-img"
                />
              </div>
              <div>
                <p class="text-medium-bold mb-4">{{ resident.name }}
                  <img title="Unverified email or mobile"
                       v-if="resident.email_verified !== 1 || resident.mobile_veridication === 'No'"
                       :src="'/images/warning.png'"
                  />
                </p>
                <p class="text-normal mb-4 color-primary">{{ resident.mobile }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-64">
          <h3 class="h3 mb-28">Profiles</h3>

          <div class="row gy-3">
            <div v-for="resident in this.unit.users" class="col-xl-4 col-lg-6 col-md-6">
              <div class="card">
                <div class="d-flex justify-content-between mb-20">
                  <div>
                    <div class="d-flex">
                      <h3
                          class="h3 mb-2 me-8"
                      >
                        <span v-if="resident.id === unit.guarantor_user_id">Guarantor</span>
                        <span v-else-if="resident.id === unit.co_guarantor_user_id">Co-Guarantor</span>
                        <span v-else>Resident</span>
                      </h3>
                      <i class="fa fa-caret-down color-secondary select-cursor" style="padding-top: 5px;padding-right:5px" id="unitDropdown" data-bs-toggle="dropdown" aria-expanded="false"></i>
                      <div class="dropdown">
                        <ul
                            class="dropdown-menu dropdown-menu-end border-standered"
                            aria-labelledby="unitDropdown"
                        >
                          <li
                              class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                          >
                            Action
                          </li>
                          <li
                              class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="vacateResident(resident)"
                          >
                            Vacate Rasident
                          </li>

                          <li v-for="linkedUnit1 in resident.units" class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="unlinkLinkedUnit(resident.id,linkedUnit1.id)" v-if="linkedUnit1.building && linkedUnit1.id !== unit.id">
                            Unlink from {{ linkedUnit1.building.name }} / {{ linkedUnit1.unit_no }}
                          </li>
                          <li v-for="linkedUnit1 in resident.units" class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="takeMe(linkedUnit1.id,linkedUnit1.building, unit.building )" v-if="linkedUnit1.building && linkedUnit1.id !== unit.id">
                            Take me to {{ linkedUnit1.building.name }} / {{ linkedUnit1.unit_no }}
                          </li>
                          <li
                              class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="linkToAnotherUnit(resident.id)"
                          >
                            Link to another unit
                          </li>
                          <li
                              class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="makeGuarantorOrCoguarantor(unit.id, resident.id, 'guarantor')"
                          >
                            Make Guarantor
                          </li>
                          <li
                              class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="makeGuarantorOrCoguarantor(unit.id, resident.id, 'Co-Gurantor')"
                          >
                            Make Co-Guarantor
                          </li>
                          <li
                              class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor" @click="switchGuarantorCoGuarantor(unit.id)"
                          >
                            Switch Guarantor / Co-Guarantor
                          </li>
                        </ul>
                      </div>
                      <div>
                        <img src="/images/icons/edit.png" class="select-cursor" @click="editResidentPopup(resident)"
                             alt=""/>
                      </div>
                    </div>
                    <div>
                      <p class="text-medium-bold color-primary mb-0">{{ resident.name }}</p>
                    </div>
                    <div>
                      <p class="text-medium color-primary mb-0">Update new image</p>
                    </div>
                  </div>

                  <div class="text-end">
                    <div class="avatar-sm ms-auto">
                      <img
                          src="/images/test-avatar.png"
                          class="avatar-img"
                          alt=""
                      />
                    </div>
                    <p class="text-medium color-primary mb-0">Resident FOB</p>
                  </div>
                </div>

                <div class="mb-20">
                  <p class="text-normal-bold-md color-secondary mb-0">Linked to</p>
                  <p v-for="linkedUnit in resident.units" class="text-normal mb-0"
                     v-if="linkedUnit.building && linkedUnit.id !== unit.id">
                    {{ linkedUnit.building.name }} / {{ linkedUnit.unit_no }}</p>
                </div>

                <div class="mb-20">
                  <p class="text-normal-bold-md color-secondary mb-0">Email</p>
                  <p class="text-normal color-primary mb-0">{{ resident.email }}</p>
                  <p v-if="!resident.email_verified" class="text-normal color-primary mb-0">
                    <span class="red">Unverified</span>
                  </p>
                </div>

                <div class="mb-20">
                  <p class="text-normal-bold-md color-secondary mb-0">
                    Date of birth
                  </p>
                  <p class="text-normal color-primary mb-0">{{ formatDateStrict(resident.dob) }}</p>
                </div>

                <div class="row">
                  <div class="col-xl-6 mb-20">
                    <p class="text-normal-bold-md color-secondary mb-0 me-8">
                      Intercom assigned <span class="green-dot"></span>
                    </p>
                    <intercom-assigned :unit="unit" :user="resident"></intercom-assigned>
                  </div>
                </div>

                <div class="mb-20">
                  <p class="text-normal-bold-md color-secondary mb-0">Mobile</p>
                  <resident-mobile :user="resident"></resident-mobile>
                </div>

                <div class="mb-20">
                  <p class="text-normal-bold-md color-secondary mb-0">Status</p>
                  <p class="text-normal mb-0">
                    Gauarantor |<span class="color-primary"
                  >Switch to cogauarantor</span
                  >
                  </p>
                </div>

                <div class="row">
                  <resident-self-verification-link :user="resident"></resident-self-verification-link>
                  <!--                <manually-verify-user :user="resident"></manually-verify-user>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <doorIntercom :unit="this.unit" ref="doorIntercom"></doorIntercom>
      <template v-for="resident in unit.users">
        <residentFobs
            :tokens="resident.tokens" :user="resident" :building_id="building_id"
            :unit_id="unit.id"/>
      </template>


      <connectedDevices :macs="this.unit.macs" :user="null" :building_id="building_id"
                        :unit_id="this.unit.id"/>
      <residentInternet :units="[this.unit]" :user="this.unit.guarantor"/>
      <residentTelephone :unit="this.unit"/>
      <div class="mb-64">
        <h3 class="h3 mb-28">Lease History</h3>
        <leaseHistoryAnalytics :unit-id="this.unit.id" ref="leaseHistoryAnalytics"></leaseHistoryAnalytics>
      </div>
      <unitModal/>
      <addResidentModal ref="addNewResidentModal" :id="'unit'"></addResidentModal>
      <linkToAnotherUnit ref="linkToAnotherUnit" /> 

    </div>
  </div>
</template>

<script>
import unitModal from "./unit-modal.vue";
import Multiselect from "vue-multiselect";
import IntercomAssigned from "../../shared/resident/intercomAssigned.vue";
import ResidentMobile from "../../shared/resident/residentMobile.vue";
import ResidentSelfVerificationLink from "../../shared/resident/send-self-verification-link.vue";
import ManuallyVerifyUser from "../../shared/resident/manually-verify-user.vue";
import connectedDevices from "../../pages/residents/connectedDevices.vue";
import residentInternet from "../../pages/residents/residentInternet.vue";
import residentFobs from "../../pages/residents/residentFobs.vue";
import leaseHistoryAnalytics from "../analytics/user/lease-history-analytics.vue";
import addResidentModal from "../../pages/residents/addResidentModal.vue";
import residentTelephone from "../../pages/residents/residentTelephone.vue";
import doorIntercom from "./doorIntercom.vue";
import unitDropdown from "./unitDropdown.vue";
import linkToAnotherUnit from "./linkToAnotherUnit.vue"
import {config} from "../../config";

export default {
  components: {
    unitModal,
    Multiselect,
    IntercomAssigned,
    ResidentMobile,
    ResidentSelfVerificationLink,
    ManuallyVerifyUser,
    residentFobs,
    connectedDevices,
    residentInternet,
    leaseHistoryAnalytics,
    addResidentModal,
    residentTelephone,
    doorIntercom,
    unitDropdown,
    linkToAnotherUnit
  },
  computed: {
    building_id() {
      if(this.$route.params.building_id) return this.$route.params.building_id;
      else return this.$store.getters.getBuildingId;
    }
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      if (oldBuildingId && oldBuildingId !== newBuildingId)
        this.$router.push('/units');
      else
        this.reloadData();
    }
  },
  created() {
    if(this.$route.params.building_id){
      // this.building_id = this.$route.params.building_id;
      this.$emit('setBuildingInSession',this.building_id);
    }
    this.reloadData();
  },
  data() {
    return {
      unit: null,
      nextUnit: null,
      previousUnit: null,
    };
  },
  methods: {
    linkToAnotherUnit(user_id){
      this.$refs.linkToAnotherUnit.form.reset();
      this.$refs.linkToAnotherUnit.form.user_id = user_id;
      this.$refs.linkToAnotherUnit.getBuildings();
      $('#linkToAnotherUnit').modal('show');
    },
    switchGuarantorCoGuarantor(unit_id){
      Swal.fire({
        title: config.confirmBoxTitle,
        text: 'Are you sure you want to switch guarantor and co-guarantor for this unit?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();
          this.$http
            .get("/switchGuarantorCoGuarantor/"+unit_id)
            .then((response) => {
                response = response.data;
                if (response.status == 'success') {
                    Toast.fire({
                      icon: "success",
                      position: "top",
                      title: 'Guarantor and co-guarantor switched succesfully.',
                    });
                    this.reloadData();
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
    makeGuarantorOrCoguarantor(unit_id, user_id, type){
      this.showLoader();
      this.$http
        .get("/makeGuarantorOrCoguarantor/"+unit_id+'/'+user_id+"/"+type)
        .then((response) => {
            response = response.data;
            if (response.status == 'success') {
                Toast.fire({
                  icon: "success",
                  position: "top",
                  title: this.capitalizeFirstLetter(type) + " has been successfully channged for this unit.",
                });
                this.reloadData();
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
    },
    takeMe(unit_id, building, currentBuilding){
      Swal.fire({
        text: 'Are you sure you want to exit '+ currentBuilding.name +' and move to ' + building.name + '?',
        title: config.confirmBoxTitle,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
            this.$emit('setBuildingInSession',building.id);
            window.location = '/unit-detail/'+unit_id;        
        }
      });
    },
    unlinkLinkedUnit(user_id,unit_id){
      // console.log(unit_id);return;
      this.showLoader();
      this.$http
        .get("/un_link_unit/"+unit_id+'/'+user_id)
        .then((response) => {
            response = response.data;
            if (response.status == 'success') {
                Toast.fire({
                  icon: "success",
                  position: "top",
                  title: "The resident has been successfully vacated.",
                });
                this.reloadData();
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
    },
    vacateResident(resident){
      if(resident.id == this.unit.co_guarantor_user_id || resident.id == this.unit.guarantor_user_id){
        Toast.fire({
          icon: "error",
          position: "top",
          title: "Guarantor/Co-Guarantor cannot be moved out. Please set different resident as Guarantor/Co-Guarantor first",
        });
        return;
      }
      
      Swal.fire({
        title: "Are you sure you want to move out " + resident.firstname + ' ' + resident.lastname + '?',
        text: 'This user is linked with other units. Do you want to move out this user from all units? *Note: if you desire to keep user in Buildling B/unit B. Accept Yes, moving them out of both Units then select Unit B and use "add user" using the universal OWNER search to move them into Unit B. (fyi, all deleted, current users will be displayed in the live drop down)',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
            let params = {
              unit_id: this.unit.id,
              user_id : resident.id
            };
            this.showLoader();
            this.$http
                .post("/moveoutuser", params)
                .then((response) => {
                    response = response.data;
                    if (response.status == 'success') {
                        Toast.fire({
                          icon: "success",
                          position: "top",
                          title: "The resident has been successfully vacated.",
                        });
                        this.reloadData();
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
    editResidentPopup(user) {
      this.$refs.addNewResidentModal.title = 'Edit resident';
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      this.$refs.addNewResidentModal.form.user_id = user.id;
      this.$refs.addNewResidentModal.form.unit_id = this.unit;
      this.$refs.addNewResidentModal.loadDetails(user, false);
      $('#addNewResidentModalunit').modal('show');
    },
    openAddResidentPopup() {
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      $('#addNewResidentModalunit').modal('show');
    },
    reloadData() {
      if (this.building_id) {
        this.showLoader();
        this.$http
            .get("/unit/" + this.$route.params.id)
            .then((response) => {
              response = response.data;
              if (response.status) {
                this.unit = response.unit;
                this.removeLoader();
                this.getNextAndPreviousUnit();
              } else {
                Toast.fire({icon: 'warning', title: response.message});
                this.removeLoader();
              }
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      }
    },
    getNextAndPreviousUnit() {
      let params = {id: this.unit.id};
      this.$http
          .post("/getNextAndPreviousUnit", params)
          .then((response) => {
            response = response.data;
            if (response.status) {
              this.nextUnit = response.next;
              this.previousUnit = response.previous;
            } else {
            }
          })
          .catch((error) => {
            console.error(error);
          });
    },
    redirectToUnitsTab() {
      this.$router.push('/units');
    },
    redirectToUnit(id) {
      this.$router.push('/unit-detail/' + id);
      this.reloadData();
    },
  }
};
</script>
<style scoped>
@media screen and (max-width: 767px) {
  .card {
    padding: 16px;
  }
}
</style>
