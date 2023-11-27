<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Error Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Title
              <Sorting :sortColumn="'title'"/>
            </th>
            <th>
              Description
              <Sorting :sortColumn="'description'"/>
            </th>
            <th>
              Entity Name
              <Sorting :sortColumn="'entity_name'"/>
            </th>
            <th>
              Entity Id
              <Sorting :sortColumn="'entity_id'"/>
            </th>
            <th>
              Building
              <Sorting :sortColumn="'building_id'"/>
            </th>
            <th>
              Unit
              <Sorting :sortColumn="'unit_id'"/>
            </th>
            <th>
              User
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>
              Location
              <Sorting :sortColumn="'location'"/>
            </th>
            <th>
              Timestamp
              <Sorting :sortColumn="'created_at'"/>
            </th>
<!--            <th>-->
<!--              Notes-->
<!--            </th>-->
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.title }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.description }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.entity_name }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.entity_id }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.building">{{ data.building.name }}</span>
              <span v-else>-</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.unit">{{ data.unit.unit_no }}</span>
              <span v-else>-</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name }}</span>
              <span v-else>-</span>
            </td>
            <td>
              <span class="text-medium">{{ data.location }}</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.created_at) }}</span>
            </td>
<!--            <td>-->
<!--              <span class="text-medium">{{ data.notes }}</span>-->
<!--            </td>-->
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>
import Sorting from "../sorting.vue";
import EntriesPerPage from "../entries-per-page.vue";
import SearchKeyword from "../search-keyword.vue";

export default {
  components: {
    Sorting,
    EntriesPerPage,
    SearchKeyword
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
          .post("/errorLogs", params)
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
