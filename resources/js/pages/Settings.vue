<template>
  <div class="rounded-lg pages-style">
    <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
      <li class="nav-item me-56 mb-12" role="presentation"
          v-if="$gate.permissions.includes('manage_sub_roles')">
        <div
            id="manage-sub-roles-tab"
            data-bs-toggle="pill"
            data-bs-target="#manage-sub-roles"
            type="button"
            role="tab"
            aria-controls="manage-sub-roles"
            aria-selected="true"
            @click="selectTab('manage-sub-roles')"
        >
          Manage Sub Roles
        </div>
      </li>
      <li class="nav-item me-56 mb-12" role="presentation"
          v-if="$gate.permissions.includes('manage_third_party_managers')">
        <div
            id="manage-party-admin-tab"
            data-bs-toggle="pill"
            data-bs-target="#manage-party-admin"
            type="button"
            role="tab"
            aria-controls="manage-party-admin"
            aria-selected="false"
            @click="selectTab('manage-users')"
        >
          Manage 3rd Party Managers
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
          class="tab-pane fade show"
          id="manage-sub-roles"
          role="tabpanel"
          aria-labelledby="manage-sub-roles-tab"
      >
        <ManageSubRoles ref="ManageSubRoles"></ManageSubRoles>
      </div>
      <div
          class="tab-pane fade"
          id="manage-party-admin"
          role="tabpanel"
          aria-labelledby="manage-party-admin-tab"
      >
        <ManageUsers ref="ManageUsers"></ManageUsers>
      </div>
    </div>
  </div>
</template>

<script>
import ManageSubRoles from "../components/settings/manage-sub-roles.vue";
import ManageUsers from "../components/settings/manage-users.vue";

export default {
  components: {
    ManageUsers,
    ManageSubRoles,
  },
  data() {
    return {};
  },
  methods: {
    selectTab(tab) {
      if (tab === "manage-sub-roles") {
        this.$refs.ManageSubRoles.loadData();
      }
      if (tab === "manage-users") {
        this.$refs.ManageUsers.loadData();
      }
    }
  }
};
</script>
