<template>
  <div>
    <div
      class="modal fade workorder-email"
      :id="'workOrderEmailModal' + workOrder.id"
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
            <div class="row">
              <div class="col-md-6 mx-auto">
                <h1 data-v-428a36ac="" class="h1">Send Email</h1>
                <form
                  @submit.prevent="sendEmail"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label
                        for="subject"
                        class="text-normal-bold-md color-secondary mb-12"
                      >
                        Subject
                      </label>
                      <div>
                        <input
                          type="text"
                          id="subject"
                          class="form-control"
                          v-model="form.subject"
                        />
                      </div>
                      <has-error :form="form" field="subject"></has-error>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label
                        for="subject"
                        class="text-normal-bold-md color-secondary mb-12"
                      >
                        Email
                      </label>
                      <div>
                        <input
                          type="text"
                          id="subject"
                          class="form-control"
                          v-model="form.email"
                        />
                      </div>
                      <has-error :form="form" field="email"></has-error>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label
                        for="subject"
                        class="text-normal-bold-md color-secondary mb-12"
                      >
                        Description
                      </label>
                      <div>
                        <textarea
                          rows="8"
                          class="w-100"
                          v-model="form.description"
                        ></textarea>
                      </div>
                      <has-error :form="form" field="subject"></has-error>
                    </div>
                  </div>
                  <div class="d-flex justify-content-center">
                    <button
                      class="btn btn-dark width-btn me-12"
                      data-bs-dismiss="modal"
                    >
                      Cancel
                    </button>
                    <button class="btn bkg-success" type="submit">Send</button>
                  </div>
                </form>
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
  components: {},
  props: ["workOrder"],
  data() {
    return {
      form: new Form({
        subject: this.workOrder.subject,
        email: "",
        description: this.workOrder.description,
      }),
    };
  },
  methods: {
    sendEmail() {
      this.showLoader();
      this.form
        .post("/api/workorder/sendEmail/" + this.workOrder.id)
        .then(({ data }) => {
          $('#workOrderEmailModal'+this.workOrder.id).modal('hide');
          this.form.reset();
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
  },
};
</script>
