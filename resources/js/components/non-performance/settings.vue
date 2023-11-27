<template>
  <div class="f-summary">
    <div>
      <div class="row mb-28">
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
                @input="this.loadData"
            ></multiselect>
          </div>
        </div>
      </div>
      <div class="row mb-28" v-if="this.dataArray">
        <div class="row">
          <div class="col-md-3">
            <dt>
              Name
            </dt>
            <input disabled type="text" class="form-control" id=""
                   :value="this.dataArray.subtype"/>
          </div>
          <div class="col-md-3">
            <dt>
              Due Date (day of month) (variable x)
            </dt>
            <input disabled type="text" class="form-control" id=""
                   :value="this.dataArray.due_date"/>
          </div>
        </div>
      </div>
      <div class="row mb-28" v-if="this.dataArray">
        <h3 class="h3 mb-34">Action Matrix</h3>
        <div class="text-start">
          <div class="table-responsive mb-28">
            <table>
              <tr>
                <th>Action Type</th>
                <th>Medium</th>
                <th>Subject Heading</th>
                <th>Amount</th>
                <th>Minimum Amount</th>
                <th>Execution Date</th>
                <th>Execution Time</th>
                <th>Content of message</th>
              </tr>
              <tr class="border_bottom" v-for="item in this.dataArray.sequence_items">
                <td>{{ item.type }}</td>
                <td>{{ item.medium }}</td>
                <td>{{ item.action_name }}</td>
                <td>
                  <span v-if="item.amount_type === 'rent_cart'">Rent Cart</span>
                  <span v-if="item.amount_type === 'amenities_cart'">Amenities Cart</span>
                  <span v-if="item.amount_type === 'late_fee'">Late Fee</span>
                  <span v-if="item.amount_type === 'shutoff_fee'">Shut Off/ Re Activation Fee</span>
                </td>
                <td>{{ item.minimum_amount }}</td>
                <td>x-{{ item.execution_date }}</td>
                <td>{{ convertToHoursMins(item.execution_time) }}</td>
                <td>View</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
      buildings: [],
      building: '',
      dataArray: null,
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      params: {},
    };
  },
  created() {
    this.getBuildings();
  },
  methods: {
    loadData() {
      let params = {};
      params.buildingId = this.building ? this.building.id : 0;
      if (params.buildingId === 0) {
        Toast.fire({icon: 'warning', title: 'No building selected. Please select building first and try again.'});
        return;
      }

      this.$http
          .post("/loadSequenceData", params)
          .then((response) => {
            this.dataArray = response.data;
            if (this.dataArray.status === "false") {
              Toast.fire({
                icon: 'warning',
                title: this.dataArray.message
              });
            }
            this.dataArray = this.dataArray.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            if (this.building == '') {
              this.building = this.buildings.find(obj => obj.id === this.$gate.building_id);
            }
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
