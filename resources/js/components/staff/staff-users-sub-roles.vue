<template>
  <div class="f-accounts a-admin">
    <div class="row">
      <EntriesPerPage ref="EntriesPerPage"/>
      <SearchKeyword ref="SearchKeyword"></SearchKeyword>
    </div>
    <div class="d-flex flex-column align-items-start flex-lg-row mb-32">
      <h3 class="h3 mb-12 me-100 align-self-lg-center">Staff User's Sub Roles</h3>
      <div class="align-self-lg-center mb-12">
        <button
            class="btn bkg-success"
            @click="assignNewSubRole"
        >
          Assign New Sub Role to Staff
        </button>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <select v-model="user_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select User (all)</option>
          <option v-for="user in this.$parent.staffUsers" :value="user.id">
            {{ user.firstname + " " + user.lastname }}
          </option>
        </select>
      </div>
      <!--      <div class="col-md-3">-->
      <!--        <select v-model="building_id"-->
      <!--                class="form-select" aria-label="Default select example"-->
      <!--                @change="loadData()">-->
      <!--          <option value="" selected>Select Building (all)</option>-->
      <!--          <option v-for="building in this.$parent.buildings" :value="building.id">-->
      <!--            {{ building.name }}-->
      <!--          </option>-->
      <!--        </select>-->
      <!--      </div>-->
      <div class="col-md-3">
        <select v-model="roleAlias"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Sub Role (all)</option>
          <option v-for="subRole in this.subRoles" :value="subRole.alias">
            {{ subRole.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>User
              <Sorting :sortColumn="'user_id'"/>
            </th>
            <th>Building
              <Sorting :sortColumn="'building_id'"/>
            </th>
            <th>Sub Role
              <Sorting :sortColumn="'sub_role'"/>
            </th>
            <th>Created By
              <Sorting :sortColumn="'created_by'"/>
            </th>
            <th>Created At
              <Sorting :sortColumn="'created_at'"/>
            </th>
            <th>Action
            </th>
          </tr>

          <tr class="border_bottom" v-for="data in this.dataArray.data">

            <td>
              <span class="text-medium" v-if="data.user">{{ data.user.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.building">{{ data.building.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.sub_role">{{ data.sub_role.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.created_by">{{ data.created_by.name }}</span>
              <span class="text-medium" v-else>-</span>
            </td>
            <td>
              <span class="text-medium">{{ formatDateTime(data.created_at) }}</span>
            </td>
            <td class="d-flex">
              <span class="">
                <img src="/images/icons/trash.png" class="select-cursor" @click="deleteAssignment(data.id)"
                     alt="trash"/>
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
    <AssignNewSubRoleModal ref="AssignNewSubRoleModal"></AssignNewSubRoleModal>
  </div>
</template>

<script>
import SearchKeyword from "../search-keyword.vue";
import EntriesPerPage from "../entries-per-page.vue";
import AssignNewSubRoleModal from "./assign-new-sub-role-modal.vue";
import { config } from "../../config";

export default {
  components: {
    SearchKeyword,
    EntriesPerPage,
    AssignNewSubRoleModal,
  },
  data() {
    return {
      subRoles: {},
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      user_id: "",
      building_id: "",
      roleAlias: "",
      selectedBuildingId: "",
      selectedBuildingUsers: "",
    };
  },
  created() {
    this.loadSubRoles();
  },
  methods: {
    loadSubRoles() {
      let params = {totalItems: 100000};
      this.$http
          .post("/loadSubRoles", params)
          .then((response) => {
            this.subRoles = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    deleteAssignment(id) {
      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to delete the sub role assignment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();
          let params = {id: id};
          this.$http
              .post("deleteSubRoleAssignment", params)
              .then((response) => {
                Toast.fire({
                  icon: response.data.status,
                  title: response.data.message,
                });
                this.loadData();
                this.removeLoader();
              })
              .catch((error) => {
                console.error(error);
              });
        }
      });
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
      params.userId = this.user_id;
      params.buildingId = this.$parent.selectedBuildingObj.id;
      params.roleAlias = this.roleAlias;
      this.selectedBuildingId = this.$parent.selectedBuildingObj.id;

      this.$http
          .post("/staffSubRoles", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    assignNewSubRole(data = null) {
      this.$refs.AssignNewSubRoleModal.users = this.$parent.staffUsers;
      this.$refs.AssignNewSubRoleModal.roles = this.subRoles;
      $('#assignNewSubRoleModal').modal('show');
    },
  }
};
</script>
