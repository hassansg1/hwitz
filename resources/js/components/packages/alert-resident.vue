<template>
  <div>
    <div
        class="modal fade"
        id="packagesModal"
        tabindex="-1"
        aria-labelledby="packagesModal"
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
          <div class="modal-body">
            <div v-if="locker">
              <h1 class="h1 mb-12">Alert notice</h1>
              <div class="text-start">
                <div class="row">
                  <div
                      class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2"
                  >
                    <div class="d-flex justify-content-between mb-32">
                      <div class="align-self-center">
                        <h3 class="h3 mb-0 text-capitalize">{{ locker.label }}</h3>
                        <p class="text-medium-bold color-primary mb-0" v-if="locker.package && locker.package.receiver">
                          {{ locker.package.receiver.name }}</p>
                        <p class="color-primary text-normal mb-0" v-if="locker.locker_screen">
                          {{ locker.locker_screen.name }}</p>
                      </div>
                      <div class="align-self-center">
                        <div class="position-relative text-center">
                          <div class="avatar-sm">
                            <img :src="locker.package.receiver.profile_picture" class="avatar-img" alt=""/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="mb-32">
                      <h3 class="h3 mb-0 text-capitalize">Alert Type</h3>
                      <br>
                      <br>
                      <input type="checkbox" v-model="emailAlert" id="confirm-term"
                             value="1"
                             style="margin-left: 5px">
                      Email
                      <input type="checkbox" v-model="textAlert" id="confirm-term"
                             value="1"
                             style="margin-left: 5px">
                      Text Message
                    </div>

                    <div class="d-flex justify-content-center mb-70">
                      <button class="btn btn-dark me-20">Cancel</button>
                      <button @click="sendPackageAlert()" class="btn btn-primary">Send alert</button>
                    </div>
                  </div>
                </div>
              </div>
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
      textAlert: false,
      emailAlert: false,
      locker: null
    };
  },
  expose: ["loadData"],
  methods: {
    alertResident(locker) {
      this.locker = locker;
      $('#packagesModal').modal('show');
    },
    sendPackageAlert() {
      if (!this.textAlert && !this.emailAlert) {
        Toast.fire({
          icon: "error",
          position: "top",
          title: "Select at least one alert type.",
        });
      } else {
        this.showLoader();
        let params = {packageId: this.locker.package.id, emailAlert: this.emailAlert, textAlert: this.textAlert};
        this.$http
            .post("/sendPackageAlert", params)
            .then((response) => {
              Toast.fire({
                icon: response.data.icon,
                title: response.data.message,
              });
              $('#packagesModal').modal('hide');
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      }
    },
  },
};
</script>
