<template>
    <div class="storage-section">
      <div
        class="modal fade"
        :id="'vacateResident'+id"
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
                  <form @submit.prevent="vacateResident()" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                      <div class="col-lg-8 mx-auto">
                          <div class="h3 mb-13">Vacate resident</div>
                          <p class="text-normal color-secondary">Select the user(s) you want to vacate</p>
                          <alert-error :form="form"></alert-error>
                        
                          <div class="row mb-20 mx-0">
                            <div class="col-lg-12 col-md-12 col-12" v-for="user in users">
                                <div class="row mx-0 border-bottom">
                                <div class="col-2">
                                    <div class="py-7">
                                    <input class="form-check-input me-12" type="checkbox" :value="user.id" v-model="form.checkedUsers" :id="'flexCheckDefault' + user.id"/>
                                    </div>
                                </div>

                                <div class="col-10">
                                    <div class="d-flex py-7">
                                        <div class="align-self-center avatar-sm me-8">
                                            <img :src=" user.profile_pic && user.profile_pic !== '' ? user.profile_pic : '/images/default-user.jpg'" class="avatar-img" alt="worker"/>
                                        </div>
                                        <div class="align-self-center">
                                            <p class="text-medium-bold mb-0">{{ user.full_name }}</p>
                                            <!-- <p class="text-normal mb-0 color-danger" v-if="user.mobile_verification === 'No'">
                                            Mobile unverified
                                            </p>
                                            <p class="text-normal mb-0 color-danger" v-else-if="user.email_verified === 0">
                                            Unverified
                                            </p>
                                            <p class="text-normal mb-0 color-primary" v-else>Verified</p> -->
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
  
                          <div class="d-flex justify-content-center">
                              <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                                  Cancel
                              </button>
                              <button class="btn bkg-success" type="submit">Vacate</button>
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
            checkedUsers : []
        }),
        users: [],
        visibleItems: [],
        showAll: false,
        building_id : '',
        user : '',
        unit : '',

        checkedUsers : []
      };
    },
    props : ["id"],
    methods: {
      vacateResident(){
        this.showLoader();
        this.form
        .post("/api/vacateResidents/"+this.unit.id)
        .then((response) => {
            this.$parent.loadData();
            Toast.fire({
                icon: 'success',
                title: 'Resident(s) vacated successfully.',
            });
            $('#vacateResident'+this.id).modal('hide');
        })
        .catch((error) => {
            Toast.fire({
                icon: 'warning',
                title: 'Something went wrong. Please try again later.',
            });
            this.removeLoader();
            console.error(error);
        });
      }
    },
  };
  </script>
  