<template>
  <div>
    <!-- Modal -->
    <div
      class="modal fade broadcast-modal"
      id="broadcastCustomMessageModal"
      tabindex="-1"
      aria-labelledby="broadcastModalLabel"
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
              <form @submit.prevent="createBroadcastTemplate" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Create Broadcast Template</h1>
                  <!-- <p class="mb-28 text-medium color-secondary">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    Architecto repellendus doloribus odio dignissimos expedita
                    rem!
                  </p> -->

                  <alert-error :form="form"></alert-error>


                  <div class="mb-24">
                    <label
                      class="text-normal-bold-md mb-12 color-secondary"
                      for="title"
                      >Title</label
                    >
                    <input id="title" v-model="form.title" type="text" class="form-control" />
                  </div>
                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Message type
                    </p>
                    <div>
                      <span class="custome-badge type-span active sms-tag select-cursor" @click="toggleActiveType('sms-tag','sms')">SMS</span>
                      <span class="custome-badge email-tag type-span select-cursor" @click="toggleActiveType('email-tag','email')" >Email</span>
                    </div>
                    <has-error :form="form" field="message_type"></has-error>
                  </div>

                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Template type
                    </p>
                    <div>
                      <span class="custome-badge template-span active general-tag select-cursor" @click="toggleTemplateType('general-tag','g')">General</span>
                      <span class="custome-badge verification-tag template-span select-cursor" @click="toggleTemplateType('verification-tag','v')" >Verification</span>
                    </div>
                    <has-error :form="form" field="message_type"></has-error>
                  </div>

                  <div class="mb-24">
                    <label
                      class="text-normal-bold-md mb-12 color-secondary"
                      for="message"
                      >Message</label
                    >
                    <textarea v-if="this.activeTab != 'email'" id="message" type="text" rows="5" v-model="form.description"> </textarea>
                    <ckeditor v-else :editor="editor" v-model="form.description" :config="editorConfig" :style="{ height: editorHeight }"/>
                    <has-error :form="form" field="description"></has-error>
                  </div>


                  <div class="d-flex justify-content-evenly">
                    <button class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Save</button>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue2';

export default {
  components : {
    ckeditor: CKEditor.component,
  },
  data() {
    return {
      activeTab : 'sms',
      editor: ClassicEditor,
      editorData: '<p>Start typing...</p>',
      editorHeight : '400px',
      editorConfig: {
        // CKEditor configuration options (optional)
      },
      form : new Form({
        title : '',
        message_type : 'sms',
        description : '',
        template_type : 'g',
      }),
    };
  },
  mounted(){
    
  },  
  props : [ ],
  methods : {
    toggleActiveType(type,tab){
      $('.type-span').removeClass('active');
      $('.'+type).addClass('active'); 
      this.activeTab = tab;
      this.form.message_type = tab;
      this.form.description = '';
    },
    toggleTemplateType(type,tab){
      $('.template-span').removeClass('active');
      $('.'+type).addClass('active');
      this.form.template_type = tab;
    },
    createBroadcastTemplate(){
      this.form
        .post("/api/createBroadcastTemplate")
        .then(({ data }) => {
          Toast.fire({
            icon: "success",
            title: "Template created successfully. You will be able to see the template while creating a new broadcast message",
          });
          $("#broadcastCustomMessageModal").modal("hide");
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    }

  }
}
</script>
<style>

.ck-editor__editable {
    min-height: 300px;
}
</style>
