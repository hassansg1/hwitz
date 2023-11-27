<template>
  <div class="workorder-archive">
    <div class="row mb-28">
      <div class="col-lg-3 col-md-6 multiselect-border">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize">Building</label>
          <multiselect 
                :selectLabel="''"
                :deselectLabel= "''"
            v-model="selectedBuilding"
            placeholder=""
            label="name"
            track-by="id"
            :options="buildings"
            @input="loadData(null,selectedBuilding)"
          ></multiselect>
        </div>
      </div>
    </div>
    <!-- <div>
      <BuildingDateRangeFilter v-if="$parent.archivedWorkOrderTab" />
    </div> -->
    <div>
      <EntriesPerPage />
    </div>
    <div class="table-responsive">
      <table>
        <tr>
          <th>Work order <Sorting :sortColumn="'subject'" /></th>
          <th>Date Created <Sorting :sortColumn="'created'" /></th>
          <th>Responsible <Sorting :sortColumn="'assign_maint'" /></th>
          <th>Status <Sorting :sortColumn="'status_id'" /></th>
          <th>Action</th>
        </tr>
        <tr class="border_bottom" v-for="workOrder in archivedWorkOrders.data">
          <td class="h4">{{ workOrder.subject }}</td>
          <td class="text-medium color-secondary">
            {{ formatDate(workOrder.created) }}
          </td>
          <td>
            <div class="d-flex">
              <div>
                <img
                  :src="
                    workOrder.maintainer && workOrder.maintainer.profile_picture
                      ? workOrder.maintainer.profile_picture
                      : '/images/default-user.jpg'
                  "
                  class="avatar-xsm me-8"
                  alt="worker"
                />
              </div>
              <div class="align-self-center text-medium color-secondary">
                {{ workOrder.maintainer ? workOrder.maintainer.name : "-" }}
                {{ workOrder.unit_no && workOrder.maintainer ? "("+workOrder.unit_no+")" :  '' }}
              </div>
            </div>
          </td>
          <td>
            <span class="status">
              {{
                workOrder.status_id == "Close" ? "Closed" : workOrder.status_id
              }}
            </span>
          </td>
          <td>
            <div
              class="reminder select-cursor"
              data-bs-toggle="modal"
              :data-bs-target="'#workOrderModal' + workOrder.id"
            >
              View
            </div>
          </td>
          <workOrderModal :workOrder="workOrder" />
        </tr>
      </table>
    </div>
    <span><pagination :pagination="archivedWorkOrders"></pagination></span>

    <!-- <SendWorkOrderModal /> -->
  </div>
</template>

<script>
// import SendWorkOrderModal from "./send-work-order-modal.vue";
import BuildingDateRangeFilter from "../building-date-range.vue";
import EntriesPerPage from "../entries-per-page.vue";
import workOrderModal from "./work-order-modal.vue";
export default {
  components: {
    BuildingDateRangeFilter,
    EntriesPerPage,
    workOrderModal,
  },
  data() {
    return {
      archivedWorkOrders: [],
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      selectedBuilding: null,
      dateRange: false,
      params: "",
      dateFilterRadio: "last_seven",
      date_filter_value: "",
      buildings : []
    };
  },
  computed: {
    selectDragAttribute() {
      if (
        typeof this.range.start !== "undefined" &&
        typeof this.range.end !== "undefined"
      ) {
        this.params.date_filter_value = this.range.start;
        this.params.date_filter_value += " - ";
        this.params.date_filter_value += this.range.end;
        this.loadData();
      }
    },
  },
  methods: {
    loadData(pageNumber = null, building = 0) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;
      this.showLoader();
      if(building != 0 ) this.selectedBuilding = building;
      this.params = {};
      this.params.building_id = this.selectedBuilding;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;

      this.params.date_filter_radio = this.dateFilterRadio;
      this.params.date_filter_value = this.date_filter_value;

      this.$http
        .post("/archivedWorkOrders", this.params)
        .then((response) => {
          this.archivedWorkOrders = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    selectEntries(index) {
      this.entriesPerPageSelected = index;
      this.loadData();
    },
    selectBuilding(index) {
      this.selectedBuilding = index;
      this.loadData();
      // this.$refs.deviceAnalytics.loadFiltersForDevices();
      // this.clearFilters = true;
      // this.selectInfo(this.infoIndex);
      // this.clearFilters = false;
    },
    dateFilter(filter) {
      this.dateRange = false;
      this.params.date_filter_radio = filter;
      if (filter === "date_range") {
        this.dateRange = true;
      } else {
        this.loadData();
      }
    },
    dateRange(index) {
      this.loadData();
    },
  },
  mounted() {
    this.selectedBuilding = this.$gate.building_id;
  },
};
</script>
