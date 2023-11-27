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

    <entriesPerPage ref="entriesPerPageUnassigned"/>

    <div class="mb-28">
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>#</th>
              <th>FOB <Sorting :sortColumn="'card_id'" /></th>
              <th>Type <Sorting :sortColumn="'card_id'" /></th>
              <th>Last user email <Sorting :sortColumn="'id'" /></th>
              <th>Last user name <Sorting :sortColumn="'id'" /></th>
              <th>History</th>
              <th>Address</th>
            </tr>

            <tr class="border_bottom" v-for="(fob,index) in data">
              <td>{{ index+1 }}</td>
              <td>
                <span class="text-medium-bold">{{fob.card_id}}</span>
              </td>
              <td>
                <div>
                  <span class="text-medium color-secondary"> Physical </span>
                </div>
              </td>
              <td>
                <div>
                  <span class="text-medium color-secondary">
                    {{lastUserEmailName(fob.history, 'email')}}
                  </span>
                </div>
              </td>
              <td>
                <div>
                  <span class="text-medium color-secondary"> {{lastUserEmailName(fob.history, 'name')}} </span>
                </div>
              </td>
              <td>
                <a class="a-link" @click="historyModal(fob)">View</a>
              </td>
              <td>
                <div>
                  <button class="btn btn-dark" @click="assignFobModal(fob)">Assign FOB</button>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <span v-if="data.length > 0"><pagination :pagination="pagination"></pagination></span>
    <historyModal ref="historyModal" />
    <assignFobModal ref="assignFobModal" />
  </div>
</template>
<script>
import entriesPerPage from "../utils/entriesPerPage.vue";
import historyModal from "./historyModal.vue";
import assignFobModal from "./assignFobModal.vue";
export default {
  components: {
    entriesPerPage,
    historyModal,
    assignFobModal
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

      if(this.building_id == 0 || this.activeTab != 'unassigned') return;

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
        .post("/get_unassign_fobs" , this.params)
        .then((response) => {
          this.data = response.data.data;
          this.pagination = response.data;
          this.$refs.entriesPerPageUnassigned.value = response.data.perPage;
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
    historyModal(fob){
      this.$refs.historyModal.building_id = this.building_id;
      this.$refs.historyModal.id = fob.id;
      this.$refs.historyModal.title = 'Activity for FOB ' + fob.card_id;
      this.$refs.historyModal.loadData();
      $('#fobHistoryModal').modal('show');

    },
    assignFobModal(fob){
      this.$refs.assignFobModal.form.reset();
      this.$refs.assignFobModal.fob = fob;
      this.$refs.assignFobModal.loadUsers();
      $('#assignFobModal').modal('show');
    }
  }
};
</script>
