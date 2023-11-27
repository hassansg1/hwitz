<template>
  <div class="a-admin-components">
    <div
        class="modal fade"
        id="packageVideoModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h3 class="h3 mb-13">
              Image
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
              <video v-if="this.videoType === 'dropOff'" width="750" height="500" controls autoplay>
                <source :src="this.dropOffVideoUrl" type="video/mp4">
                Your browser does not support the video tag.
              </video>
              <video v-if="this.videoType === 'pickUp'" width="750" height="500" controls autoplay>
                <source :src="this.pickUpVideoUrl" type="video/mp4">
                Your browser does not support the video tag.
              </video>
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
  created() {
    this.loadAmazonUrl();
  },
  data() {
    return {
      package: null,
      videoType: null,
      pickUpVideoUrl: null,
      dropOffVideoUrl: null,
      amazonUrl: "",
    };
  },
  methods: {
    loadPackageDetail(packageId, type) {
      this.videoType = type;
      let params = {};
      params.id = packageId;
      this.$http
          .post("/lockerPackageDetail", params)
          .then((response) => {
            this.package = response.data;
            this.pickUpVideoUrl = this.amazonUrl + this.package.building_id + "/" + this.package.pickup_video;
            this.dropOffVideoUrl = this.amazonUrl + this.package.building_id + "/" + this.package.dropoff_video;
            $('#packageVideoModal').modal('show');
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },

    loadAmazonUrl() {
      this.amazonUrl = $('#mus_entry_url').val();
    },
  },
};
</script>
