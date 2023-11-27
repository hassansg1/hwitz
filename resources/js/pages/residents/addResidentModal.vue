<template>
    <div>
      <div
        class="modal fade"
        :id="'addNewResidentModal' + id"
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
                    <form @submit.prevent="submitForm" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="col-lg-6 mx-auto">
                            <h1 class="h1 mb-13">{{title}}</h1>
                            <alert-error :form="form"></alert-error>

                            <div class="row" v-if="loadedFromParking">
                                <div class="col-12 mb-3">
                                    <label for="firstAndSecondName" class="text-normal-bold-md color-secondary mb-12">
                                        Building
                                    </label>
                                    <div>
                                        <input required type="text" placeholder="Building" class="form-control" v-model="parking_building_id" disabled />
                                        <has-error :form="form" field="building"></has-error>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="lastName" class="text-normal-bold-md color-secondary mb-12">
                                        Unit
                                    </label>
                                    <div>
                                        <input required type="text" id="lastName" placeholder="Last Name" class="form-control" v-model="parking_unit_id" disabled/>
                                        <has-error :form="form" field="lastname"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="multiselect-border multiselect-bold mb-16" v-if="!loadedFromParking">
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
                                        :disabled="form.user_id && form.user_id != '' ? true : false"
                                        @input="loadUnits()"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="device_type"></has-error>
                            </div>

                            <div class="multiselect-border multiselect-bold mb-16" v-if="!loadedFromParking">
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
                                        :disabled="form.user_id && form.user_id != '' ? true : false"
                                        @input="selectUnit"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="unit_id"></has-error>
                            </div>

                            <div class="row">
                                <div class="col-2 mb-3">
                                    <div>
                                        <label for="firstAndSecondName" class="text-normal-bold-md color-secondary mb-12">
                                            Prefix
                                        </label>
                                        <select name="gender" id="" class="form-control" v-model="form.prefix" placeholder="prefix">
                                            <option value="" selected disabled>Prefix</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                        </select>
                                        <has-error :form="form" field="prefix"></has-error>
                                    </div>
                                </div>
                                <div class="col-5 mb-3">
                                    <label for="firstAndSecondName" class="text-normal-bold-md color-secondary mb-12">
                                        First Name
                                    </label>
                                    <div>
                                        <input required type="text" placeholder="First Name" id="firstAndSecondName" class="form-control" v-model="form.firstname" @blur="checkUserExists" :class="{ 'is-invalid': form.errors.has('title') }"/>
                                        <has-error :form="form" field="firstname"></has-error>
                                    </div>
                                </div>
                                <div class="col-5 mb-3">
                                    <label for="lastName" class="text-normal-bold-md color-secondary mb-12">
                                    Last Name
                                    </label>
                                    <div>
                                    <input required type="text" id="lastName" placeholder="Last Name" class="form-control" v-model="form.lastname" @blur="checkUserExists"/>
                                    <has-error :form="form" field="lastname"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    Mobile
                                    </label>
                                    <div>
                                    <input required type="text" id="Mobile" :class="{ 'is-invalid': !isMobileValid }" placeholder="XXX" maxlength="3" class="form-control" v-model="mobile1" @blur="checkStaffMobile"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input required type="text" :class="{ 'is-invalid': !isMobileValid }" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="mobile2" @blur="checkStaffMobile"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input required type="text" :class="{ 'is-invalid': !isMobileValid }" id="Mobile" placeholder="XXXX" maxlength="4" class="form-control" v-model="mobile3" @blur="checkStaffMobile"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="email" class="text-normal-bold-md color-secondary mb-12">
                                    Email
                                    </label>
                                    <div>
                                    <input required type="email" :class="{ 'is-invalid': !isEmailValid }" @blur="checkEmailExists" placeholder="Email" id="email" class="form-control" v-model="form.email"/>
                                    <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                            </div>

                            <!-- TABLE START -->

                            <div class="row" v-if="users && users.length > 0">
                                <div class="col-md-12 mb-3">
                                    <div class="table-responsive mb-28">
                                    <table>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Residency Status</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr class="border_bottom" v-for="(user, index) in users" :key="index">
                                            <td>
                                                <span class="text-medium-md color-secondary">
                                                    {{ user.firstname + " " + user.lastname }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-medium-md color-secondary">
                                                    {{ user.email }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-medium-md color-secondary">
                                                    {{ user.status_delete == 1 ? formatDate(user.modified) : "Current " + user.usertype_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fa fa-plus select-cursor" @click="loadDetails(user,true)" :title="'Load user details'"></i>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <!-- TABLE END -->

                            <div class="multiselect-border multiselect-bold mb-16" v-if="!loadedFromParking">
                                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                    Intercom Position
                                </label>
                                <div class="dropdown-wrapper">
                                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                        v-model="form.intercom"
                                        placeholder=""
                                        label="name"
                                        track-by="id"
                                        :multiple="false"
                                        :options="intercoms"
                                    ></multiselect>
                                </div>
                                <has-error :form="form" field="connection_type"></has-error>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                        Work Phone
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="workphone1" @blur="concatPhone('workphone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="workphone2" @blur="concatPhone('workphone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXXX" maxlength="4" class="form-control" v-model="workphone3" @blur="concatPhone('workphone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    Home Phone
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="home_phone1" @blur="concatPhone('home_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="home_phone2" @blur="concatPhone('home_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXXX" maxlength="4" class="form-control" v-model="home_phone3" @blur="concatPhone('home_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    Other Phone
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="otherphone1" @blur="concatPhone('other_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control" v-model="otherphone2" @blur="concatPhone('other_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                                    .
                                    </label>
                                    <div>
                                    <input  type="text" id="Mobile" placeholder="XXXX" maxlength="4" class="form-control" v-model="otherphone3" @blur="concatPhone('other_phone')"/>
                                    <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="form.showCoGuarantor">
                                <div class="col-12 mb-3">
                                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                                        Make Co-guaranter
                                    </label>
                                <div>
                                    <!-- <input type="checkbox" id="subject" class="form-control" v-model="form.mac_address"/> -->
                                    <input class="form-check-input mt-0 me-12" type="checkbox" value="" v-model="form.coguarantor_id" id="flexCheckDefault"/>
                                </div>
                                <has-error :form="form" field="mac_address"></has-error>
                                </div>
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
    props : ["loadedFromParking","id"],
    components: {
        
    },
    mounted (){
        // $('.mac-address').mask('AA:AA:AA:AA:AA:AA', {
        //     'translation': {
        //         A: {pattern: /[A-Fa-f0-9]/}
        //     }
        // });
    },
    data() {
      return {
        form : new Form({
            user_id: '',
            prefix : '',
            firstname : '',
            lastname : '',
            mobile : '',
            email : '',
            unit_id : '',
            building_id : '',
            work_phone : '',
            other_phone : '',
            home_phone : '',
            coguarantor_id : 0,
            showCoGuarantor : false,
            intercom : '',
            guaranter : '',
            fromParking : false
        }),

        buildings : [],

        units : [],
        users : '',

        mobile1 : '',
        mobile2 : '',
        mobile3 : '',
        workphone1 : '',
        workphone2 : '',
        workphone3 : '',
        home_phone1 : '',
        home_phone2 : '',
        home_phone3 : '',
        otherphone1 : '',
        otherphone2 : '',
        otherphone3 : '',

        intercoms : [],
        isMobileValid : true,
        isEmailValid : true,

        title : 'Add new resident',

        parking_building_id : '',
        parking_unit_id : ''

      };
    },
    methods: {
        checkEmailExists(){
            this.isEmailValid = true;
            if(this.form.email == '') return;
            this.showLoader();
            let params = {
                email : this.form.email
            }
            if(this.form.user_id && this.form.user_id != ''){
                params.bypass_id = this.form.user_id;
            }
            this.$http
                .get("/checkResidentEmailExists" , {params})
                .then((response) => {
                    if(response.data.status == 'warning'){
                        Toast.fire({
                            icon: 'warning',
                            title: response.data.message,
                        });
                        this.isEmailValid = false;
                    }
                    this.removeLoader();
                    this.checkUserExists();
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
                });
        },  
        checkUserExists(){
            if(this.form.user_id && this.form.user_id != '') return;
            this.showLoader();
            let params = {
                firstname : this.form.firstname,
                lastname : this.form.lastname,
                mobile : this.form.mobile,
                email : this.form.email,
                building_id : this.form.building_id ? this.form.building_id.id : '',
                unit_id : this.form.unit_id ? this.form.unit_id.id : ''

            };
            if(params.building_id == '' || params.unit_id == '') {
                this.removeLoader();
                return;
            }
            this.$http
                .get("/searchUsers" , {params})
                .then((response) => {
                    this.users = response.data;
                    this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
                });
        },
        checkStaffMobile () {
            this.isMobileValid = true;
            this.form.mobile =
                this.mobile1 +
                (this.mobile2 ? "-" + this.mobile2 : "") +
                (this.mobile3 ? "-" + this.mobile3 : "");
            if(this.form.mobile == '') return;
            this.showLoader();
            let params = {
                mobile : this.form.mobile
            }
            if(this.form.user_id && this.form.user_id != ''){
                params.bypass_id = this.form.user_id;
            }
            this.$http
                .get("/checkResidentMobileExists" , {params})
                .then((response) => {
                    if(response.data.status == 'warning'){
                        Toast.fire({
                            icon: 'warning',
                            title: response.data.message,
                        });
                        this.isMobileValid = false;
                    }
                    this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
                });
            
        },
        concatPhone(type){
            if(type == 'workphone'){
                this.form.work_phone = this.workphone1 +
                    (this.workphone2 ? "-" + this.workphone2 : "") +
                    (this.workphone3 ? "-" + this.workphone3 : "");
            }else if (type == 'other_phone'){
                this.form.other_phone = this.otherphone1 +
                    (this.otherphone2 ? "-" + this.otherphone2 : "") +
                    (this.otherphone3 ? "-" + this.otherphone3 : "");
            } else if (type == 'home_phone'){
                this.form.home_phone = this.home_phone1 +
                    (this.home_phone2 ? "-" + this.home_phone2 : "") +
                    (this.home_phone3 ? "-" + this.home_phone3 : "");
            }
        },
        submitForm(){
            if(!this.isEmailValid) {Toast.fire({icon: 'warning', title: 'Email already exists'});return;}
            if(!this.isMobileValid) {Toast.fire({ icon: 'warning', title: 'Mobile already exists'});return;}
                
            this.showLoader();
            let building = this.form.building_id;
            let unit = this.form.unit_id;
            let intercom = this.form.intercom;


            this.form.building_id = this.form.building_id ? this.form.building_id.id : '';
            this.form.unit_id = this.form.unit_id ? this.form.unit_id.id : '';
            this.form.intercom = this.form.intercom ? this.form.intercom.id : '';

            this.form
                .post("/api/addResident")
                .then((response) => {

                    if(this.form.user_id && this.form.user_id != ''){
                        Toast.fire({icon: 'success',title: 'Resident details updated successfully.',});
                    }else{
                        Toast.fire({icon: 'success',title: 'Resident added successfully to ' + building.name +'/' +unit.unit_no});
                    }
                    
                    this.$parent.reloadData();
                    $("#addNewResidentModal"+this.id).modal("hide");
                    this.removeLoader();
                })
                .catch((error) => {
                    this.form.unit_id = unit;
                    this.form.building_id = building;
                    this.form.intercom = intercom;
                    // this.form.location = location;
                    this.removeLoader();
                });
        },
        loadDetails(user, flag = false){
            if(!flag){
                let units_users_unit_id = user.units_users_unit_id;

                if(units_users_unit_id) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'This user is already linked with ' + user.building_name + '-' + user.unit_no,
                    });
                    return;
                }
            }
            this.form.email = user.email;
            this.form.firstname = user.firstname;
            this.form.lastname = user.lastname;
            this.form.prefix = user.prefix;

            let mobile = user.mobile.split('-');
            if (mobile.length > 0) {
                this.mobile1 = mobile[0] ? mobile[0] : '';
                this.mobile2 = mobile[1] ? mobile[1] : '';
                this.mobile3 = mobile[2] ? mobile[2] : '';
                this.form.mobile =
                                    this.mobile1 +
                                    (this.mobile2 ? "-" + this.mobile2 : "") +
                                    (this.mobile3 ? "-" + this.mobile3 : "");
            }

        },
        getBuildings(building = false, unit = false) {
            this.$http
                .get("/buildings")
                .then((response) => {
                    this.buildings = response.data.data;
                    if(building) this.form.building_id = building;
                    else this.form.building_id = this.buildings.find(obj => obj.id === this.$gate.building_id);
                    if(unit) 
                    {
                        this.units.push(unit);
                        this.$nextTick(() => {
                            this.form.unit_id = unit;
                        })


                        this.parking_building_id = this.form.building_id.name;
                        this.parking_unit_id = unit.unit_no;
                        
                    }else {
                        this.loadUnits();
                    }
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
        selectUnit(){
            let unit = this.form.unit_id;
            let intercoms = [];

            if(unit.call1 == '') intercoms.push({id : 1 , name : "Position 1"});
            if(unit.call2 == '') intercoms.push({id : 2 , name : "Position 2"});
            if(unit.call3 == '') intercoms.push({id : 3 , name : "Position 3"});

            this.intercoms = intercoms;

            if(unit.guarantor_user_id) {
                this.form.guaranter = unit.guaranter;
                this.form.showCoGuarantor = true;
            }else{
                this.form.guaranter = 1;
                this.form.showCoGuarantor = false;
            }

        }
    },
  };
  </script>

  