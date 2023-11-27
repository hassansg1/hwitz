<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Data</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Unit No.</th>
            <th>Residents</th>
            <th>Used Mac Addresses</th>
            <th>Data Down</th>
            <th>Data Up</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.unit_no ?? '' }}</span>
            </td>
            <td>
              <ul>
                <li v-if="data.users" v-for="user in data.users">
                  {{ user.name }}
                </li>
              </ul>
            </td>
            <td>
              <ul>
                <li v-if="data.macs" v-for="mac in data.macs">
                  {{ mac }}
                </li>
              </ul>
            </td>
            <td>
              <span class="text-medium" v-if="data.incoming">{{ data.incoming ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.outgoing">{{ data.outgoing ?? '' }}</span>
            </td>
          </tr>
        </table>
        <br>
        <h4>Totals:</h4>
        <h5>Building : {{ this.$parent.currentBuilding.name }}</h5>
        <table class="table table-striped table-bordered" id="">
          <tr>
            <td><strong>Unique Units</strong></td>
            <td v-if="dataArray.data">{{ dataArray.data.length }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Avg Bandwidth
              per unit</strong></td>
            <td>{{ dataArray.avg_bandwidth }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Unique Devices</strong></td>
            <td>{{ dataArray.unique_devices }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Avg Bandwidth
              per device</strong></td>
            <td>{{ dataArray.avg_bandwidth_per_device }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>% Participation</strong></td>
            <td>{{ dataArray.participation }}%</td>
            <td colspan="6">&nbsp;</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";


export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange},
  created() {
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;

      this.$http
          .post("/dataAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
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

