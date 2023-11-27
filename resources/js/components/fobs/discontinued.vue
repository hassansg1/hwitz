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
    <entriesPerPage ref="entriesPerPageDiscontinued"/>

    <div class="mb-28">
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>#</th>
              <th>FOB <Sorting :sortColumn="'card_id'" /></th>
              <th>Last Assigned to <Sorting :sortColumn="'user_history'" /></th>
              <th>Email <Sorting :sortColumn="'user_history'" /></th>
              <th>Date discarded <Sorting :sortColumn="'modified'" /></th>
              <th>Performed by <Sorting :sortColumn="'modified_by'" /></th>
              <th>Action</th>
              <th>Entry</th>
            </tr>

            <tr class="border_bottom" v-for="(fob,index) in data">
              <td>{{ index+1 }}</td>
              <td>
                <span class="text-medium-bold">{{fob.card_id}}</span>
              </td>
              <td>
                <div class="d-flex">
                  <div class="avatar-sm me-9">
                    <img
                      :src="fob.history_user && fob.history_user.profile_picture && fob.history_user.profile_picture != '' ? fob.history_user.profile_picture : '/images/default-user.jpg'"
                      class="avatar-img"
                      alt="card"
                    />
                  </div>
                  <div class="align-self-center">
                    <div class="text-medium-bold">{{fob.history_user ? fob.history_user.name : 'Deleted user'}}</div>
                    <div class="color-primary text-normal" v-if="fob.history_user">{{fob.history_user ? fob.history_user.mobile : '-'}}</div>
                  </div>
                </div>
              </td>

              <td>
                <div class="d-flex">
                  <div class="align-self-center text-medium color-secondary">
                    {{fob.history_user ? fob.history_user.email : 'Deleted user'}}
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex">
                  <div class="align-self-center text-medium color-secondary">
                    {{formatDate(fob.modified)}}
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex">
                  <div class="align-self-center text-medium color-secondary">
                    {{fob.modified_by ? fob.modified_by.name  : '-'}}
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex">
                  <div class="align-self-center text-medium color-danger select-cursor" @click="permanentlyDiscard(fob)">
                    Permanently discard
                  </div>
                </div>
              </td>
              <td>
                <a class="a-link" @click="entryActivity(fob)">Activity</a>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <span v-if="data.length > 0"><pagination :pagination="pagination"></pagination></span>
    <entryModal ref="entryModalDiscontinued" :type="'discontinued'" />
  </div>
</template>
<script>

import entriesPerPage from "../utils/entriesPerPage.vue";
import entryModal from "./entryModal.vue";

import { config } from "../../config"

export default {
  components: {
    entriesPerPage,
    entryModal
  },
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
    
  },
  methods : {
    loadData(pageNumber = null){
      console.log(this.activeTab)
      if(this.building_id == 0 || this.activeTab != 'discontinued') return;

      this.showLoader();

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = this.building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;

      this.$http
        .post("/getDiscontinuedFobs" , this.params)
        .then((response) => {
          this.data = response.data.data;
          this.pagination = response.data;
          this.$refs.entriesPerPageDiscontinued.value = response.data.perPage;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
        
    },
    lastUserEmailName(history, type){
      let email = '-';
      let name = '-';
      if(history.length > 0){
        let obj = history[history.length - 1];
        if(obj.user){
          name = obj.user.name;
          email = obj.user.email;
        }
      }
      return type == 'email' ? email : name;
    },
    entryActivity(fob){
      this.$refs.entryModalDiscontinued.building_id = this.building_id;
      this.$refs.entryModalDiscontinued.id = fob.id;
      this.$refs.entryModalDiscontinued.title = 'Entry Controller history for FOB ' + fob.card_id;
      this.$refs.entryModalDiscontinued.loadData();
      $('#fobEntryActivityModaldiscontinued').modal('show');

    },
    permanentlyDiscard(fob){
      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to permanently discard this fob?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();
          this.$http
            .get("/remove_permanently/"+fob.id)
            .then((response) => {
              this.loadData();
              Toast.fire({
                  icon: 'success',
                  title: 'Fob discarded succesfully',
              });
              this.removeLoader();
            })
            .catch((error) => {
              console.error(error);
            });
        }
      });
    }
  }
};
</script>
