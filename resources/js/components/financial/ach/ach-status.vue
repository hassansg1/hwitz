<template>
  <div class="f-ach">
    <div class="row mb-50">
      <div class="col-lg-3 col-md-6">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize">Building</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="building"
              label="name"
              track-by="id"
              :options="buildings"
              :multiple="false"
              placeholder=""
              @input="loadData()"
          ></multiselect>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table">
        <tr>
          <th>Created Date
            <Sorting :sortColumn="'ach_batchid'"/>
          </th>
          <th>Batch Id</th>
          <th>Total Amount</th>
          <th># of items</th>
        </tr>

        <template v-for="(data, index) in data">
          <tr>
            <td class="text-medium color-secondary">{{ formatDate(data.created_at) }}</td>
            <td class="text-medium color-secondary">
              {{ data.id }}
            </td>
            <td class="text-medium-bold color-primary">
              {{ formatMoneyWithCommas(data.amount) }}
            </td>
            <td>
              <div class="d-flex justify-content-between">
                <div class="text-medium color-secondary">{{ data.items.length }}</div>

                <div @click="toggleRow(index)">
                  <img :class="{ rotateIcon: showRow && showTable === index }" src="/images/icons/down-arrow.png"
                       alt=""/>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="showRow">
            <td colspan="4" v-if="showRow && showTable === index">
              <table class="table table_styled">
                <tr>
                  <th>Building</th>
                  <th>Date</th>
                  <th>Receiver</th>
                  <th>Amount</th>
                  <th>ACH Status</th>
                </tr>
                <tr v-for="log in data.items">
                  <td class="text-medium-bold">{{ log.building ? log.building.name : '-' }}</td>
                  <td class="text-medium color-secondary">{{ formatDate(log.created_at) }}</td>
                  <td class="text-medium color-secondary">{{ log.receiver ? log.receiver.name : '-' }}</td>
                  <td class="text-medium-bold color-primary">{{ log.amount ? '$' + log.amount : '-' }}</td>
                  <td class="text-medium color-secondary " v-if="data.processed_at">
                    <span class="reconciled">
                    Reconciled
                  </span></td>
                  <td class="text-medium color-secondary " v-else>
                    <span class="pending">Pending</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </template>
      </table>
      <template v-if="data.length == 0">
        <div class="text-center">No Data found.</div>
      </template>
    </div>
    <span v-if="data.length > 0"><pagination :pagination="pagination"></pagination></span>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showRow: false,
      showTable: 0,
      buildings: [],
      building: '',
      data: [],
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      params: {},
    };
  },
  methods: {
    toggleRow(index) {
      if (this.showTable === index)
        this.showRow = !this.showRow;
      this.showTable = index;
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            this.buildings.unshift({"id": 0, "name": "Portfolio Wide"});
            if (this.building == '') {
              this.building = this.buildings.find(obj => obj.id === this.$gate.building_id);
            }
            this.loadData();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadData(pageNumber = null) {
      let building_id = this.building ? this.building.id : 0;

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;

      this.showLoader();
      this.$http
          .post("/loadAchStatusData", this.params)
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
  },
};
</script>
