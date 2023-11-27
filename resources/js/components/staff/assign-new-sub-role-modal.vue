<template>
  <div>
    <div
        class="modal fade"
        id="assignNewSubRoleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="row">
              <form @submit.prevent="assignNewSubRole" id="broadcastMessageForm" @keydown="form.onKeydown($event)"
                    enctype="multipart/form-data">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Assign sub role to staff user</h1>
                  <alert-error :form="form"></alert-error>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      User
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect
                          :selectLabel="''"
                          :deselectLabel="''"
                          v-model="form.userId"
                          placeholder=""
                          label="fullname"
                          track-by="id"
                          :multiple="false"
                          :options="users"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="connection_type"></has-error>
                  </div>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Sub Role
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect
                          :selectLabel="''"
                          :deselectLabel="''"
                          v-model="form.roleId"
                          placeholder=""
                          label="name"
                          track-by="alias"
                          :multiple="false"
                          :options="roles"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="connection_type"></has-error>
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button class="btn bkg-success" type="submit" v-if="this.form.id">Update</button>
                    <button class="btn bkg-success" type="submit" v-else>Add</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      title: "Add new sub role",
      users: [],
      roles: [],
      form: new Form({
        userId: '',
        roleId: '',
      }),
    };
  },
  methods: {
    assignNewSubRole() {
      let params = {
        alias: this.form.roleId.alias,
        userId: this.form.userId.id,
        buildingId: this.$parent.selectedBuildingId
      };
      this.showLoader();
      this.$http
          .post("/assignNewSubRole", params)
          .then((response) => {
            this.$parent.loadData();
            Toast.fire({
              icon: response.data.status,
              title: response.data.message,
            });
            this.form.reset();
            $("#assignNewSubRoleModal").modal("hide");
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    }
  },
};
</script>

