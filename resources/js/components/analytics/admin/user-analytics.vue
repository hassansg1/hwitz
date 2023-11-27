<template>
  <div class="a-admin-components">
    <div class="row mb-28">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
      <div class="col-8">
        <h3 class="h3 mb-0">User</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              User#
              <Sorting :sortColumn="'id'"/>
            </th>
            <th>
              User name (type)
              <Sorting :sortColumn="'firstname'"/>
            </th>
            <th>Go to:address</th>
            <th>Activity info</th>
            <th>Login</th>
            <th>Entrances</th>
            <th>Emails</th>
            <th>Fobs assigned</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.u_id }}</span>
            </td>
            <td>
              {{ data.name }}
              <span v-if="data.usertype">({{ data.usertype.name ?? "" }})</span>
            </td>
            <td></td>
            <td>
              <span
                  style="cursor: pointer"
                  v-on:click="userActivityModal(data.u_id)"
                  class="text-medium-md color-primary"
              >
                Activity
              </span>
            </td>
            <td>
              <span
                  style="cursor: pointer"
                  v-on:click="loginActivityModal(data.u_id)"
                  class="text-medium-md color-primary"
              >
                Attempts
              </span>
            </td>
            <td>
              <span
                  style="cursor: pointer"
                  v-on:click="userEntranceModal(data.u_id)"
                  class="text-medium-md color-primary"
              >
                View
              </span>
            </td>
            <td>
              <span
                  style="cursor: pointer"
                  v-on:click="userEmailModal(data.u_id)"
                  class="text-medium-md color-primary"
              >
                View
              </span>
            </td>
            <td>
              <span
                  v-for="token in data.userTokens"
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
    <UserActivityModal ref="userActivityModal"/>
    <LoginActivityModal ref="loginActivityModal"/>
    <UserEntranceModal ref="userEntranceModal"/>
    <UserEmailModal ref="userEmailModal"/>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";
import Vue from "vue";
import UserActivityModal from "./user-activity-modal.vue";
import LoginActivityModal from "./login-activity-modal.vue";
import UserEntranceModal from "./user-entrance-modal.vue";
import UserEmailModal from "./user-email-modal.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import SearchKeyword from "../../search-keyword.vue";

export default {
  components: {
    Sorting,
    UserActivityModal,
    LoginActivityModal,
    UserEntranceModal,
    UserEmailModal,
    EntriesPerPage,
    BuildingDateRange
    , SearchKeyword},
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
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;

      this.$http
          .post("/userAnalytics", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    userActivityModal(id) {
      this.$refs.userActivityModal.userActivityModal(id);
      $("#userActivityModal").modal("show");
    },
    loginActivityModal(id) {
      this.$refs.loginActivityModal.loginActivityModal(id);
      $("#loginActivityModal").modal("show");
    },
    userEntranceModal(id) {
      this.$refs.userEntranceModal.userEntranceModal(id);
      $("#userEntranceModal").modal("show");
    },
    userEmailModal(id) {
      this.$refs.userEmailModal.userEmailModal(id);
      $("#userEmailModal").modal("show");
    },
  },
};
</script>
