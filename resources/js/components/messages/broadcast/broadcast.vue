<template>
  <div class="broadcast">
    <div class="d-flex flex-wrap multiselect-border">
      <div class="dropdown-wrapper col-lg-3 mt-6" style="margin-right: 4px; min-width: 230px;">
        <label class="dropdown-label text-capitalize">Building</label>
        <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
          v-model="building_id"
          placeholder=""
          label="name"
          track-by="id"
          :options="buildings"
          @input="loadData()"
        ></multiselect>
      </div>
      <button style="margin-right: 4px; "
        class="btn btn-primary mt-6"
        @click="openBroadcastPopup"
      >
        + New Broadcast
      </button>

      <button style="margin-right: 4px;"
        class="btn btn-primary mt-6"
        @click="openBroadcastTemplatePopup"
      >
        + New Template
      </button>
    </div>
    <div class="table-responsive">
      <table>
        <!-- <tr class="border_bottom">
          <td class="h4 color-secondary-light">Broadcast Message 1</td>
          <td class="text-medium color-secondary me-auto">
            <div class="d-flex justify-content-between">
              <p class="align-self-center mb-0 color-secondary-light">
                Date sent 12-02-2022
              </p>
              <div class="d-flex justify-content-between">
                <p class="mb-0 text-danger me-12">2 days to be estimated</p>
                <p class="mb-0 color-primary me-12">Restore</p>
              </div>
            </div>
          </td>
        </tr> -->

        <tr class="border_bottom" v-for="data in history">
          <td class="h4">{{capitalizeFirstLetter(data.message_type)}} - {{ data && data.description ? data.description.replace(/<[^>]+>/g, '') : '' }}</td>
          <td class="text-medium color-secondary me-auto">
            <div class="d-flex justify-content-between">
              <p class="align-self-center mb-0">Date sent {{formatDate(data.created)}}</p>
              <div class="d-flex justify-content-between">
                <div class="align-self-center">
                  <!-- <img src="/images/icons/trash.png" alt="icon" /> -->
                  <i class="fa fa-info-circle select-cursor" @click="getUserPopupDetails(data.id,data.user_id)" aria-hidden="true" title="Popup user details"></i>
                  <span v-if="data.template_type == 'v' && data.verification">
                    <span v-if="data.verification.status == 'cancelled'">
                      Cancelled
                    </span>
                    <span v-else-if="data.verification.status == 'unverified' && new Date(data.verification.z_date) > new Date(data.date)">
                      <span class="color-primary text-medium-md mb-0 select-cursor" @click="stopVerification(data.id)">Stop Verification</span>
                    </span>
                    <span v-else-if="new Date(data.verification.z_date) <= new Date(data.date)">
                      Expired
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </td>
        </tr>

      </table>
    </div>

    <div class="row mt-4" v-if="building_id">
      <div class="col-lg-12 text-center">
        <pagination :pagination="pagination"></pagination>
      </div>
    </div>

    <BroadcastModal ref="broadcastModal" />
    <BroadcastTemplateModal />
    <Modal :id="0" :body="popupHtml" :title="'Broadcast User Details'" />
  </div>
</template>
<script>
import BroadcastModal from "./broadcast-modal.vue";
import BroadcastTemplateModal from "./custom-message.vue";

export default {
  components: {
    BroadcastModal,
    BroadcastTemplateModal
  },
  data() {
    return {
      history : '',
      building_id : '',
      popupHtml : '-',
      showAll : false,
      buildings : [],

      // pagination start
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      search: '',
      params: {},

      //pagination end
    };
  },
  methods : {
    getBuildings() {
      if(this.building_id) return;
      this.$http
        .get("/buildings")
        .then((response) => {
          this.buildings = response.data.data;
          if (this.$gate.building_id > 0) {
            this.building_id = this.buildings.find(
              (obj) => obj.id === this.$gate.building_id
            );
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    loadData(pageNumber = null){

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;
      
      let building_id = this.building_id ? this.building_id.id : 0;

      this.params = {};
      this.params.building_id = building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;
      // this.params.search = this.search;
      let params = this.params;
      this.showLoader();
      this.$http
        .get("/broadcastHistory",{params})
        .then((response) => {
          this.pagination = response.data;
          this.history = response.data.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    stopVerification(id){
      this.showLoader();
      this.$http
        .get("/stopVerificationBroadcast/"+id)
        .then((response) => {
          Toast.fire({ icon: "success", title: response.data.message });
          this.loadData();
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getUserPopupDetails(id, userIds){
      this.showLoader();
      this.$http
        .get("/workorder/getUserPopupDetails/"+id+'/'+userIds)
        .then((response) => {
          this.popupHtml = response.data.html;
          $('#modal-0').modal('show');
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openBroadcastPopup(){
      this.$refs.broadcastModal.params.building_id = this.building_id ? this.building_id.id : 0;
      this.$refs.broadcastModal.getBuildings();
      this.$refs.broadcastModal.getResidentsByFilters();
      this.$refs.broadcastModal.loadTemplates();
      this.$refs.broadcastModal.form.reset();
      this.$refs.broadcastModal.form.building_id = this.building_id? this.building_id.id : 0;
      this.$refs.broadcastModal.form.message_type = 'sms';
      this.$refs.broadcastModal.template = [];
      $('#broadcastModal').modal('show');
    },
    openBroadcastTemplatePopup(){
      
      $('#broadcastCustomMessageModal').modal('show');
    }
  }
};
</script>
