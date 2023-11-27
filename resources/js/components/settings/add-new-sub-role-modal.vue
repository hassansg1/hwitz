<template>
  <div>
    <div
        class="modal fade"
        id="addNewSubRoleModal"
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
              <form @submit.prevent="addNewSubRole" id="broadcastMessageForm" @keydown="form.onKeydown($event)"
                    enctype="multipart/form-data">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Add new sub role</h1>
                  <alert-error :form="form"></alert-error>

                  <div class="row">
                    <div class="col-12 mb-3">
                      <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" required class="form-control" v-model="form.name"/>
                      </div>
                      <has-error :form="form" field="name"></has-error>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 mb-3">
                      <div>
                        <label for="name">Alias</label>
                        <input type="text" id="alias" required class="form-control" v-model="form.alias"/>
                      </div>
                      <has-error :form="form" field="alias"></has-error>
                    </div>
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
      form: new Form({
        name: '',
        alias: '',
        id: null,
      }),
    };
  },
  methods: {
    addNewSubRole() {
      this.showLoader();
      this.form
          .post("/api/addNewSubRoleModal")
          .then((response) => {
            this.$parent.loadData();
            Toast.fire({
              icon: response.data.status,
              title: response.data.message,
            });
            this.form.reset();
            $("#addNewSubRoleModal").modal("hide");
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
    }
  },
};
</script>

