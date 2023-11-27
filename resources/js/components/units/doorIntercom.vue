<template>
  <div class="mb-64">
    <h3 class="h3 mb-28">Door Intercom</h3>
    <div class="text-start" v-if="this.unit">
      <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card">
            <form @submit.prevent="updateDoorIntercom" id="updateDoorIntercom" @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data">
              <alert-error :form="form"></alert-error>
              <div class="multiselect-border multiselect-bold mb-16">
                <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                  Call Number
                </label>
                <div>
                  <input disabled type="text" class="form-control" :value="this.unit.button"/>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                    First Number
                  </label>
                  <div>
                    <input
                        type="text" class="form-control" maxlength="20"
                        v-model="form.call1"/>
                  </div>
                  <has-error :form="form" field="call1"></has-error>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                    Link (Rings at Same time)
                  </label>
                  <div>
                    <input
                        type="checkbox" class=""
                        v-model="form.group1"/>
                  </div>
                  <has-error :form="form" field="group1"></has-error>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                    Second Number
                  </label>
                  <div>
                    <input
                        type="text" @keypress="onlyNumber" class="form-control" maxlength="10"
                        v-model="form.call2"/>
                  </div>
                  <has-error :form="form" field="call2"></has-error>
                </div>
              </div>


              <div class="row">
                <div class="col-12 mb-3">
                  <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                    Link (Rings at Same time)
                  </label>
                  <div>
                    <input
                        type="checkbox" class=""
                        v-model="form.group2"/>
                  </div>
                  <has-error :form="form" field="group2"></has-error>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                    Third Number
                  </label>
                  <div>
                    <input
                        type="text" @keypress="onlyNumber" class="form-control" maxlength="10"
                        v-model="form.call3"/>
                  </div>
                  <has-error :form="form" field="call3"></has-error>
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                  Cancel
                </button>
                <button class="btn bkg-primary text-white" type="submit">Update</button>
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
  props: ["unit"],
  components: {},
  mounted() {
    this.form.call1 = this.unit.call1;
    this.form.call2 = this.unit.call2;
    this.form.call3 = this.unit.call3;
    this.form.group1 = this.unit.group1;
    this.form.group2 = this.unit.group2;
  },
  data() {
    return {
      form: new Form({
        call1: '',
        call2: '',
        call3: '',
        group1: '',
        group2: '',
        unit_id: '',
      }),
    };
  },
  methods: {
    ValidateIPaddress(ipaddress) {
      if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
        return true;
      }
      return false;
    },
    isNumeric: function (n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    },
    updateDoorIntercom() {
      let validNumber = true;
      let message = "";
      if (this.form.call1.length == 0) {
        validNumber = true;
      }
      let sip = this.form.call1.substr(0, 4);
      if (sip === 'sip:') {
        if (!this.ValidateIPaddress(this.form.call1.substr(4, this.form.call1.length))) {
          message = 'Please follow the format "sip:255.255.255.255"';
          validNumber = false;
        }
      } else if (this.isNumeric(this.form.call1) && (this.form.call1.length == 10)) {
        validNumber = true;
      } else {
        message = 'Please provide valid phone number';
        validNumber = false;
      }
      if (!validNumber) {
        Toast.fire({
          icon: "error",
          title: message,
          position: "center",
        });
        return;
      }
      this.showLoader();

      this.form.unit_id = this.unit.id;
      this.form
          .post("/api/updateDoorIntercom")
          .then((response) => {
            Toast.fire({
              icon: response.data.status,
              title: response.data.message,
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
    }
  },
};
</script>

