<template>
  <div class="units-section">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-32">
      <div class="align-self-center">
        <button class="btn btn-top bkg-success me-20 mb-8"
                @click="openAddResidentPopup"
        >
          + Assign unit
        </button>
      </div>
      <span class="align-self-center mb-8">
        <img title="Unverified email or mobile"
             :src="'/images/warning.png'"
        />
        <span class="text-normal color-secondary me-8">Unverified users: </span>
        <a class="a-link me-20" @click="loadUnverifiedUsersModal" href="javascript:void(0)">{{ this.unverifiedUsers }}/ {{
            this.totalUsers
          }}</a>
      </span>
      <span class="align-self-center mb-8">
        <span
            class="text-medium-bold color-primary select-cursor"
            data-bs-toggle="modal"
            data-bs-target="#unitUnoccupiedModal"
        >View vacancy report</span
        >
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
          <div class="d-flex justify-content-between mb-20">
            <div>
              <div class="d-flex cpointer" @click="openUnitTab(data)">
                <h3 class="h3 mb-2 me-8">{{ data.unit_no }}</h3>
                <div>
                  <img src="/images/icons/edit.png" alt=""/>
                  <img src="/images/icons/warning.png" alt=""/>
                </div>
              </div>
              <div>
                <p class="text-normal color-danger mb-0">Unoccupied</p>
              </div>
            </div>
          </div>
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
          <div class="row gy-3">
            <div class="col-12">
              <button
                  @click="openAddResidentPopup"
                  class="btn btn-dark">Assign to resident</button>
            </div>
          </div>
        </div>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <unVerifiedUsersModal ref="unVerifiedUsersModal"/>
    <unoccupiedModal/>
    <addResidentModal ref="addNewResidentModal"></addResidentModal>

  </div>
</template>

<script>
import unVerifiedUsersModal from "./unverfied-users-modal.vue";
import unoccupiedModal from "./unoccupied-modal.vue";
import addResidentModal from "../../pages/residents/addResidentModal.vue";

export default {
  components: {
    unVerifiedUsersModal,
    unoccupiedModal,
    addResidentModal,
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
    loadUnverifiedUsersModal() {
      this.$refs.unVerifiedUsersModal.users = this.unverifiedUsersList;
      $("#unverified-users-modal").modal("show");
    },
    openAddResidentPopup() {
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      $('#addNewResidentModal').modal('show');
    },
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

      params.occupied = 0;
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
    openUnitTab(data) {
      this.$router.push("unit-detail/" + data.id);
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
