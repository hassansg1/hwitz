<template>
    <div>
      <div
        class="modal fade"
        :id="'addNewStorageAndParkingModal' + id"
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
                    <form @submit.prevent="submitForm" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="col-lg-6 mx-auto">
                            <h1 class="h1 mb-13">{{title}}</h1>
                            <alert-error :form="form"></alert-error>

                            <div class="multiselect-border multiselect-bold mb-16">
                                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                    Building
                                </label>
                                <div class="dropdown-wrapper">
                                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                        v-model="form.building_id"
                                        placeholder=""
                                        label="name"
                                        track-by="id"
                                        :multiple="false"
                                        :options="buildings"
                                        @input="loadUnits"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="building"></has-error>
                            </div>

                            <div class="multiselect-border multiselect-bold mb-16">
                                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                    Unit
                                </label>
                                <div class="dropdown-wrapper">
                                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                        v-model="form.unit_id"
                                        placeholder=""
                                        label="unit_no"
                                        track-by="id"
                                        :multiple="false"
                                        :options="units"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="unit_id"></has-error>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button class="btn bkg-success" type="submit">Add</button>
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
    props : ['id'],
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
        }),
        buildings : [],
        units : [],
        title : '',
        parking : 0,
        storage : 0


      };
    },
    methods: {
        submitForm(){
            this.showLoader();
            let unit_id = this.form.unit_id;
            this.form.unit_id = this.form.unit_id ? this.form.unit_id.id : ''
            this.form
                .post('/api/addNewStorageAndParking')
                .then(({ data }) => {
                    Toast.fire({ icon: 'success', title: 'Unit assigned successfully.' });
                    this.$parent.reloadData();
                    $('#addNewStorageAndParkingModal'+this.id).modal('hide');
                    this.removeLoader();
                })
                .catch((error) => {
                    this.form.unit_id = unit_id;
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
                    this.buildings = this.buildings.filter(building => building.type === type);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        loadUnits(){
            let units  = this.form.building_id.units;
            units = units.filter(unit => unit.is_physical == 1);
            units = units.map(unit => {
                if (unit.is_handicapped == 1 && unit.$isDisabled) {
                    return { ...unit, unit_no : unit.unit_no + ' (Handicapped) (Not Available)' };
                }else if(unit.is_handicapped == 0 && unit.$isDisabled){
                    return { ...unit, unit_no : unit.unit_no + ' (Not Available)' };
                }else if(unit.is_handicapped == 1 && !unit.$isDisabled){
                    return { ...unit, unit_no : unit.unit_no + ' (Handicapped)' };
                }
                

                return unit;
            });

            this.units = units;
        }
    },
  };
  </script>

  