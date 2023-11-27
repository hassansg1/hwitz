<template>
    <div>
      <div
        class="modal fade"
        :id="'assignNewFobModal'"
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
                            <h1 class="h1 mb-13">Assign new fob</h1>
                            <alert-error :form="form"></alert-error>

                            <div class="multiselect-border multiselect-bold mb-16">
                                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                    Fobs
                                </label>
                                <div class="dropdown-wrapper">
                                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                        v-model="form.token_id"
                                        placeholder=""
                                        label="card_id"
                                        track-by="id"
                                        :multiple="false"
                                        :options="fobs"
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
        fobs : []


      };
    },
    methods: {
        submitForm(flag = false){
            this.showLoader();
            let token_id = this.form.token_id;
            if(!flag) this.form.token_id = this.form.token_id ? this.form.token_id.id : ''
            this.form
                .post('/api/updateFobAssignment')
                .then(({ data }) => {
                    Toast.fire({ icon: data.status, title: data.message });
                    this.$parent.reloadData();
                    $('#assignNewFobModal').modal('hide');
                    this.removeLoader();
                })
                .catch((error) => {
                    this.form.token_id = token_id;
                    Toast.fire({ icon: 'warning', title: 'Something went wrong. Please try again later' });
                    this.form.reset();
                    this.removeLoader();
                });
        },
        getBuildings(type) {
            this.$http
                .get("/buildings?unit_users_id="+this.form.user_id)
                .then((response) => {
                    this.buildings = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        loadUnits(){
            let units  = this.form.building_id.units;
            units = units.filter(unit => unit.is_physical == 1);

            this.units = units;
        },
        loadTokens(building_id,user_id){
            this.$http
                .get("/loadAllTokens/"+building_id+"/"+user_id)
                .then((response) => {
                    this.fobs = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    },
  };
  </script>

  