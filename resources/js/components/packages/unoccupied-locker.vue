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
              <p class="color-primary text-normal mb-0" v-if="data.locker_screen">{{ data.locker_screen.name }}</p>
            </div>

          </div>
          <hr class="mt-9 mb-16"/>
        </div>
      </div>
      <span>
      </span>
    </div>
    <pagination :pagination="dataArray"></pagination>
  </div>
</template>

<script>
export default {
  components: {},
  created() {
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      if (this.$parent.activeTab === 'unoccupiedLocker')
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
      entriesPerPageSelected: null,
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
      params.occupied = 0;
      params.totalItems = this.entriesPerPageSelected;
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
  }
};
</script>
