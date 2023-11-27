<template>
  <div class="a-admin-components" v-if="type">
    <div
        class="modal fade"
        :id="type+'videoModal'"
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
              <video v-if="video" width="750" height="500" controls autoplay>
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
  props: ["type"],
  created() {
    this.loadAmazonUrl();
  },
  data() {
    return {
      amazonUrl: "",
      video: null,
    };
  },
  methods: {
    loadAmazonUrl() {
      this.amazonUrl = $('#mus_entry_url').val();
    },
    loadVideo(video) {
      this.video = this.amazonUrl + video;
      $('#' + this.type + "videoModal").modal('show');
    },
  },
};
</script>
