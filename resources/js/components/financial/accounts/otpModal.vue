<template>
    <div>
      <div
        class="modal fade"
        :id="'otpModal'+id"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        style="z-index: 9999;"
      >
        <div class="modal-dialog border-normal">
          <div class="modal-content">
            <div class="modal-header">
              <h3>Otp Verification</h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form @submit.prevent="verifyOtp" @keydown="form.onKeydown($event)">
                <div class="modal-body text-start addEditAccountModalBody">
                    <div class="row">
                        <div class="col-md-12 mb-5 mb-md-0">
                            <alert-error :form="form"></alert-error>
                            <span id="OTPMsg"></span>
                            <!-- <h3 class="h3 mb-16">Profile information</h3> -->
                            <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div>
                                    <label for="firstAndSecondName" class="text-normal-bold-md color-secondary mb-12" >Please enter code</label>
                                    <input required type="text" id="Mobile" class="form-control" v-model="form.wallet_token" />
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="p-detail" class="d-flex justify-content-end">
                        <button type="button" class="btn btn-dark mb-2 ms-1 px-4 btn-size" @click="resendOtp">Resend Wallet Code</button>
                        <button type="submit" class="btn btn-dark mb-2 ms-1 px-4 btn-size">Save</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import { Form } from 'vform';

  export default {
    props : ['id'],
    components: {
    },
    data() {
      return {
        form : new Form({
            wallet_token : ''
        })
      };
    },
    methods: {
        verifyOtp(){
            this.$parent.form.token_password = this.form.wallet_token;
            this.$parent.addWallet();
            this.form.reset();
            $('#otpModal'+this.id).modal('hide');
            $('#editAccount'+this.id).modal('hide');
        },
        sendOtp(){
            this.showLoader();
            this.$http
                .get("wallet/sendOTP/"+this.$gate.user.id)
                .then((response) => {
                    console.log(response.data);
                    $('#OTPMsg').html('<div class="alert alert-success">'+response.data+'</div>');
                    this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        resendOtp(){
            this.showLoader();
            this.$http
                .get("wallet/resend_OTP/"+this.$gate.user.id)
                .then((response) => {
                    console.log(response.data);
                    $('#OTPMsg').html('<div class="alert alert-success">'+response.data+'</div>');
                    this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    },
  };
  </script>
 
  