<template>
  <div class="a-admin-components" v-if="type">
    <div
        class="modal fade"
        :id="type+'fiveImagesModal'"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h3 class="h3 mb-13">
              Images
            </h3>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-27">
              <div class="row">
                <div v-for="image in images" class="col-xl-4 col-lg-4 col-md-4">
                  <img style="width: 100%;cursor: pointer"
                       :src="amazonUrl+building_id+'/'+image"
                       alt="">
                </div>
              </div>
            </div>
            <div id="action-btn" class="d-flex justify-content-end">
              <button
                  type="button"
                  class="btn btn-dark me-36"
                  data-bs-dismiss="modal"
                  aria-label="Close"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["type"],
  created() {
    this.loadAmazonUrl();
  },
  data() {
    return {
      amazonUrl: "",
      building_id: "",
      images: []
    };
  },
  methods: {
    loadAmazonUrl() {
      this.amazonUrl = $('#mus_entry_url').val();

    },
    loadFiveImages(image, building_id) {
      this.amazonUrl = $('#mus_entry_url').val();
      this.building_id = building_id;
      let params = {image: image};
      this.$http
          .post("/loadFiveImages", params)
          .then((response) => {
            this.images = response.data.images;
            $('#' + this.type + "fiveImagesModal").modal('show');
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
