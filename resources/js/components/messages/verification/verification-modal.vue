<template>
  <div>
    <!-- Modal -->
    <div
      class="modal fade verification-modal"
      id="verificationModal"
      tabindex="-1"
      aria-labelledby="verification"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-customize">
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
            <form @submit.prevent="sendBroadcastMessage" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Verification Broadcast</h1>
                  <alert-error :form="form"></alert-error>
                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Message type
                    </p>
                    <div>
                      <span class="custome-badge active">Both</span>
                    </div>
                  </div>
                  <div class="multiselect-border mb-24">
                    <div class="dropdown-wrapper">
                      <label class="dropdown-label text-capitalize">Template</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="template"
                        placeholder=""
                        label="title"
                        track-by="id"
                        :options="templates"
                        :multiple="false"
                        @input="selectTemplate"
                      ></multiselect>
                    </div>
                  </div>
                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Message
                    </p>
                    <div>
                      <textarea name="" id="" rows="5" v-model="form.description"></textarea>
                    </div>
                    <has-error :form="form" field="description"></has-error>
                  </div>

                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Fob Turn Off Date
                    </p>
                    <div class="row">
                      <div>
                        <input type="date" class="form-control" v-model="form.expiry" required/>
                      </div>
                    </div>
                    <has-error :form="form" field="expiry"></has-error>
                  </div>

                  <div class="d-flex justify-content-evenly">
                    <button class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Send Verification</button>
                  </div>
                </div>
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

  props : ['users'],
  methods : {
    selectTemplate(){
      this.form.description = this.template.description;
    },  
    loadTemplates(){
      this.$http
        .get("/getBroadcastTemplates?template_type=v&message_type="+this.form.message_type)
        .then((response) => {
          this.templates = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    sendBroadcastMessage(){
      this.showLoader();
      this.form
        .post("/api/sendBroadcastMessage")
        .then(({ data }) => {
          Toast.fire({
            icon: "success",
            title: "Verification broadcasted successful.",
          });
          $("#verificationModal").modal("hide");
          this.$parent.checkedUsers = [];
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
  },
  data(){
    return {
      form : new Form({
        message_type : 'both',
        template_type : 'v',
        description : '',
        users : this.users,
        expiry : '',
        building_id : ''
      }),
      template : '',
      templates : []
    };
  },
}
</script>
