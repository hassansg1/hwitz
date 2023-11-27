<template>
  <div>
    <div
      class="modal fade f-one-invoice"
      id="oneTiemInvoice"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body text-start">
            <form @submit.prevent="sendOneTimeInvoice" @keydown="form.onKeydown($event)">
              <div class="mx-auto row g-3 mb-60">
                <h3 class="mb-16 h3">New one time invoice</h3>

                <div class="row pb-24 gy-3">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Select Biller (Receivable)
                  </p>

                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">User Type</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="receivable_user_type"
                        label="name"
                        track-by="id"
                        :options="receivable_user_types"
                        :multiple="false"
                        placeholder=""
                        @input="getReceivableUserTypes"
                      ></multiselect>
                    </div>
                    <!-- <has-error :form="form" field="payment_type"></has-error> -->
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">User</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.user_id"
                        label="name"
                        track-by="id"
                        :options="receivable_users"
                        :multiple="false"
                        placeholder=""
                      ></multiselect>
                    </div>
                    <!-- <has-error :form="form" field="payment_type"></has-error> -->
                  </div>
                </div>

                <div class="row pb-24 gy-3">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Select Payer Type (Payable)
                  </p>

                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">User Type</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="payable_user_type"
                        label="name"
                        track-by="id"
                        :options="payable_user_types"
                        :multiple="false"
                        placeholder=""
                        @input="getPayableUsers"
                      ></multiselect>
                    </div>
                    <!-- <has-error :form="form" field="payment_type"></has-error> -->
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">User</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.getPayerData"
                        label="name"
                        track-by="id"
                        :options="payable_users"
                        :multiple="false"
                        placeholder=""
                      ></multiselect>
                    </div>
                    <!-- <has-error :form="form" field="payment_type"></has-error> -->
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.cart"
                        label="name"
                        track-by="id"
                        :options="cartTypes"
                        :multiple="false"
                        placeholder=""
                      ></multiselect>
                    </div>
                    <!-- <has-error :form="form" field="payment_type"></has-error> -->
                  </div>
                  <div class="col-xl-7 col-lg-7 col-md-7">
                    <a href="javascript:void(0)">Need to manage users?</a>
                  </div>
                </div>

                <div class="row pb-24 gy-3">
                  <div class="text-start">
                    <div class="table-responsive">
                      <table>
                        <tr>
                          <th>Date of Service</th>
                          <th>Category</th>
                          <th>Description</th>
                          <th>Amount</th>
                          <th>Discount (percent)</th>
                        </tr>
                        <tbody>
                          <tr v-for="(row,index) in addOnRows">
                            <td>
                              <input type="date" v-model="form.date_of_service[index]" class="form-control" required>
                            </td>
                            <td>
                              <!-- <div class="multiselect-border-shadow">
                                <div class="dropdown-wrapper">
                                  <label class="dropdown-label text-uppercase">Category</label>
                                  <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                    v-model="form.category[index]"
                                    label="name"
                                    track-by="id"
                                    :options="categories"
                                    :multiple="false"
                                    placeholder=""
                                    style="z-index: 99999;"
                                  ></multiselect>
                                </div>
                              </div> -->
                              <select class="form-control" v-model="form.category[index]" style="border: 1px solid #cccccc !important;" required>
                                <option value="" selected disabled>Category</option>
                                <option :value="category.name" v-for="category in categories">{{ category.name }}</option>
                              </select>
                            </td>
                            <td>
                              <textarea rows="2" v-model="form.description[index]" class="form-control" style="border : 1px solid #cccccc !important" placeholder="Description" required></textarea>
                            </td>
                            <td>
                              <input type="number" v-model="form.amount[index]" class="form-control" placeholder="Amount" required>
                            </td>
                            <td>
                              <input type="number" v-model="form.discount[index]" class="form-control" placeholder="Discount" required>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="col-lg-3 mt-6">
                        <button type="button" class="btn btn-primary" style="width: unset !important;" @click="addRows">Add</button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
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
      payable_user_types : [],
      receivable_user_types : [],
      receivable_users : [],
      payable_users : [],

      form : new Form({
        user_id : '',
        getPayerData : '',
        cart : '',
        building_id : '',
        date_of_service : [],
        category : [],
        description : [],
        amount : [],
        discount : []
      }),
      receivable_user_type : '',
      payable_user_type : '',
      cartTypes : [
        {id : 2 , name: "Amenities"},
        {id : 3 , name: "Rent"},
        {id : 4 , name: "Laundry"},
      ],
      categories : [],
      addOnRows : 0
    };
  },

  methods : {
    sendOneTimeInvoice(){
      this.showLoader();
      let user_id = this.form.user_id;
      let getPayerData = this.form.getPayerData;
      let cart = this.form.cart;
      this.form.user_id = this.form.user_id ? this.form.user_id.id : 0;
      this.form.getPayerData = this.form.getPayerData ? this.form.getPayerData.id : 0;
      this.form.cart = this.form.cart ? this.form.cart.id : 0;

      this.form
        .post("/api/sendOneTimeInvoice")
        .then(({ data }) => {
          Toast.fire({
            icon: data.status,
            title: data.message,
          });

          this.form.user_id = user_id;
          this.form.getPayerData = getPayerData;
          this.form.cart = cart;

          $("#oneTiemInvoice").modal("hide");
          this.removeLoader();
        })
        .catch((error) => {
          this.form.user_id = user_id;
          this.form.getPayerData = getPayerData;
          this.form.cart = cart;
          this.removeLoader();
        });
    },
    validateAndPrepareData(){

    },
    addRows(){
      this.addOnRows = this.addOnRows + 1;
    },
    getReceivableUserTypes(){
      let receivable_user_type = this.receivable_user_type ? this.receivable_user_type.id : 0;
      this.showLoader();
      this.$http
        .get("/getUsersOfABuildingByUserType/"+ this.form.building_id +"/"+receivable_user_type)
        .then((response) => {
          this.receivable_users = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    },
    getPayableUsers(){
      let payable_user_type = this.payable_user_type ? this.payable_user_type.id : 0;
      this.showLoader();
      this.$http
        .get("/getPayer/"+this.form.building_id+"/"+payable_user_type)
        .then((response) => {
          this.payable_users = response.data
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    },
    getReceivableAndPayableUserTypes(){
      this.showLoader();
      this.$http
        .get("/getUserTypesForReceivablesAndPayables")
        .then((response) => {
          console.log("res",response)
          this.payable_user_types = response.data.payable_user_type;
          this.receivable_user_types = response.data.receivable_user_types;
          this.categories = response.data.categories;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    }
  }
};
</script>
