<template>
  <div class="a-admin-components">
    <div class="row mb-28" v-if="!fromParking">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Unit</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Unit#
              <Sorting :sortColumn="'unit_no'"/>
            </th>
            <th>Current User (type)</th>
            <th>Go to:address</th>
            <th>Activity info</th>
            <th>Fobs assigned</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium"
              >{{ data.building.name }}/{{ data.unit_no }}</span
              >
            </td>
            <td>
              <div v-for="user in data.users" class="d-flex align-items-center">
                <div class="avatar-sm">
                  <img :src="user.profile_picture" class="avatar-img" alt=""/>
                </div>
                <span style="margin-left: 10px">{{ user.name }}</span>
                <span style="margin-left: 10px" v-if="user.units.length > 1">
                  <span
                      :title="`User is linked to ${user.units.length} other unit(s)`"
                      class="fas fa-eye"
                  ></span>
                </span>
              </div>
              <div v-if="data.users.length === 0">
                <span style="color: green; font-weight: bold">VACANT</span>
              </div>
            </td>
            <td>
              <span class="text-medium-md color-primary">
                <a target="_blank" href="" class="mus_link"
                >{{ data.building.name }},
                  {{ data.building.city }}
                  ({{ data.unit_no }})
                </a>
              </span>
            </td>

            <td>
              <span
                  style="cursor: pointer"
                  v-on:click="loadUnitActivityData(data.id)"
                  class="text-medium-md color-primary"
              >
                Activity
              </span>
            </td>
            <td>
              <span
                  v-for="token in data.tokens"
                  class="text-medium-md color-primary"
              >
                <li>
                  {{ token }}
                </li>
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <ActivityModal ref="activityModal"/>
  </div>
</template>

<script>
import ActivityModal from "./unit-activity-modal.vue";
import Sorting from "../../sorting.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import SearchKeyword from "../../search-keyword.vue";

export default {
  components: {Sorting, ActivityModal, EntriesPerPage, SearchKeyword},
  created() {
  },
  props: ["fromParking"],
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      dateRange: false,
      dateFilterRadio: "last_seven",
      date_filter_value: "",
      entriesPerPageSelected: null,

      unit_id: null,
    };
  },
  expose: ["loadData"],
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRangeFilter ? this.$refs.BuildingDateRangeFilter.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRangeFilter ? this.$refs.BuildingDateRangeFilter.date_filter_value : null;
      if (this.fromParking) {
        params.totalItems = 20;
      } else {
        params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      }
      params.buildingId = this.$parent.buildingIndex;

      if (this.unit_id) params.unit_id = this.unit_id;

      this.$http
          .post("/unitAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadUnitActivityData(unitId) {
      this.$refs.activityModal.loadUnitActivityData(unitId);
      $("#activityModal").modal("show");
    },
  },
};
</script>
