<template>
  <div class="laundry-section">
    <div class="mb-50">
      <button class="btn bkg-success" @click="createAd">+ Create an ad template</button>
    </div>

    <div class="row gy-3">
      <div class="col-xl-3 col-lg-4 col-md-6" v-for="ad in advertismentData">
        <div class="card-advertisement">
          <div class="position-relative">
            <div class="top-image">
              <img :src="ad.bimage ? ad.bimage : '/images/default-no-image.png'" class="camera-img" alt="" />
            </div>
          </div>

          <div class="card-body">
            <div
              class="d-flex justify-content-between flex-column flex-wrap mb-24"
            >
              <div>
                <h3 class="h3">{{ad.offer_name}}</h3>
              </div>
              <div class="d-flex">
                <div class="badge-room text-normal text-normal-bold-md" style="margin-right: 2px;">
                  No. of Appliances Use : {{ ad.offer_num }}
                </div>
                <div class="badge-room text-normal text-normal-bold-md">
                  Free Offer : {{ ad.offer_num_times }}
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <div>
                <button class="btn btn-dark" @click="EditAd(ad)">
                  Edit
                </button>
              </div>

              <!-- <div class="align-self-end">
                <div class="form-check form-switch custom-switch p-0">
                  <label class="switch mb-0">
                    <input type="checkbox" checked role="switch" />
                    <span class="slider round"></span>
                  </label>
                </div>
                <p class="text-end text-normal color-secondary mb-0">active</p>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <addEditLaundryAdModal ref="addEditLaundryAdModal" :id="'advertisement'" />
  </div>
</template>
<script>
import addEditLaundryAdModal from './edit-laundry-ad-modal.vue';
  
export default {
  components : {
    addEditLaundryAdModal
  },
  props : [],
  data() {
    return {
      advertismentData : []
    };
  },
  mounted(){
    
  },
  created(){
      
  },  
  methods : {
    createAd(){
      this.$refs.addEditLaundryAdModal.form.reset();
      $('#addEditLaundryAdadvertisement').modal('show');
    },
    EditAd(ad){
      this.$refs.addEditLaundryAdModal.form.reset();
      this.$refs.addEditLaundryAdModal.form.offer_name = ad.offer_name;
      this.$refs.addEditLaundryAdModal.form.offer_num = ad.offer_num;
      this.$refs.addEditLaundryAdModal.form.offer_num_times = ad.offer_num_times;
      this.$refs.addEditLaundryAdModal.form.id = ad.id;
      $('#addEditLaundryAdadvertisement').modal('show');
    },
    loadData(){
        this.showLoader();
          this.showLoader();
          this.$http.get("/getAdvertisements")
              .then((response) => {
                this.advertismentData = response.data;
                this.removeLoader();
              })
              .catch((error) => {
                  console.error(error);
                  this.removeLoader();
          });
    }

  }
}
</script>