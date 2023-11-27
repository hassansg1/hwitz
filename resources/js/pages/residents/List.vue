<template>
  <div class="residents-section">
    <div class="resident-list-section" v-if="showResidentsListingSection">
      <div class="d-flex flex-row flex-wrap mb-28" style="justify-content: space-between;">
        <div>
          <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="search"
            @change="loadData()"     
        />
        </div>
        <div>
          <button class="btn bkg-success me-20 mb-12 mb-md-0" @click="openAddResidentPopup">
            + Add new resident
          </button>
        </div>
      </div>
      
      <div class="row gy-5 gx-5 mb-20 mx-0">
        <div class="col-lg-4 col-md-6 col-12">
          <div class="row bkg-secondary color-secondary">
            <div class="col-2"></div>
            <div class="col-10">
              <div class="py-7">Name</div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 d-none d-md-block">
          <div class="row bkg-secondary color-secondary">
            <div class="col-2"></div>
            <div class="col-10">
              <div class="py-7">Name</div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 d-none d-lg-block">
          <div class="row bkg-secondary color-secondary">
            <div class="col-2"></div>
            <div class="col-10">
              <div class="py-7">Name</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-20 mx-0">
        <div class="col-lg-4 col-md-6 col-12" v-for="(unit_users,index) in data" v-if="unit_users.user">
          <div class="row mx-0 border-bottom">
            <div class="col-1">
              <div class="py-7">
                <!-- <input class="form-check-input me-12" type="checkbox"  :id="'flexCheckDefault' + unit_users.user.id"/> -->
              </div>
            </div>

            <div class="col-10">
              <span class="text-dark text-decoration-none select-cursor" @click="loadResidentDetailSection(index,unit_users)">
                <div class="d-flex py-7">
                  <div class="align-self-center avatar-sm me-8">
                    <img :src=" unit_users.user && unit_users.user.profile_picture && unit_users.user.profile_picture !== '' ? unit_users.user.profile_picture : '/images/default-user.jpg'" class="avatar-img me-8" alt="worker"/>
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">{{ unit_users.user ? unit_users.user.name : '-' }}</p>
                    <p class="text-normal mb-0 color-primary">{{unit_users.user ? unit_users.user.mobile : '-'}}</p>
                  </div>
                </div>
              </span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row mb-20 mx-0">
        <pagination :pagination="pagination"></pagination>
      </div>
    </div>
    <div class="resident-detail-section">
      <ResidentDetailSection ref="residentDetailSection"></ResidentDetailSection>
    </div>

    <addResidentModal ref="addNewResidentModal" :id="'resident'"></addResidentModal>
  </div>
</template>
<script>
import ResidentDetailSection from "./Details.vue";
import addResidentModal from "./addResidentModal.vue";
export default {
  components:{
    ResidentDetailSection,
    addResidentModal
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
    this.showResidentDetailSection = false;
    this.showResidentsListingSection = true;
    this.loadData();  
  },
  data(){
    return {
      showResidentsListingSection : true,
      showResidentDetailSection : false,
      data : [],

      // pagination start
      pagination : '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      search : '',
      params : {},

      //pagination end
    };
  },
  methods : {
    openAddResidentPopup(){
      this.$refs.addNewResidentModal.form.reset();
      this.$refs.addNewResidentModal.getBuildings();
      $('#addNewResidentModalresident').modal('show');
    },
    loadResidentDetailSection(id,unit_users){
      this.$refs.residentDetailSection.showResidentDetailSection = true;
      this.$refs.residentDetailSection.showDetail(id,unit_users)
      this.showResidentsListingSection = false;
      this.$refs.residentDetailSection.data = this.data;
      this.$refs.residentDetailSection.pagination = this.pagination;
    },
    reloadData(){
      this.loadData();
    },  
    loadData(pageNumber = null){
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = this.building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;
      this.params.search = this.search;

      this.showLoader();
      this.$http
        .post("/residents",this.params)
        .then((response) => {
          this.pagination = response.data;
          this.data = response.data.data;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    }
  }
}
</script>