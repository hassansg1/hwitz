<template>
  <div class="f-accounts a-admin">
    <div class="row">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
    </div>
    <div class="d-flex flex-column align-items-start flex-lg-row mb-32">
      <h3 class="h3 mb-12 me-100 align-self-lg-center">All 3rd Party Managers</h3>
      <div class="align-self-lg-center mb-12">
        <button
            class="btn bkg-success"
            @click="addNewSystemUser"
        >
          Add New 3rd Party Manager
        </button>
      </div>
    </div>

    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>
              Name
              <Sorting :sortColumn="'name'"/>
            </th>
            <th>Mobile
              <Sorting :sortColumn="'mobile'"/>
            </th>
            <th>Mobile Verified
              <Sorting :sortColumn="'mobile_verification'"/>
            </th>
            <th>Email
              <Sorting :sortColumn="'email'"/>
            </th>
            <th>Email Verified
              <Sorting :sortColumn="'email_verified'"/>
            </th>
            <th>Status
              <Sorting :sortColumn="'status'"/>
            </th>
            <th>Action
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.mobile ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.mobile_verification === 'No' ? 'red' : '']">
                <span v-if="data.mobile_verification === 'No'">Not Verified</span>
                <span v-else>Verified</span>
              </span>
            </td>
            <td>
              <span class="text-medium">{{ data.email ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.email_verified === '0' ? 'red' : '']">
                <span v-if="data.email_verified === '0'">Not Verified</span>
                <span v-else>Verified</span>
              </span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.status === '0' ? 'red' : '']">
                <span v-if="data.status === '0'">Inactive</span>
                <span v-else>Active</span>
              </span>
            </td>
            <td>
              <span class="">
                <img src="/images/icons/edit.png" class="select-cursor" alt="edit" @click="addNewSystemUser(data)"/>
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <AddNewSystemUser ref="AddNewSystemUser"></AddNewSystemUser>
  </div>
</template>

<script>
import SearchKeyword from "../search-keyword.vue";
import EntriesPerPage from "../entries-per-page.vue";
import AddNewSystemUser from "./add-new-system-user.vue";

export default {
  components: {
    SearchKeyword,
    EntriesPerPage,
    AddNewSystemUser,
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
    };
  },
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
      params.usertypeId = 9;

      this.$http
          .post("/loadSystemUsers", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    addNewSystemUser(data = null) {
      $('#addNewSystemUser').modal('show');
    },
  }
};
</script>
