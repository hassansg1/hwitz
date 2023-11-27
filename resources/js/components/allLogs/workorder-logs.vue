<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Word Order Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Work Order Id / Subject
            </th>
            <th>
              Created Date
              <Sorting :sortColumn="'open_date'"/>
            </th>
            <th>
              In Progress Date
              <Sorting :sortColumn="'inprocess_date'"/>
            </th>
            <th>
              Expired Date
              <Sorting :sortColumn="'due_date'"/>
            </th>
            <th>
              Done Date
              <Sorting :sortColumn="'resolved_date'"/>
            </th>
            <th>
              Archived Date
              <Sorting :sortColumn="'closed_date'"/>
            </th>
            <th>
              Priority Logs
            </th>
            <th>
              Status
              <Sorting :sortColumn="'status'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium" v-if="data.workorder">{{ data.workorder.id }} / {{
                  data.workorder.subject
                }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateStrict(data.open_date) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateStrict(data.inprocess_date) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateStrict(data.due_date) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateStrict(data.resolved_date) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateStrict(data.closed_date) }}</span>
            </td>
            <td>
              <a href="javscript::void(0)" class="a-link" @click="openWorkOrderPriorityLogsModal(data)">View Priority Logs</a>
            </td>
            <td>
              <span class="text-medium">{{ data.status }}</span>
            </td>
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <workOrderPriorityLogsModal ref="workOrderPriorityLogsModal" />
  </div>
</template>

<script>
import Sorting from "../sorting.vue";
import EntriesPerPage from "../entries-per-page.vue";
import SearchKeyword from "../search-keyword.vue";
import workOrderPriorityLogsModal from "./work-order-priority-logs-modal.vue";

export default {
  components: {
    Sorting,
    EntriesPerPage,
    SearchKeyword,
    workOrderPriorityLogsModal
  },
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
  expose: ["loadData"],
  methods: {
    openWorkOrderPriorityLogsModal(workorder){
      this.$refs.workOrderPriorityLogsModal.data = workorder.priority_logs;
      $('#workOrderPriorityLogsModal').modal('show');
    },
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.searchKeyword = this.$refs.SearchKeyword.searchKeyword;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;

      this.$http
          .post("/workOrderLogs", params)
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
