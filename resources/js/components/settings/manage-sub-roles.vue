<template>
  <div class="f-accounts a-admin">
    <div class="row">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
    </div>
    <div class="d-flex flex-column align-items-start flex-lg-row mb-32">
      <h3 class="h3 mb-12 me-100 align-self-lg-center">All Sub Roles</h3>
      <div class="align-self-lg-center mb-12">
        <button
            class="btn bkg-success"
            @click="addNewSubRole"
        >
          Add New Sub Role
        </button>
      </div>
    </div>

    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>Name
              <Sorting :sortColumn="'name'"/>
            </th>
            <th>Alias
              <Sorting :sortColumn="'alias'"/>
            </th>
            <th>Created By
              <Sorting :sortColumn="'created_by'"/>
            </th>
            <th>Created At
              <Sorting :sortColumn="'created_at'"/>
            </th>
            <th>Updated By
              <Sorting :sortColumn="'updated_by'"/>
            </th>
            <th>Updated At
              <Sorting :sortColumn="'updated_at'"/>
            </th>
            <th>Action
            </th>
          </tr>

          <tr class="border_bottom" v-for="data in this.dataArray.data">
            <td>
              <span class="text-medium">{{ data.name }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.alias }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.created_by">{{ data.created_by.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.created_at) }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.updated_by">{{ data.updated_by.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.updated_at) }}</span>
            </td>
            <td class="d-flex">
              <span class="">
                <img src="/images/icons/edit.png" class="select-cursor" alt="edit" @click="addNewSubRole(data)"/>
              </span>
              <!--              <span class="">-->
              <!--                <img src="/images/icons/trash.png" class="select-cursor" @click="deleteWallet(wallet.id)" alt="trash"/>-->
              <!--              </span>-->
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <AddNewSubRoleModal ref="AddNewSubRoleModal"></AddNewSubRoleModal>
  </div>
</template>

<script>
import SearchKeyword from "../search-keyword.vue";
import EntriesPerPage from "../entries-per-page.vue";
import AddNewSubRoleModal from "./add-new-sub-role-modal.vue";

export default {
  components: {
    SearchKeyword,
    EntriesPerPage,
    AddNewSubRoleModal,
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

      this.$http
          .post("/loadSubRoles", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    addNewSubRole(data = null) {
      if (data) {
        this.$refs.AddNewSubRoleModal.title = "Edit Sub Role";
        this.$refs.AddNewSubRoleModal.form.id = data.id;
        this.$refs.AddNewSubRoleModal.form.name = data.name;
        this.$refs.AddNewSubRoleModal.form.alias = data.alias;
      }
      $('#addNewSubRoleModal').modal('show');
    },
  }
};
</script>
