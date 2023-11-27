<template>
  <div>
    <!-- Modal -->
    <div
        class="modal fade send-direct-message-modal"
        id="directMessageModal"
        tabindex="-1"
        aria-labelledby="directMessageModalArea"
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
            <div class="row">
              <div class="col-md-6 mx-auto multiselect-border multiselect-bold">
                <h1 class="h1 mb-13">Send Direct Message</h1>
                <p class="text-medium color-secondary mb-12">Select a user</p>
                <form
                    @submit.prevent="sendMessage"
                    @keydown="form.onKeydown($event)"
                    enctype="multipart/form-data"
                >
                  <alert-error :form="form"></alert-error>
                  <div class="dropdown-wrapper mb-12" v-if="data">
                    <multiselect
                        :selectLabel="''"
                        :deselectLabel="''"
                        v-model="form.users"
                        placeholder=""
                        label="full_name"
                        track-by="id"
                        :options="data"
                        :multiple="true"
                    ></multiselect>
                    <has-error :form="form" field="users"></has-error>
                  </div>
                  <!-- <div class="multiselect-area"> -->

                  <!-- </div> -->

                  <div id="text-area">
                    <p class="text-medium color-secondary mb-12">Message</p>
                    <textarea
                        rows="8"
                        class="w-100"
                        v-model="form.message"
                    ></textarea>
                    <has-error :form="form" field="message"></has-error>
                  </div>
                  <label class="color-secondary mb-12" type="button">
                    <input
                        type="file"
                        id="myFile"
                        name="filename"
                        class="d-none"
                        multiple
                        @change="attachAFile"
                    />
                    Attach a file
                  </label>
                  <div class="text-medium mb-28">
                    {{ files.join(" , ") }}
                  </div>
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-dark me-12" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button type="submit" class="btn bkg-success">
                      Send Message
                    </button>
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
import {Form} from "vform";

export default {
  props: ["data"],

  data() {
    return {
      dataArray: [
        {id: 1, name: "john doe", image: "/images/worker.png"},
        {id: 2, name: "jane doe", image: "/images/Ellipse1.png"},
        {id: 3, name: "jane doe 2", image: "/images/Ellipse14.png"},

        {id: 4, name: "john doe", image: "/images/worker.png"},
        {id: 5, name: "jane doe", image: "/images/Ellipse14.png"},
        {id: 6, name: "jane doe 2", image: "/images/Ellipse1.png"},
      ],
      value: [{name: "Building 1", code: "1"}],
      options: [
        {name: "Building 2", code: "1"},
        {name: "Building 2", code: "2"},
        {name: "Building 3", code: "3"},
        {name: "Building 4", code: "4"},
      ],
      form: new Form({
        users: "",
        message: "",
        attachment: [],
      }),
      files: [],
    };
  },
  methods: {
    sendMessage() {
      this.showLoader();
      if (this.form.users && this.form.users.length > 0) {
        this.form.users = this.form.users.map((obj) => obj.id);
      }
      this.form
          .post("/api/sendMessage")
          .then(({data}) => {
            Toast.fire({
              icon: "success",
              title: "Message sent successfully.",
            });
            $("#directMessageModal").modal("hide");
            this.$parent.fetchMessages("inbox");
            // this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
    },
    attachAFile() {
      var files = $("#myFile")[0].files;
      for (var i = 0; i < files.length; i++) {
        var fileup = files[i];
        this.form.attachment.push(fileup);
        this.files.push(fileup.name);
        console.log(this.form.attachment);
        // formData.append($(".EmailAttachmentName").attr("name"), fileup, fileup.name);
      }
      return;
      let file = event.target.files[0];

      const reader = new FileReader();

      reader.onloadend = () => {
        const attachment = reader.result;
        const formData = new FormData();
        this.form.attachment = attachment;
      };

      reader.readAsDataURL(file);
    },
  },
};
</script>
