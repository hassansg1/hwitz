<template>
  <div>
    <!-- Modal -->
    <div
      class="modal fade broadcast-modal"
      id="broadcastModal"
      tabindex="-1"
      aria-labelledby="broadcastModalLabel"
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
              <form @submit.prevent="sendBroadcastMessage" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Send Broadcast Message</h1>
                  <!-- <p class="mb-28 text-medium color-secondary">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    Architecto repellendus doloribus odio dignissimos expedita
                    rem!
                  </p> -->

                  <alert-error :form="form"></alert-error>
                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Message type
                    </p>
                    <div>
                      <span class="custome-badge tab-span active sms-tag select-cursor" @click="toggleActiveType('sms-tag','sms')">SMS</span>
                      <span class="custome-badge mms-tag tab-span select-cursor" @click="toggleActiveType('mms-tag','mms')">MMS</span>
                      <span class="custome-badge email-tag tab-span select-cursor" @click="toggleActiveType('email-tag','email')" >Email</span>
                    </div>
                    <has-error :form="form" field="message_type"></has-error>
                  </div>

                  <div class="multiselect-border">
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
                  <!-- <div class="mb-24">
                    <label
                      class="text-normal-bold-md mb-12 color-secondary"
                      for="title"
                      >Title</label
                    >
                    <input id="title" type="text" class="form-control" />
                  </div> -->

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

                  <div class="mb-24" v-if="this.activeTab == 'mms'">
                    <p class="text-normal-bold-md color-secondary">Attachments</p>
                    <label class="custom-file-upload mb-6">
                      <input type="file" id="broadcastAttachment"/>
                      <div class="d-flex justify-content-between">
                        <div class="d-flex align-self-center">
                          <div class="align-self-center">
                            <img
                              src="/images/icons/attachment.png"
                              class="me-2"
                              alt=""
                            />
                          </div>
                          <div class="text-medium align-self-center mb-0">
                            Attachment
                          </div>
                        </div>

                        <div class="align-self-center">+</div>
                      </div>
                    </label>

                    <has-error :form="form" field="attachment"></has-error>
                  </div>

                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Recipients
                    </p>

                    <div class="d-flex flex-wrap">
                      <span class="custome-badge me-12 mb-12 select-cursor" :class="building.id == params.building_id ? 'active' : 'inactive'" @click="buildingToggle(building.id)" v-for="building in buildings">{{building.name}}</span>
                    </div>
                  </div>

                  <div class="mb-24">
                    <p class="text-normal-bold-md mb-12 color-secondary">
                      Filter residents with live services
                    </p>

                    <div class="d-flex flex-wrap">
                      <span class="custome-badge select-cursor filter-span parking me-12 mb-12" :class="params.parking ? 'active' : 'inactive'" @click="filterToggle('parking')">Parking</span>
                      <span class="custome-badge select-cursor filter-span tv me-12 mb-12" :class="params.tv ? 'active' : 'inactive'" @click="filterToggle('tv')">Tv</span>
                      <span class="custome-badge select-cursor filter-span phone me-12 mb-12" :class="params.phone ? 'active' : 'inactive'" @click="filterToggle('phone')">Phone</span>
                      <span class="custome-badge select-cursor filter-span internet me-12 mb-12" :class="params.internet ? 'active' : 'inactive'" @click="filterToggle('internet')">Internet</span>
                      <span class="custome-badge select-cursor filter-span package me-12 mb-12" :class="params.package ? 'active' : 'inactive'" @click="filterToggle('package')">Package Delivery</span>
                    </div>
                    <div class="multiselect-border">
                      <div class="dropdown-wrapper">
                        <label class="dropdown-label text-capitalize">Residents</label>
                        <multiselect 
                          :selectLabel="''"
                          :deselectLabel= "''"
                          v-model="form.users"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :options="residents"
                          :multiple="true"
                        ></multiselect>
                      </div>
                    </div>
                    <has-error :form="form" field="users"></has-error>
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
      buildings : '',
      activeFilterType : '',
      residents : [],
      resident : '',
      params : {
        usertype_id : 13,
        template_type: 'g',
        parking: 0,
        tv: 0,
        phone: 0,
        internet: 0,
        package: 0,
        building_id : ''
      },
      editor: ClassicEditor,
      editorData: '<p>Start typing...</p>',
      editorHeight : '400px',
      editorConfig: {
        // CKEditor configuration options (optional)
      },
      form : new Form({
        message_type : 'sms',
        description : '',
        building_id : '',
        template_type : 'g',
        attachment : [],
        services : {
          parking : 0,
          tv : 0,
          phone : 0,
          internet : 0,
          package : 0,
        },
        users : [],
      }),
      templates : [],
      template : ''

    };
  },
  mounted(){
    
  },
  methods : {
    selectTemplate(){
      console.log(this.template,'Ads');
      this.form.description = this.template.description;
    },  
    sendBroadcastMessage(){
      this.showLoader();
      this.form.users = this.form.users.map((obj) => obj.id);
      if(this.form.users.length > 0) this.form.users.push(this.$gate.user.id); // push owner id as well as per cake
      this.form
        .post("/api/sendBroadcastMessage")
        .then(({ data }) => {
          Toast.fire({
            icon: "success",
            title: "Message sent successfully.",
          });
          $("#broadcastModal").modal("hide");
          this.$parent.loadData();
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
    toggleActiveType(type,tab){
      $('.tab-span').removeClass('active');
      $('.'+type).addClass('active'); 
      this.activeTab = tab;
      this.form.message_type = tab;
      this.form.description = '';
      this.loadTemplates();
    },
    filterToggle(tab){
      // $('.filter-span').removeClass('active');
      // $('.'+tab).addClass('active')
      if(tab == 'parking') {
        this.params.parking = this.params.parking ? 0 : 1;
        this.form.services.parking = this.params.parking;
      }
      if(tab == 'tv') {
        this.params.tv = this.params.tv ? 0 : 1;
        this.form.services.tv = this.params.tv;
      }
      if(tab == 'phone') {
        this.params.phone = this.params.phone ? 0 : 1;
        this.form.services.phone = this.params.tv;
      }
      if(tab == 'internet') {
        this.params.internet = this.params.internet ? 0 : 1;
        this.form.services.internet = this.params.internet;
      }
      if(tab == 'package') {
        this.params.package = this.params.package   ? 0 : 1;
        this.form.services.package = this.params.package;
      }

      this.getResidentsByFilters();
    },
    buildingToggle(id){
      this.params.building_id = id;
      this.form.building_id = id;
      this.getResidentsByFilters();
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
    getResidentsByFilters(){
      this.$http
        .post("/workorder/getResidentsByFilters",this.params)
        .then((response) => {
          this.residents = response.data && response.data.resident && response.data.resident.length > 0 ? response.data.resident : [];
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    loadTemplates(){
      let type = this.form.message_type == 'email' ? 'email' : 'sms';

      this.$http
        .get("/getBroadcastTemplates?template_type=g&message_type="+type)
        .then((response) => {
          this.templates = response.data;
          console.log(this.templates,'asd');
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
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
