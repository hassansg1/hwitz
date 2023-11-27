<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">Extension Logs</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Locker
              <Sorting :sortColumn="'locker_id'"/>
            </th>
            <th>
              Package Picture
            </th>
            <th>
              Drop Off At
            </th>
            <th>
              Previous Time
              <Sorting :sortColumn="'previous_time'"/>
            </th>
            <th>
              Extended Time
              <Sorting :sortColumn="'extended_time'"/>
            </th>
            <th>
              Performed By
              <Sorting :sortColumn="'extended_by'"/>
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">
                <span v-if="data.locker">{{ data.locker.label ?? '' }}</span>
                <span v-else>-</span>
              </span>
            </td>
            <td>
              <span class="text-medium" v-if="data.package">
                <div class="camera-pic-container position-relative pt-7">
                <img height="100%" width="100%" title="click to enlarge" class="viewImage" data-toggle="modal"
                     data-target="#myModal" :src="'/getBlobPhoto/'+data.package.id +'/package_picture'"
                     alt="packagePicture">
                <img
                    src="/images/icons/expand.png"
                    class="position-absolute select-cursor"
                    style="bottom: 10px; right: 10px"
                    alt=""
                />
                </div>
              </span>
            </td>
            <td>
              <span class="text-medium" v-if="data.package && data.package.arrived_at !== ''">{{
                  formatDateTime(data.package.arrived_at)
                }}</span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatDateTime(data.previous_time) }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium">
                <span>{{ formatDateTime(data.extended_time) }}</span>
              </span>
            </td>
            <td>
              <span v-if="data.user">{{ data.user.name ?? '' }}</span>
              <span v-else>-</span>
            </td>
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
          .post("/extensionLogs", params)
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
