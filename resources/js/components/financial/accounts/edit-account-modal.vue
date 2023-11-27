<template>
  <div>
    <div
      class="modal fade f-accounts-modal"
      :id="'editAccount'+id"
      tabindex="-1"
      aria-labelledby="editAccountLabel"
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
            <div>
              <h3 class="h3 mb-16">{{title}}</h3>
            </div>
            <form @submit.prevent="addWallet" @keydown="form.onKeydown($event)">
              <span class="panel-default"></span>
              <alert-error :form="form"></alert-error>
              <div class="row pb-24 gy-3">
                <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                  Type of payment
                </p>

                <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-uppercase"
                      >Payment Type</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="form.payment_type"
                      label="name"
                      track-by="id"
                      :options="paymentTypeOptions"
                      :multiple="false"
                        placeholder=""
                    ></multiselect>
                  </div>
                  <has-error :form="form" field="payment_type"></has-error>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="name@example.com"
                      v-model="form.nick_name"
                    />
                    <label for="">Nick Name</label>
                  </div>
                  <has-error :form="form" field="nick_name"></has-error>
                </div>
              </div>
              <div class="row pb-24 gy-3">
                <template v-if="form.payment_type && form.payment_type.id == 1">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Account Details
                    <img src="/images/visa.png" class="me-8" alt="card" v-if="form.card_type != '' && form.card_type == 4" />
                    <img src="/images/master.png" class="me-8" alt="card" v-if="form.card_type != '' && form.card_type == 5" />
                  </p>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" :id="'card_number'+id" v-model="form.card_number" @keyup="cardNumberKeyup" />
                      <label for="">Card Number</label>
                    </div>
                    <has-error :form="form" field="card_number"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" id="" v-model="form.card_cvv" maxlength="3"/>
                      <label for="">Card Cvv</label>
                    </div>
                    <has-error :form="form" field="card_cvv"></has-error>
                  </div>
                  <!-- <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.card_type"/>
                      <label for="">Card Type</label>
                    </div>
                    <has-error :form="form" field="card_type"></has-error>
                  </div> -->
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.name_on_card"/>
                      <label for="">Name on account</label>
                    </div>
                    <has-error :form="form" field="name_on_card"></has-error>
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-2 col-md-3">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">Expiration Month</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.month"
                        label="name"
                        track-by="id"
                        :options="months"
                        :multiple="false"
                        placeholder=""
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="month"></has-error>
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-2 col-md-3">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">Expiration Year</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.year"
                        label="name"
                        track-by="id"
                        :options="years"
                        :multiple="false"
                        placeholder=""
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="year"></has-error>
                  </div>
                </template>
                <template v-else>
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Account Details
                  </p>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="bnk_routing_no" maxlength="9" v-model="form.bnk_routing_no"/>
                      <label for="">Bank Routing Number</label>
                    </div>
                    <has-error :form="form" field="firstname"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="bnk_acc_no" maxlength="15" v-model="form.bnk_acc_no"/>
                      <label for="">Bank Account Number</label>
                    </div>
                    <has-error :form="form" field="firstname"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="name_on_card" v-model="form.name_on_card"/>
                      <label for="">Name of account</label>
                    </div>
                    <has-error :form="form" field="firstname"></has-error>
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">Check holder type</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.check_holder_type"
                        label="name"
                        track-by="code"
                        :options="checkHolderTypes"
                        placeholder=""
                        :multiple="false"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="check_holder_type"></has-error>
                  </div>
                  <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">Account type</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.check_type"
                        label="name"
                        track-by="code"
                        :options="accountTypes"
                        placeholder=""
                        :multiple="false"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="check_type"></has-error> 
                  </div>
                </template>
                <div class="row pb-24 gy-3">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Personal Detail
                  </p>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.email" disabled/>
                      <label for="">Email</label>
                    </div>
                    <has-error :form="form" field="email"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.mobileno"/>
                      <label for="">Mobile Number</label>
                    </div>
                    <has-error :form="form" field="mobileno"></has-error>
                  </div>
                </div>
                <div class="row mb-24 gy-3">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Address Detail
                  </p>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.address1" />
                      <label for="">Building Address</label>
                    </div>
                    <has-error :form="form" field="address1"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.city"/>
                      <label for="">City</label>
                    </div>
                    <has-error :form="form" field="city"></has-error>
                  </div>
                  <div
                    class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6"
                  >
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-uppercase">State</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="form.state"
                        label="name"
                        track-by="code"
                        :options="states"
                        :multiple="false"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="state"></has-error>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="" v-model="form.zipcode"/>
                      <label for="">Zip code</label>
                    </div>
                    <has-error :form="form" field="zipcode"></has-error>
                  </div>
                </div>
                <div class="row mb-24 gy-3">
                  <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                    Make This A "default" Payment Source For The Following Carts:
                  </p>
                  <div class="col-xl-12 col-lg-12 col-md-12" v-if="form.payment_type && form.payment_type.id == 1">
                      <input  class="form-check-input me-12" type="checkbox" value="default_laundry" v-model="form.default_laundry">
                      <label for="defaultLaundry">Laundry</label>
                      <br>
                      <table class="table table-bordered mt-6">
                        <tbody>
                          <tr>
                            <th>Cart Type</th>
                            <th title="checking this box will set up this payment source as a default for the following carts">
                                Default
                            </th>
                            <th title="only recurring payments are paid automatically each month">AutoPay</th>
                          </tr>
                          <tr>
                            <td><label for="">Amenities</label></td>
                            <td><input id="" class="form-check-input me-12" type="checkbox" value="default_amenities" v-model="form.default_amenities">
                            </td>
                            <td><input id="" class="form-check-input me-12" type="checkbox" value="autopay_amenities" v-model="form.autopay_amenities"></td>
                          </tr>
                          <tr>
                            <td><label for="defaultAmenities">Rent</label></td>
                            <td><input id="" class="form-check-input me-12" type="checkbox" value="default_rent" v-model="form.default_rent"></td>
                            <td><input id="autopay_rent" class="form-check-input me-12" type="checkbox" value="autopay_rent" v-model="form.autopay_rent"></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12" v-else>
                    <input  type="checkbox" class="form-check-input me-12" value="default_laundry" v-model="form.default_laundry">
                    <label for="defaultLaundry">Laundry</label>
                    <br>
                      
                    <input id="" type="checkbox" class="form-check-input me-12" value="default_amenities" v-model="form.default_amenities">
                    <label for="defaultAmenities">Amenities</label>
                    <br>
                    <input id="" type="checkbox" class="form-check-input me-12" value="default_rent" v-model="form.default_rent">
                    <label for="defaultRent">Rent</label>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <button class="btn btn-dark me-20" data-bs-dismiss="modal">Cancel</button>
                  <button class="btn btn-primary">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <otpModal ref="otpModal" :id="id"/>
  </div>
</template>
<script>
import otpModal from './otpModal.vue';
export default {
  components: {otpModal},
  props : ['id'],
  mounted(){  
    this.states = Object.entries(this.usStates()).map(([id, name]) => ({ id, name }));
    this.form.state = this.states.find(item => item.id === this.$gate.user.state);
  },
  data() {
    return {
      title : 'Edit Account',
      editMode : false,
      wallet : '',
      paymentTypeOptions : [
        {id: 1 , name : 'Credit Card' },
        {id: 2 , name : 'Cheque' },
      ],
      cartTypeOptions : [
        {id: 'default_rent' , name : 'Rent' },
        {id: 'default_amenities' , name : 'Default Amenities' },
        {id: 'default_laundry' , name : 'Laundry' },
      ],
      states : [],
      checkHolderTypes : [{id : 1 , name : 'Personal'},{id : 2 , name : 'Business' }],
      accountTypes : [{id : 1 , name : 'Checking'},{id : 2 , name : 'Saving' }],
      months : [
        {id: 1,name:1},
        {id:2,name:2},
        {id:3,name:3},
        {id:4,name:4},
        {id:5,name:5},
        {id:6,name:6},
        {id:7,name:7},
        {id:8,name:8},
        {id:9,name:9},
        {id:10,name:10},
        {id:11,name:11},
        {id:12,name:12},
      ],
      years : this.getNext20Years(),

      form : new Form({
        payment_type : {id: 1 , name : 'Credit Card' },
        nick_name : '',
        card_type : '',
        card_number : '',
        name_on_card : '',
        month : '',
        year : '',
        card_cvv : '',
        email : this.$gate.user.email,
        address1 : this.$gate.user.address1,
        address2 : this.$gate.user.address1,
        city : this.$gate.user.city,
        state : '',
        zipcode : this.$gate.user.zipcode,
        mobileno : this.$gate.user.mobile,
        default_laundry : '',
        default_amenities : '',
        default_rent : '',
        autopay_amenities : '',
        autopay_rent : '',
        bnk_routing_no : '',
        bnk_acc_no : '',
        check_holder_type : '',
        check_type : '',
        token_password : '',
        user_id : this.$gate.user.id
      }),
      selectedPaymentType: "",
      selectedState: "",
      selectedCheckHolderType: "",
      selectedCartType: "",
      


      value3: [],
      options3: [],
      value4: [],
      options4: [],
    };
  },
  methods: {
    validateRoutingNumber(n) {
        n = n ? n.match(/\d/g).join('') : 0;//get just digits
        var c = 0, isValid = false;

        if (n && n.length == 9){//don't waste energy totalling if its not 9 digits
            for (var i = 0; i < n.length; i += 3) {//total the sums
                c += parseInt(n.charAt(i), 10) * 3 +  parseInt(n.charAt(i + 1), 10) * 7 +  parseInt(n.charAt(i + 2), 10);
            }
            isValid = c != 0 && c % 10 == 0;//check if multiple of 10
        }

        return {//return an object telling whether its valid and if not, why.
            isValid: isValid,
            errorMsg: n.length != 9 ? 'Rounting number must be 9 digits' : (!isValid ? 'Invalid bank routing number.' : '')//determine the error message
        };
    },
    cardNumberKeyup(){
      $('#card_number'+this.id).mask('0000 0000 0000 0000');
      var out_put = this.creditCardTypeFromNumber($('#card_number'+this.id).val());
      if(out_put==0){out_put='';}
      this.form.card_type = out_put;
    },  
    creditCardTypeFromNumber(num) {
        // first, sanitize the number by removing all non-digit characters.
        num = num.replace(/[^\d]/g,'');
        // now test the number against some regexes to figure out the card type.
        if (num.match(/^5[1-5]\d{14}$/)) {
            return '5'; // 2 Master card 
        } else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
            return '4'; // 1 Visa
        }/* else if (num.match(/^3[47]\d{13}/)) {
            return 'AmEx';
        } */
        else if (num.match(/^6011\d{12}/)) {
            return '6';// 3 'Discover';
        }
        return '0';
    },
    selectPaymentType(data) {
      this.selectedPaymentType = data.name;
    },

    selectCartType(data) {
      this.selectedCartType = data.name;
    },

    selectCheckHolderType(data) {
      this.selectedCheckHolderType = data.name;
    },

    selectState(data) {
      this.selectedState = data.name;
    },
    addWallet(){
        if(this.form.token_password == '')
        {
            this.sendOtp();
            return false;
        }
        let routingNumber = this.form.bnk_routing_no;
        if(routingNumber)
        {
            if(routingNumber !== '' && routingNumber.indexOf("xx") != false)
            {
                var validateRouting = this.validateRoutingNumber(routingNumber);

                if(validateRouting.isValid == false)
                {
                    $(".panel-default").before('<div class="alert alert-danger"><ul><li>'
                        +validateRouting.errorMsg+
                    '</li></ul></div>');
                    return false;
                }    
            }
        }
      this.prepareFormData();
      if(this.editMode) this.updateWallet(this.wallet);
      else this.createWallet();
    },
    createWallet(){
      this.showLoader();
      this.form
        .post('/api/wallet/store/'+this.$gate.user.id+'/p/s')
        .then(({ data }) => {
          console.log(data);
          let status = data.status;
          let message = data.message;
          let style_class = "success";
          if(status == 0) style_class = "warning";
          $("#editAccount").modal("hide");
          Toast.fire({ icon: style_class, title: message });
          this.$parent.loadData();
        })
        .catch((error) => {
          Toast.fire({ icon: 'warning', title: 'Something went wrong. Please try again later' });
          this.form.reset();
          this.removeLoader();
        });
    },
    updateWallet(wallet){
      // wallet/update/{walletId}/{portfolio?}/{origin?}
      this.showLoader();
      this.form
        .post('/api/wallet/update/'+wallet.id+'/p/s')
        .then(({ data }) => {
          console.log(data);
          let status = data.status;
          let message = data.message;
          let style_class = "success";
          if(status == 0) style_class = "warning";
          $("#editAccount").modal("hide");
          Toast.fire({ icon: style_class, title: message });
          this.$parent.loadData();
        })
        .catch((error) => {
          Toast.fire({ icon: 'warning', title: 'Something went wrong. Please try again later' });
          this.form.reset();
          this.removeLoader();
        });
    },
    sendOtp(){
      let id = this.wallet ? this.wallet.id : 0;
      this.$refs.otpModal.sendOtp();
      $('#otpModal'+id).modal('show');
      
    },  
    prepareFormData(){
      this.form.payment_type = this.form.payment_type.id;
      this.form.month = this.form.month.id;
      this.form.year = this.form.year.id;
      this.form.state = this.form.state.id;
      this.form.check_holder_type = this.form.check_holder_type ? this.form.check_holder_type.id : this.form.check_holder_type;
      this.form.check_type = this.form.check_type ? this.form.check_type.id : this.form.check_type;

      if(this.form.payment_type == 1){
        delete this.form.bnk_routing_no;
        delete this.form.bnk_acc_no;
        delete this.form.check_holder_type;
        delete this.form.check_type;
      }else{
        delete this.form.card_type;
        delete this.form.card_number;
        delete this.form.month;
        delete this.form.year;
        delete this.form.card_cvv;
      }
    },
  },
};
</script>
