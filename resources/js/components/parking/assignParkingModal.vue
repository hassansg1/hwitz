<template>
    <div class="storage-section">
      <div
        class="modal fade"
        :id="'assignParkingModal'+id"
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
                          <div class="h3 mb-13">Assign User to parking unit</div>
                          <p class="text-normal color-secondary">Select the user you want yo assign to the parking unit</p>
                          <alert-error :form="form"></alert-error>
  
                          <div class="multiselect-border multiselect-bold mb-16">
                              <div class="dropdown-wrapper">
                                  <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                      v-model="form.user"
                                      label="full_name"
                                      track-by="id"
                                      :multiple="true"
                                      :options="users"
                                      placeholder="Users"
                                  ></multiselect>
                              </div>
                              <has-error :form="form" field="user"></has-error>
                          </div>

                          <div class="d-flex mb-20">
                            <input class="form-check-input mt-0 me-12" type="checkbox" value=""  id="flexCheckDefault" v-model="form.is_handicapped" />
                            <label class="text-normal align-self-center color-secondary form-check-label" for="flexCheckDefault">
                              Handicap
                            </label>
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
    data() {
      return {
        form : new Form({
            unit_id : '',
            user : [],
            is_handicapped : ''
        }),
        users: [],
        visibleItems: [],
        showAll: false,
        building_id : '',
        user : '',
        unit : ''
      };
    },
    props : ["id"],
    methods: {
      submitForm(){
        this.form
          .post("/api/parking/assign")
          .then((data) => {
            let response = data;
            if(response.data && response.data.status == 'error'){
              Toast.fire({
                  icon: 'error',
                  title: response.data.message,
              });
              return;
            }
            this.$parent.loadData();
            Toast.fire({
                icon: 'success',
                title: 'Changes saved successfully.',
            });
            $('#assignParkingModal'+this.id).modal('hide');
          })
          .catch((error) => {
            console.error(error);
            this.removeLoader();
          });
      },
      loadUsers(){
        this.$http
            .get("/parking/loadUsers?building_id="+this.$gate.building_id)
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
  