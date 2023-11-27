<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Phone</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Unit No.</th>
            <th>Residents</th>
            <th>Did Number</th>
            <th>Duration</th>
            <th>Total Calls</th>
            <th>Inbound</th>
            <th>Outbound</th>
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
              <span class="text-medium">{{ data.did_number ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.total_minutes ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.total_calls ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.incoming ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.outgoing ?? '' }}</span>
            </td>
          </tr>
        </table>
        <br>
        <h4>Totals:</h4>
        <h5 v-if="this.$parent.currentBuilding">Building : {{ this.$parent.currentBuilding.name }}</h5>
        <table class="table table-striped table-bordered" id="">
          <tr>
            <td><strong>Unique Units</strong></td>
            <td v-if="dataArray.data">{{ dataArray.data.length }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Total Minutes</strong></td>
            <td>{{ dataArray.total_minutesSum }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Total Calls</strong></td>
            <td>{{ dataArray.total_callsSum }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Total In</strong></td>
            <td>{{ dataArray.incomingSum }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Total Out</strong></td>
            <td>{{ dataArray.outgoingSum }}</td>
            <td colspan="6">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Avg Minutes<br>per unit</strong></td>
            <td>
              {{ dataArray.average }}
            </td>
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
      unitId: null,
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
      params.unitId = this.$parent.unitId ? this.$parent.unitId : null;

      this.$http
          .post("/phoneAnalytics", params)
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

