<template>
  <div class="add-staff-user">
    <div class="card shadow px-4 py-5">
      <div class="row">
        <div class="col-md-12 mb-5 mb-md-0">
          <form @submit.prevent="addUser" @keydown="form.onKeydown($event)">
            <alert-error :form="form"></alert-error>
            <!-- <h3 class="h3 mb-16">Profile information</h3> -->
            <div class="row">
              <div class="col-2 mb-3">
                <div>
                  <label
                      for="firstAndSecondName"
                      class="text-normal-bold-md color-secondary mb-12"
                  >
                    Prefix
                  </label>
                  <select
                      name="gender"
                      id=""
                      class="form-control"
                      v-model="form.prefix"
                      placeholder="prefix"
                  >
                    <option value="" selected disabled>Prefix</option>
                    <option value="Mr.">Mr.</option>
                    <option value="Mrs.">Mrs.</option>
                    <option value="Ms.">Ms.</option>
                  </select>
                </div>
              </div>
              <div class="col-5 mb-3">
                <label
                    for="firstAndSecondName"
                    class="text-normal-bold-md color-secondary mb-12"
                >
                  First Name
                </label>
                <div>
                  <input
                      required
                      type="text"
                      placeholder="First Name"
                      id="firstAndSecondName"
                      class="form-control"
                      v-model="form.firstname"
                      @blur="checkUserExists"
                      :class="{ 'is-invalid': form.errors.has('title') }"
                  />
                  <has-error :form="form" field="firstname"></has-error>
                </div>
              </div>
              <div class="col-5 mb-3">
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
                      placeholder="Last Name"
                      class="form-control"
                      v-model="form.lastname"
                      @blur="checkUserExists"
                  />
                  <has-error :form="form" field="lastname"></has-error>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2 mb-3">
                <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                  Mobile
                </label>
                <div>
                  <input required type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control"
                         v-model="mobile1" @blur="checkStaffMobile"/>
                  <has-error :form="form" field="mobile"></has-error>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                  .
                </label>
                <div>
                  <input required type="text" id="Mobile" placeholder="XXX" maxlength="3" class="form-control"
                         v-model="mobile2" @blur="checkStaffMobile"/>
                  <has-error :form="form" field="mobile"></has-error>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <label for="mobile" class="text-normal-bold-md color-secondary mb-12">
                  .
                </label>
                <div>
                  <input required type="text" id="Mobile" placeholder="XXXX" maxlength="4" class="form-control"
                         v-model="mobile3" @blur="checkStaffMobile"/>
                  <has-error :form="form" field="mobile"></has-error>
                </div>
              </div>
              <div class="col-6 mb-3">
                <label for="email" class="text-normal-bold-md color-secondary mb-12">
                  Email
                </label>
                <div>
                  <input required type="email" @blur="checkUserExists" placeholder="Email" id="email"
                         class="form-control" v-model="form.email"/>
                  <has-error :form="form" field="email"></has-error>
                </div>
              </div>
            </div>

            <div class="row" v-if="users && users.length > 0">
              <div class="col-md-12 mb-3">
                <div class="table-responsive mb-28">
                  <table>
                    <tr>
                      <th>#</th>
                      <th>Name of user</th>
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    <tr
                        class="border_bottom"
                        v-for="(user, index) in users"
                        :key="index"
                    >
                      <td>
                        <span class="text-medium-md color-secondary">{{
                            index + 1
                          }}</span>
                      </td>
                      <td>
                        <span class="text-medium-md color-secondary">
                          {{ user.firstname + " " + user.lastname }}
                        </span>
                      </td>
                      <td>
                        <span class="text-medium-md color-secondary">
                          {{ user.mobile }}
                        </span>
                      </td>
                      <td>
                        <span class="text-medium-md color-secondary">
                          {{ user.email }}
                        </span>
                      </td>
                      <td>
                        <span class="text-medium-md color-secondary">
                          {{ user.status == 1 ? "Active" : "Inactive" }}
                        </span>
                      </td>
                      <td>
                        <router-link
                            class="text-medium-md color-primary border-bottom"
                            to="/"
                        >
                          Edit
                        </router-link>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row">
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
                      placeholder="DOB"
                      id="dateOfBirth"
                      class="form-control"
                      v-model="form.dob"
                  />
                  <has-error :form="form" field="dob"></has-error>
                </div>
              </div>
              <div class="col-6 mb-3">
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
                      placeholder="Address"
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
                      placeholder="City"
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
                      placeholder="State"
                      class="form-control"
                      v-model="form.state"
                  />
                  <has-error :form="form" field="state"></has-error>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
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
                      placeholder="Zip code"
                      class="form-control"
                      v-model="form.zipcode"
                  />
                  <has-error :form="form" field="zipcode"></has-error>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div>
                  <label
                      for="firstAndSecondName"
                      class="text-normal-bold-md color-secondary mb-12"
                  >
                    Type
                  </label>
                  <select
                      name="gender"
                      id=""
                      class="form-control"
                      v-model="form.usertype"
                  >
                    <option value="" selected disabled>Select Type</option>
                    <option :value="usertype.id" v-for="usertype in usertypes">
                      {{ usertype.name }}
                    </option>
                  </select>
                  <has-error :form="form" field="usertype"></has-error>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 multiselect-border">
                <div class="dropdown-wrapper">
                  <label class="dropdown-label text-capitalize">Building</label>
                  <multiselect
                      :selectLabel="''"
                      :deselectLabel="''"
                      v-model="form.building"
                      placeholder=""
                      label="name"
                      track-by="id"
                      :options="buildings"
                      :multiple="true"
                  ></multiselect>
                </div>
                <has-error :form="form" field="building"></has-error>
                <span class="text-normal-bold-md color-secondary form-error"
                >Note : You will be able to assign FOB after adding this
                  user.</span
                >
              </div>
              <div class="col-md-6">

                <label class="color-secondary mb-12" type="button">
                  <img
                      src="/images/icons/attachment.png"
                      class="me-2"
                      alt=""
                  />
                  <input
                      type="file"
                      id="myFile"
                      name="filename"
                      class="d-none"
                      @change="attachAFile"
                  />
                  Add Profile Picture
                </label>
              </div>
            </div>

            <div id="p-detail" class="d-flex justify-content-end mt-40">
              <a :to="'/staff'" class="btn btn-dark mb-2 me-1 px-4 btn-size"
              >Cancel</a
              >
              <button class="btn btn-dark mb-2 ms-1 px-4 btn-size">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- urgent-task data-->
  </div>
</template>

<script>
import axios from "axios";
import {Form} from "vform";

export default {
  props: ["parentPage"],
  data() {
    return {
      user: "",
      form: new Form({
        prefix: "",
        firstname: "",
        lastname: "",
        mobile: "",
        dob: "",
        address1: "",
        city: "",
        state: "",
        zipcode: "",
        email: "",
        building: "",
        usertype: "",
        profile_picture: ""
      }),
      usertypes: [],
      buildings: [],
      users: [],
      mobile1: "",
      mobile2: "",
      mobile3: "",
      emailValid: true,
      emailMsg: "",
      mobileValid: true,
      mobileMsg: "",
    };
  },
  components: {},
  methods: {
    attachAFile() {
      let file = event.target.files[0];
      let self = this;

      if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          const base64Image = e.target.result;
          self.form.profile_picture = base64Image;
          console.log(self.form.profile_picture)
        };

        reader.readAsDataURL(file);


      }
    },
    addUser() {
      if (!this.emailValid) {
        Toast.fire({icon: "warning", title: this.emailMsg});
        return;
      }
      if (!this.mobileValid) {
        Toast.fire({icon: "warning", title: this.mobileMsg});
        return;
      }

      console.log(this.form.building);
      this.form.mobile = this.mobile1 + "-" + this.mobile2 + "-" + this.mobile3;
      this.showLoader();
      this.form
          .post("/api/addStaffUser")
          .then(({data}) => {
            if (this.parentPage === 'manage_users') {
              Toast.fire({
                icon: "success",
                title: "User Added Successfully.",
              });
              this.$parent.loadData();
            } else {
              Toast.fire({
                icon: "success",
                title: "Staff User Added Successfully.",
              });
              this.$router.push("/staff");
            }
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
    },
    getUserTypes() {
      this.$http
          .get("/getUserTypes/staff")
          .then((response) => {
            this.usertypes = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    checkStaffMobile() {
      this.form.mobile =
          this.mobile1 +
          (this.mobile2 ? "-" + this.mobile2 : "") +
          (this.mobile3 ? "-" + this.mobile3 : "");
      let params = {
        mobile: this.form.mobile,
        building_id: this.$route.params.id,
      };
      this.$http
          .post("/checkStaffMobile", params)
          .then((response) => {
            if (response.data.status == 1 || response.data.status == 2) {
              this.mobileValid = false;
              this.mobileMsg = response.data.msg;
              Toast.fire({
                icon: "warning",
                title: this.mobileMsg,
              });
            } else {
              this.mobileValid = true;
              this.mobileMsg = "";
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
        building_id: this.$route.params.id,
      };
      this.$http
          .post("/checkStaffEmail", params)
          .then((response) => {
            console.log(response);
            if (response.data.status == 1 || response.data.status == 2) {
              this.emailValid = false;
              this.emailMsg = response.data.msg;
              Toast.fire({
                icon: "warning",
                title: this.emailMsg,
              });
            } else {
              this.emailValid = true;
              this.emailMsg = "";
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    checkUserExists() {
      this.form.mobile =
          this.mobile1 +
          (this.mobile2 ? "-" + this.mobile2 : "") +
          (this.mobile3 ? "-" + this.mobile3 : "");
      let params = {
        mobile: this.form.mobile,
        firstname: this.form.firstname,
        lastname: this.form.lastname,
        email: this.form.email,
        building_id: this.$route.params.id,
      };
      this.$http
          .post("/checkUserExists", params)
          .then((response) => {
            console.log(response);
            this.users = response.data.users;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    formatMobileNumber(number) {
      // Remove any non-digit characters from the input
      const digitsOnly = number.replace(/\D/g, "");

      // Apply the format: XXX-XXX-XXXX
      const formattedNumber = digitsOnly.replace(
          /(\d{3})(\d{3})(\d{4})/,
          "$1-$2-$3"
      );

      return formattedNumber;
    },
  },
  created() {
    this.getBuildings();
    this.getUserTypes();
  },
  mounted() {
  },
};
</script>
