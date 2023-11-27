<template>
  <div class="a-admin-components">
    <div
        class="modal fade"
        id="packageImageModal"
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
              <img style="width: 100%;height: 100%" :src="this.imageUrl" alt="">
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
  },
  data() {
    return {
      imageUrl: "",
    };
  },
  methods: {},
};
</script>
