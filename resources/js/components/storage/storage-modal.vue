<template>
  <div class="storage-section">
    <div
      class="modal fade"
      :id="'storageModal'+id"
      tabindex="-1"
      aria-labelledby="storageModal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <form @submit.prevent="submitForm()" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                    <div class="col-lg-6 mx-auto">
                        <div class="h3 mb-13">Assign User to storage unit</div>
                        <p class="text-normal color-secondary">Select the user you want yo assign to the storage unit</p>
                        <alert-error :form="form"></alert-error>

                        <div class="multiselect-border multiselect-bold mb-16">
                            <div class="dropdown-wrapper">
                                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                    v-model="form.user"
                                    placeholder=""
                                    label="full_name"
                                    track-by="id"
                                    :multiple="true"
                                    :options="users"
                                ></multiselect>
                            </div>
                            <has-error :form="form" field="user"></has-error>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button class="btn bkg-success" type="submit">Assign</button>
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
  props : ["id"],
  data() {
    return {
      form : new Form({
          unit_id : '',
          user : [],
      }),
      users: [],
      visibleItems: [],
      showAll: false,
      building_id : ''
    };
  },
  methods: {
    submitForm(){
      this.showLoader();
      this.form
          .post('/api/parking/assign')
          .then(({ data }) => {
            console.log(data)
            let response = data;
            if(response.status == 'error'){
              Toast.fire({
                  icon: 'error',
                  title: response.message,
              });
              this.removeLoader();
              return;
            }
              Toast.fire({ icon: 'success', title: 'Storage assigned succesfully.' });
              this.$parent.loadData();
              $('#storageModal'+this.id).modal('hide');
          })
          .catch((error) => {
              Toast.fire({ icon: 'warning', title: 'Something went wrong. Please try again later' });
              this.removeLoader();
          });
    },
    loadUsers(type) {
        this.$http
            .get("/parking/loadUsers?building_id="+this.building_id)
            .then((response) => {
                this.users = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
    },
  },
};
</script>
