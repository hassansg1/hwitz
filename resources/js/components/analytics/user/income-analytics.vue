<template>
  <div>
    <div class="row mb-28">
      <div class="col-8">
        <h3 class="h3 mb-0">Income</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Name
              <Sorting :sortColumn="'name'"/>
            </th>
            <th>
              Unit
              <Sorting :sortColumn="'unit_id'"/>
            </th>
            <th>
              Status
              <Sorting :sortColumn="'task_state'"/>
            </th>
            <th>
              Assigned To
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Created By
              <Sorting :sortColumn="'created_by'"/>
            </th>
            <th>
              Created At
              <Sorting :sortColumn="'created'"/>
            </th>
            <th>
              Viewed At
              <Sorting :sortColumn="'viewed_on'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.unit">{{ data.unit.unit_no ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.deleted_at">Deleted</span>
              <span class="text-medium" v-else-if="data.task_state === 'O'">Pending</span>
              <span class="text-medium" v-else-if="data.task_state === 'I'">In Progress</span>
              <span class="text-medium" v-else-if="data.task_state === 'C'">Completed</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.created_by">{{ data.created_by.name ?? 'System' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.created) }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDate(data.viewed_on) }}</span>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";


export default {
  components: {Sorting},
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
      let params = this.$parent.params;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;

      this.$http
          .post("/taskAnalytics", params)
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

