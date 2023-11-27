<template>
  <div
  class="modal fade broadcast-modal"
  :id="'addEditLaundryAd' +id"
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
              <h3 class="h3 mb-13">{{title}}</h3>

              <alert-error :form="form"></alert-error>


              <div class="row gy-3">
                  <div class="mb-24 col-xl-12">
                      <label class="text-normal-bold-md mb-12 color-secondary" for="title">Offer Name</label>
                      <input id="title" v-model="form.offer_name" type="text" class="form-control" />
                      <has-error :form="form" field="offer_name"></has-error>
                  </div>
              </div>
              <div class="row gy-3">
                  <div class="mb-24 col-xl-12">
                      <label class="text-normal-bold-md mb-12 color-secondary" for="title">No. of Appliances Use</label>
                      <input id="title" v-model="form.offer_num" type="number" class="form-control" />
                      <has-error :form="form" field="offer_num"></has-error>
                  </div>
              </div>
              <div class="row gy-3">
                  <div class="mb-24 col-xl-12">
                      <label class="text-normal-bold-md mb-12 color-secondary" for="title">Free Offer</label>
                      <input id="title" v-model="form.offer_num_times" type="number" class="form-control" />
                      <has-error :form="form" field="offer_num_times"></has-error>
                  </div>
              </div>

              <label class="color-secondary mb-12" type="button">
                    <input
                      type="file"
                      id="myFile"
                      name="filename"
                      class="d-none"
                      @change="attachAFile()"
                    />
                    Upload Advertisement Image <i class="fa fa-paperclip"></i>
                  </label>
              

              <div class="d-flex justify-content-end">
                  <button class="btn btn-dark" type="button" data-bs-dismiss="modal" style="margin-right: 2px;">Cancel</button>
                  <button type="submit" class="btn btn-dark">Save</button>
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
  components : {
      
  },
  props : ["id"],
  data() {
    return {
      form : new Form({
        offer_name : '',
        offer_num : '',
        offer_num_times : '',
        image : '',
        id : '',
      }),
      weeklyPlannerHtml : '',
      title : 'Laundry Advertisement Setup'
    };
  },
  mounted(){
    
  },
  created(){
      
  },  
  methods : {
    attachAFile() {
        let file = event.target.files[0];
        let self = this;
        
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const base64Image = e.target.result;
                self.form.image = base64Image;
            };

            reader.readAsDataURL(file);
        }
    },
    submit(){
        this.showLoader();
        this.form
            .post("/api/saveAdvertisement")
            .then(({ data }) => {
            
                this.$parent.loadData();
                Toast.fire({ icon: "success", title: 'Advertisement saved succesfully.' });
                $('#addEditLaundryAd'+this.id).modal('hide');
                this.removeLoader();
            })
            .catch((error) => {
                this.removeLoader();
            });
    }

  }
}
</script>
