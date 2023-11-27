<template>
  <div class="fobs-section">
    <!-- <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-32">
      <span>
        <span class="text-normal color-secondary me-8">verified users: </span>
        <a class="a-link me-20" href="">13/48</a>
      </span>
      <span>
        <a class="a-link me-20" href="">Select all</a>
      </span>

      <span>
        <a class="a-link" href="">Delete all</a>
      </span>
    </div> -->

    <entriesPerPage ref="entriesPerPage"/>

    <div class="mb-28">
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>#</th>
              <th>FOB <Sorting :sortColumn="'card_id'" /></th>
              <th>Assign to <Sorting :sortColumn="'user_id'" /></th>
              <th>Email <Sorting :sortColumn="'email'" /></th>
              <th>Status</th>
              <th>Entry</th>
            </tr>

            <tr class="border_bottom" v-for="(fob,index) in data">
              <td>{{ index+1 }}</td>
              <td>
                <span class="text-medium-bold">{{fob.card_id}}</span>
              </td>
              <td>
                <div class="d-flex">
                  <div class="me-9 avatar-sm">
                    <img  :src="fob.profile_picture && fob.profile_picture != '' ? fob.profile_picture : '/images/default-user.jpg'" class="avatar-img" alt="card"/>
                  </div>
                  <div class="align-self-center">
                    <div class="text-medium-bold">{{fob.firstname + ' ' + fob.lastname}}</div>
                    <div class="text-normal" :class="{ 'color-danger': fob.mobile_verification != 'Yes'}">{{fob.mobile}}</div>
                  </div>
                </div>
              </td>

              <td class="w-50">
                <div class="d-flex">
                  <div class="align-self-center">
                    <img v-if="fob.email_verified == 0" src="/images/icons/warning.png" class="me-12" alt="" />
                  </div>
                  <div class="align-self-center text-medium mt-6" :class="{ 'color-danger': fob.mobile_verification != 'Yes'  }">
                    {{fob.email}}
                  </div>
                </div>
              </td>
              <td>
                <div>
                  <div class="form-check form-switch p-0 mt-20">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="fob.is_active" role="switch" @click="recycleFob(fob)" :title="'Recycle'"/>
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </td>

              <td>
                <!-- <a class="a-link" data-bs-toggle="modal" data-bs-target="#fobsModal">Activity</a> -->
                <a class="a-link" @click="entryActivity(fob)">Activity</a>
              </td>
            </tr>
            <!-- <tr class="border_bottom">
              <td>
                <div>
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />
                </div>
              </td>
              <td>
                <span class="text-medium-bold">10-216</span>
              </td>
              <td>
                <div class="d-flex">
                  <div class="me-9">
                    <img
                      src="/images/worker.png"
                      class="avatar-sm"
                      alt="card"
                    />
                  </div>
                  <div class="align-self-center">
                    <div class="text-medium-bold">John Doe</div>
                    <div class="color-primary text-normal">786-345-1244</div>
                  </div>
                </div>
              </td>

              <td class="w-50">
                <div class="d-flex">
                  <div class="align-self-center text-medium color-secondary">
                    lia2m@email.com
                  </div>
                </div>
              </td>
              <td>
                <div>
                  <div class="form-check form-switch p-0 mt-20">
                    <label class="switch mb-0">
                      <input type="checkbox" checked role="switch" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </td>

              <td>
                <a
                  class="a-link"
                  data-bs-toggle="modal"
                  data-bs-target="#fobsModal"
                  >Activity</a
                >
              </td>
            </tr>
            <tr class="border_bottom">
              <td>
                <div>
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />
                </div>
              </td>
              <td>
                <span class="text-medium-bold">10-216</span>
              </td>
              <td>
                <div class="d-flex">
                  <div class="me-9">
                    <img
                      src="/images/worker.png"
                      class="avatar-sm"
                      alt="card"
                    />
                  </div>
                  <div class="align-self-center text-medium-bold color-danger">
                    Unassigned
                  </div>
                </div>
              </td>

              <td class="w-50">
                <div class="d-flex">
                  <div class="align-self-center">
                    <img src="/images/icons/warning.png" class="me-12" alt="" />
                  </div>
                  <div class="align-self-center text-medium color-danger mt-6">
                    lia2m@email.com
                  </div>
                </div>
              </td>
              <td>
                <div>
                  <div class="form-check form-switch p-0 mt-20">
                    <label class="switch mb-0">
                      <input type="checkbox" checked role="switch" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </td>

              <td>
                <a
                  class="a-link"
                  data-bs-toggle="modal"
                  data-bs-target="#fobsModal"
                  >Activity</a
                >
              </td>
            </tr> -->
          </table>
        </div>
      </div>
    </div>
    <span v-if="data.length > 0"><pagination :pagination="pagination"></pagination></span>
    <entryModal ref="entryModal" :type="'inUse'" />
  </div>
</template>
<script>
import entryModal from "./entryModal.vue";
import entriesPerPage from "../utils/entriesPerPage.vue";

import { config } from "../../config";
export default {
  components: {
    entryModal,
    entriesPerPage
  },
  props : ["loadedAsAComponentInDashboard"],
  data() {
    return {

      data : [],
      activeTab : '',

      // pagination start
      pagination : '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      params : {},

      //pagination end
    };
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
  created(){
    if(this.loadedAsAComponentInDashboard) this.entriesPerPageSelected = 10;
    this.loadData();
  },
  methods : {
    loadData(pageNumber = null){

      if(this.building_id == 0) return;

      if(!this.loadedAsAComponentInDashboard) this.showLoader();

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = this.building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;

      this.$http
        .post("/get_assigned_fobs" , this.params)
        .then((response) => {
          this.data = response.data.data;
          this.pagination = response.data;
          this.$refs.entriesPerPage.value = response.data.perPage;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
        
    },
    recycleFob(fob){
      

      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to recycle this fob?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();
          this.$http
            .get("/remove_and_recycle/"+fob.token_idd)
            .then((response) => {
              this.loadData();
              Toast.fire({
                  icon: 'success',
                  title: 'Fob recycled succesfully',
              });
              this.removeLoader();
            })
            .catch((error) => {
              console.error(error);
            });
        }
      });
      
    },
    entryActivity(fob){
      this.$refs.entryModal.building_id = this.building_id;
      this.$refs.entryModal.id = fob.token_idd;
      this.$refs.entryModal.title = 'Entry Controller history for FOB ' + fob.card_id;
      this.$refs.entryModal.loadData();
      $('#fobEntryActivityModalinUse').modal('show');

    }
  }
};
</script>
