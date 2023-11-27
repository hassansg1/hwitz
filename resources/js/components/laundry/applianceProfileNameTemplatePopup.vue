<template>
    <div>
      <div
        class="modal fade broadcast-modal"
        :id="'addApplianceProfileNameTemplatePopup'+id"
        tabindex="-1"
        aria-labelledby="broadcastModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
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
                <form @submit.prevent="submit" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                  <div class="col-lg-12 mx-auto">
                    <h3 class="h3 mb-13">Create Appliance Profile Template</h3>
  
                    <alert-error :form="form"></alert-error>
  
  
                    <div class="row pb-24 gy-3">
                        <div class="mb-24 col-xl-12">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">Template Name</label>
                            <input id="title" v-model="form.template_name" type="text" class="form-control" />
                            <has-error :form="form" field="template_name"></has-error>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                      <button class="btn btn-dark" type="button" data-bs-dismiss="modal" style="margin-right:2px">Cancel</button>
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
  export default {
    components : {
        
    },
    props : ["id"],
    data() {
      return {
        form : new Form({
          template_name : '',
        }),
      };
    },
    mounted(){
      
    },
    created(){
      window.openCostConfigPopup =this.openCostConfigPopup;
    },  
    methods : {
      submit(){
        this.showLoader();
        this.form
          .post("/api/saveLaundryName")
          .then(({ data }) => {
            this.$parent.loadData();
            Toast.fire({
              icon: "success",
              title: "Template created successfully.",
            });
            $("#addApplianceProfileNameTemplatePopup"+this.id).modal("hide");
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
          });
      },
    }
  }
  </script>
  