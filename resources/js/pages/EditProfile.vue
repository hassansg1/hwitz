<template>
  <div class="edit-profile">
    <div class="card shadow px-4 py-5">
      <div class="row gap-3">
        <div class="col-lg-12">
          <div class="d-flex flex-column flex-lg-row">
            <div class="d-inline mx-auto mx-lg-0">
              <div class="position-relative text-center me-45">
                <div class="profile-picture">
                  <img :src="user.profile_picture" class="profile-img"/>
                </div>
                <span class="verifiyIcon" v-if="user.email_verified">
                  <svg
                      class=""
                      xmlns="http://www.w3.org/2000/svg"
                      width="38"
                      height="38"
                      viewBox="0 0 38 38"
                      fill="none"
                  >
                    <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M24.9228 14.761C25.2285 15.0455 25.2367 15.5148 24.9413 15.8091L17.5005 23.2214C17.3536 23.3677 17.1507 23.4495 16.9393 23.4474C16.728 23.4454 16.5268 23.3598 16.383 23.2106L13.0475 19.7515C12.7582 19.4516 12.7762 18.9826 13.0878 18.704C13.3993 18.4255 13.8863 18.4428 14.1756 18.7428L16.9583 21.6286L23.8344 14.7788C24.1299 14.4845 24.6172 14.4765 24.9228 14.761Z"
                        fill="black"
                    />
                  </svg>
                </span>
              </div>
            </div>

            <div class="user-profile-name">
              <div class="d-flex mb-36">
                <div class="me-6">
                  <h3 class="h3 mb-0">
                    {{ form.firstname }} {{ form.lastname }}
                  </h3>
                  <div class="color-primary text-normal">
                    <span>{{ form.mobile }} </span>
                    <span>|</span>
                    <span>{{ user.usertype ? user.usertype.name : "-" }}</span>
                  </div>
                </div>

                <i class="fa fa-ellipsis-h color-secondary select-cursor" style="padding-top: 7px;padding-right:5px"
                   id="unitDropdown" data-bs-toggle="dropdown" aria-expanded="false"></i>
                <div class="dropdown">
                  <ul
                      class="dropdown-menu dropdown-menu-end border-standered"
                      aria-labelledby="unitDropdown"
                  >
                    <li
                        class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Action
                    </li>

                    <li v-for="linkedUnit1 in user.units"
                        class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor"
                        @click="unlinkLinkedUnit(user.id,linkedUnit1.id)">
                      Unlink from {{ linkedUnit1.building_name }} / {{ linkedUnit1.unit_no }}
                    </li>
                    <li v-for="linkedUnit1 in user.units"
                        class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor"
                        @click="takeMe(linkedUnit1)">
                      Take me to {{ linkedUnit1.building_name }} / {{ linkedUnit1.unit_no }}
                    </li>
                    <li
                        class="color-dark px-2 py-1 border-bottom text-sm-bold select-cursor"
                        @click="linkToAnotherUnit(user.id)"
                    >
                      Link to another unit
                    </li>
                  </ul>
                </div>


                <!-- <div class="me-69">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 18 18"
                    fill="none"
                  >
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M11.7297 1.48979C12.3635 0.856039 13.223 0.5 14.1193 0.5C15.9857 0.5 17.4987 2.01299 17.4987 3.87936C17.4987 4.77562 17.1426 5.63518 16.5089 6.26893L5.48659 17.2912C5.35376 17.4241 5.17361 17.4987 4.98576 17.4987H1.20828C0.817107 17.4987 0.5 17.1816 0.5 16.7904V13.0129C0.5 12.8251 0.574622 12.6449 0.70745 12.5121L11.7297 1.48979ZM14.1193 1.91656C13.5987 1.91656 13.0995 2.12335 12.7314 2.49145L12.3619 2.86093L15.1377 5.63675L15.5072 5.26727C15.8753 4.89918 16.0821 4.39993 16.0821 3.87936C16.0821 2.79533 15.2033 1.91656 14.1193 1.91656ZM14.1361 6.63841L11.3603 3.86258L1.91656 13.3063V16.0821H4.69238L14.1361 6.63841Z"
                      fill="#999999"
                    />
                  </svg>
                </div> -->
              </div>

              <div class="mb-12">
                <p class="mb-0 text-normal-bold-md color-secondary">Email</p>
                <p class="mb-0 text-normal">{{ form.email }}</p>
              </div>
              <div class="mb-12">
                <p class="mb-0 text-normal-bold-md color-secondary">
                  Date of Birth
                </p>

                <p class="text-normal">{{ form.dob }}</p>
              </div>
              <div class="mb-12">
                <div class="text-normal" v-for="unit in user.units">
                  This user is linked with {{ unit.building_name }}/{{ unit.unit_no }}
                </div>
              </div>
            </div>

            <div class="d-grid d-lg-block ms-lg-auto">
              <label class="btn btn-dark px-4" type="button">
                <input
                    type="file"
                    id="myFile"
                    name="filename"
                    class="d-none"
                    @change="updateProfilePicture"
                />
                Change Photo
              </label>
            </div>
          </div>
        </div>
      </div>
      <hr class="mb-40"/>
      <div class="row">
        <div class="col-md-6 mb-5 mb-md-0">
          <form @submit.prevent="updateUser" @keydown="form.onKeydown($event)">
            <alert-error :form="form"></alert-error>
            <h3 class="h3 mb-16">Profile information</h3>
            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="firstAndSecondName"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  First and Second Name
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="firstAndSecondName"
                      class="form-control"
                      v-model="form.firstname"
                      :class="{ 'is-invalid': form.errors.has('title') }"
                  />
                  <has-error :form="form" field="firstname"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="lastName"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  Last Name
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="lastName"
                      class="form-control"
                      v-model="form.lastname"
                  />
                  <has-error :form="form" field="lastname"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                  Mobile
                  <i>
                    <span v-if="user.mobile_verification == 'No'" style="color:red;padding:5px">
                      <a :href="mobileVerificationLink" target="_blank" style="color: red !important">(Verify Mobile no.)</a>
                    </span>
                    <span v-else style="color:green;padding:5px">(Verified)</span>
                  </i>
                </label>
                <div>
                  <input required type="text" id="Mobile" class="form-control" v-model="form.mobile" @blur="checkStaffMobile" />
                  <has-error :form="form" field="mobile"></has-error>
                  
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label
                    for="dateOfBirth"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  Date of Birth
                </label>
                <div>
                  <input
                      required
                      type="date"
                      id="dateOfBirth"
                      class="form-control"
                      v-model="form.dob"
                  />
                  <has-error :form="form" field="dob"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="address"
                    class="text-normal-bold-md color-secondarymb-12"
                >
                  Address
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="address"
                      class="form-control"
                      v-model="form.address1"
                  />
                  <has-error :form="form" field="address1"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label
                    for="city"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  City
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="city"
                      class="form-control"
                      v-model="form.city"
                  />
                  <has-error :form="form" field="city"></has-error>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label
                    for="state"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  State
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="state"
                      class="form-control"
                      v-model="form.state"
                  />
                  <has-error :form="form" field="state"></has-error>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label
                    for="zipCode"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  Zip code
                </label>
                <div>
                  <input
                      required
                      type="text"
                      id="zipCode"
                      class="form-control"
                      v-model="form.zipcode"
                  />
                  <has-error :form="form" field="zipcode"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 mb-3">
                <label for="email" class="text-normal-bold-md color-secondary mb-12">
                  Email
                  <i>
                    <span v-if="user.email_verified == 1" style="color:green;padding:5px">(Verified)</span>
                    <span v-else style="padding:5px">
                      <a :href="emailVerificationLink" style="color:red !important;" target="_blank">(Verify Email)</a>
                    </span>
                  </i>
                </label>
                <div>
                  <input required type="email" id="email" class="form-control" v-model="form.email" v-if="$gate.permissions.includes('edit_personal_email')" @blur="checkStaffEmail" />
                  <input required type="email" id="email" class="form-control cursor-not-allowed" v-model="form.email" v-else readonly />
                  <has-error :form="form" field="email"></has-error>
                </div>
              </div>
            </div>

            <div id="p-detail" class="d-flex justify-content-end mt-40">
              <button class="btn btn-dark mb-2 ms-1 px-4">Save</button>
            </div>
          </form>
        </div>

        <div class="col-md-6">
          <h3 class="h3 mb-16">Change Password</h3>

          <form
              @submit.prevent="changePassword"
              @keydown="passwordForm.onKeydown($event)"
          >
            <alert-error :form="passwordForm"></alert-error>
            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="currentPassword"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  Current Password
                </label>
                <div>
                  <input
                      required
                      type="password"
                      id="currentPassword"
                      class="form-control"
                      v-model="passwordForm.password"
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="newPassword"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  New Password
                </label>
                <div>
                  <input
                      required
                      type="password"
                      id="newPassword"
                      class="form-control"
                      v-model="passwordForm.new_password"
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label
                    for="confirmPassword"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  Confirm Password
                </label>
                <div>
                  <input
                      required
                      type="password"
                      id="confirmPassword"
                      class="form-control"
                      v-model="passwordForm.confirm_password"
                  />
                </div>
              </div>
            </div>

            <div id="p-password" class="row mt-40">
              <div class="col-12 d-flex flex-row-reverse">
                <button class="btn btn-dark px-4" type="submit">Save</button>
              </div>
            </div>
          </form>

          <div class="row">
            <div class="col-12">
              <h4 class="profile-info">Agreement</h4>
              <div v-if="user.accpeted_eula_version">
                <span class="color-secondary">Eula Version : </span>
                <a :href="eulaPath" target="_blank">{{
                    user.accpeted_eula_version
                  }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <linkToAnotherUnit ref="linkToAnotherUnit"/>
    <!-- urgent-task data-->
  </div>
</template>

<script>
import axios from "axios";
import { Form } from "vform";
import linkToAnotherUnit from "../components/units/linkToAnotherUnit.vue";

import config from "../config.js";

const CryptoJS = require("crypto-js");

export default {
  data() {
    return {
      eulaPath: "",
      user: "",
      form: new Form({
        firstname: "",
        lastname: "",
        mobile: "",
        dob: "",
        address1: "",
        city: "",
        state: "",
        zipcode: "",
        email: "",
        profile_picture: "",
      }),
      
      passwordForm: new Form({
        password: "",
        new_password: "",
        confirm_password: "",
      }),

      mobileValid : true,
      mobileMsg : '',
      emailValid : true,
      emailMsg : '',
      mobileVerificationLink : '',
      emailVerificationLink : ''
    };
  },
  components: {linkToAnotherUnit},
  methods: {
    checkStaffMobile() {
      this.showLoader();
      let params = {
        mobile: this.form.mobile,
        building_id: '',
        bypass_id: this.user.id,
      };
      this.$http
          .post("/checkStaffMobile", params)
          .then((response) => {
            if (response.data.status == 1 || response.data.status == 2) {
              this.mobileValid = false;
              this.mobileMsg = response.data.msg;
              // Toast.fire({
              //   icon: "warning",
              //   title: this.mobileMsg,
              // });
              this.form.errors.set( {mobile : this.mobileMsg})
            } else {
              this.mobileValid = true;
              this.mobileMsg = "";
              this.form.errors.set( {mobile : this.mobileMsg})
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    checkStaffEmail() {
      let params = {
        email: this.form.email,
        building_id: '',
        bypass_id: this.user.id,
      };
      this.$http
          .post("/checkStaffEmail", params)
          .then((response) => {
            console.log(response);
            if (response.data.status == 1 || response.data.status == 2) {
              this.emailValid = false;
              this.emailMsg = response.data.msg;
              // Toast.fire({
              //   icon: "warning",
              //   title: this.emailMsg,
              // });
              this.form.errors.set( {email : this.emailMsg})
            } else {
              this.emailValid = true;
              this.emailMsg = "";
              this.form.errors.set( {email : this.emailMsg})
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    reloadData(){
      this.fetchUser();
    },
    linkToAnotherUnit(user_id) {
      this.$refs.linkToAnotherUnit.form.reset();
      this.$refs.linkToAnotherUnit.form.user_id = user_id;
      this.$refs.linkToAnotherUnit.getBuildings();
      $('#linkToAnotherUnit').modal('show');
    },
    unlinkLinkedUnit(user_id, unit_id) {
      // console.log(unit_id);return;
      this.showLoader();
      this.$http
          .get("/un_link_unit/" + unit_id + '/' + user_id)
          .then((response) => {
            response = response.data;
            if (response.status == 'success') {
              Toast.fire({
                icon: "success",
                position: "top",
                title: "The user has been successfully vacated.",
              });
              this.fetchUser();
            } else {
              Toast.fire({
                icon: "error",
                title: response.message ?? "Something went wrong",
              });
            }
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    takeMe(unit) {
      this.$emit('setBuildingInSession', unit.building_id);
      this.$nextTick(() => {
        window.location = '/unit-detail/' + unit.id;
      });

    },
    changePassword() {
      if (
          this.passwordForm.new_password != this.passwordForm.confirm_password
      ) {
        Toast.fire({
          icon: "error",
          title: "Password and confirm password fields do not match",
        });
        return;
      }

      this.showLoader();
      this.passwordForm
          .post("/api/change-password")
          .then(({data}) => {
            this.removeLoader();
            if (data.code == 1) {
              Toast.fire({
                icon: "success",
                title: data.message,
              });
              this.passwordForm.reset();
            } else {
              Toast.fire({
                icon: "error",
                title: data.message,
              });
            }
          })
          .catch((error) => {
            this.removeLoader();
          });
    },
    fetchUser() {
      this.getEula();
      this.showLoader();

      this.$http
        .get("/user")
        .then((response) => {
          response = response.data;
          this.user = response;
          this.$emit('updateGateUser',this.user);
          this.form.firstname = response.firstname;
          this.form.lastname = response.lastname;
          this.form.mobile = response.mobile;
          this.form.dob = response.dob;
          this.form.address1 = response.address1;
          this.form.city = response.city;
          this.form.state = response.state;
          this.form.zipcode = response.zipcode;
          this.form.email = response.email;
          this.form.profile_picture = response.profile_picture;
          

          this.mobileVerificationLink =
                config.config.attendPortalUrl +
                "mobileVerification/" +
                CryptoJS.MD5(this.user.email).toString() +
                "?redirect_url=" +
                config.config.baseUrl +
                "/edit-profile";

          this.emailVerificationLink =
              config.config.attendPortalUrl +
              "emailVerification/" +
              CryptoJS.MD5(this.user.email).toString() +
              "?redirect_url=" +
              config.config.baseUrl +
              "/edit-profile";
          $('#Mobile').mask('000-000-0000');    
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getEula() {

      this.$http
          .get("/userEula")
          .then((response) => {
            response = response.data;
            this.eulaPath = response.data;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    updateUser() {

      if (!this.emailValid) {
        Toast.fire({icon: "warning", title: this.emailMsg});
        return;
      }
      if (!this.mobileValid) {
        Toast.fire({icon: "warning", title: this.mobileMsg});
        return;
      }
      this.showLoader();
      this.form
          .put("/api/user/" + this.user.id)
          .then(({data}) => {
            Toast.fire({
              icon: "success",
              title: "Profile Updated Successfully.",
            });
            this.fetchUser();
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
    },
    updateProfilePicture(event) {
      let file = event.target.files[0];

      const reader = new FileReader();

      reader.onloadend = () => {
        const profile_pic = reader.result;
        const formData = new FormData();
        formData.append("profilePicture", profile_pic);

        console.log(file);

        this.$http
            .post("/user/profile_picture/" + this.user.id, formData)
            .then(({data}) => {
              Toast.fire({
                icon: "success",
                title: "Profile Picture Updated Successfully.",
              });
              this.fetchUser();
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
            });
      };

      reader.readAsDataURL(file);
    },
  },
  created() {
    this.fetchUser();
  },
  mounted() {
    
  },
};
</script>
