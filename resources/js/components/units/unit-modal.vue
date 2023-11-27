<template>
  <div>
    <div
      class="modal fade unit-modal"
      id="unitModal"
      tabindex="-1"
      aria-labelledby="unitModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg-md">
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
            <div class="mb-52">
              <div>
                <p class="text-medium-bold mb-4">Link User to another unit</p>
                <p class="text-sm color-secondary">lorem ipsum as deg tre</p>
              </div>
            </div>
            <div class="row gy-3 mb-52">
              <div class="col-lg-6 col-md-6 mb-9">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="floatingInput" />
                  <label for="floatingInput">Day Wanted</label>
                </div>
              </div>
              <div class="col-lg-6 col-md-6">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="floatingInput" />
                  <label for="floatingInput">Day Wanted</label>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn bkg-primary text-white">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
};
</script>
