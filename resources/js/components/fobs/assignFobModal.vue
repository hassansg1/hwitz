<template>
    <div>
      <div
        class="modal fade"
        :id="'assignFobModal'"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header border-0">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="row">
                    <form @submit.prevent="submitForm(false)" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="col-lg-6 mx-auto">
                            <div class="h3 mb-13">Assign user to FOB {{ fob.card_id }}</div>
                            <p class="text-normal color-secondary">
                                If you selected a mobile FOB the user will receive an invitation
                                email.
                            </p>
                            <alert-error :form="form"></alert-error>

                            <div class="multiselect-border multiselect-bold mb-16">
                                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                    Users
                                </label>
                                <div class="dropdown-wrapper">
                                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                        v-model="selectedUser"
                                        placeholder=""
                                        label="fullname"
                                        track-by="id"
                                        :multiple="false"
                                        :options="users"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="unit_id"></has-error>
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
    components: {
        
    },
    mounted (){
    },
    data() {
      return {
        form : new Form({
            unit_id : '',
            user_id : '',
            building_id : '',
            token_id : '',
            prev_token_id : ''
        }),
        buildings : [],
        units : [],
        fobs : [],

        fob : '',
        users : [],
        selectedUser : ''


      };
    },
    methods: {
        submitForm(flag = false){
            this.showLoader();
            this.form.token_id = this.fob.id;
            this.form.building_id = this.$gate.building_id;
            this.form.unit_id = this.selectedUser.unit_id;
            this.form.user_id = this.selectedUser.id;
            this.form
                .post('/api/updateFobAssignment')
                .then(({ data }) => {
                    Toast.fire({ icon: data.status, title: data.message });
                    this.$parent.loadData();
                    $('#assignFobModal').modal('hide');
                    this.removeLoader();
                })
                .catch((error) => {
                    Toast.fire({ icon: 'warning', title: 'Something went wrong. Please try again later' });
                    this.form.reset();
                    this.removeLoader();
                });
        },
        loadUsers(type) {
            this.$http
                .get("/fobs/loadUsers?building_id="+this.$gate.building_id)
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

  