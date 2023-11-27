<template>
  <div>
    <div
      class="modal fade unit-modal"
      id="unitOccupiedModal"
      tabindex="-1"
      aria-labelledby="unitOccupiedModalLabel"
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
              <div class="d-flex">
                <div class="me-16">
                  <div class="icon-lg">
                    <img
                      src="/images/icons/pencil.png"
                      class="icon-img"
                      alt=""
                    />
                  </div>
                </div>

                <div>
                  <p class="text-medium-bold mb-4">Edit User</p>
                  <p class="text-sm color-secondary">lorem ipsum as deg tre</p>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-6 mb-9">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="floatingInput"
                    />
                    <label for="floatingInput">Day Wanted</label>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="floatingInput"
                    />
                    <label for="floatingInput">Day Wanted</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-52">
              <div class="d-flex">
                <div class="me-16">
                  <div class="icon-lg">
                    <img src="/images/icons/door.png" class="icon-img" alt="" />
                  </div>
                </div>

                <div>
                  <p class="text-medium-bold mb-4">Door Intercom</p>
                  <p class="text-sm color-secondary">lorem ipsum as deg tre</p>
                </div>
              </div>
              <div class="row gy-3">
                <div class="col-lg-6 col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="floatingInput"
                    />
                    <label for="floatingInput">Name</label>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="floatingInput"
                    />
                    <label for="floatingInput">Name</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn bkg-primary">Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      paymenmentType: [
        "Cash",
        "Checks",
        "Debit cards",
        "Credit cards",
        "Mobile payments",
      ],

      cartType: ["Cart 1", "Cart 2", "Cart 3", "Cart 4", "Cart 5"],
      stats: ["State 1", "State 2", "State 3", "State 4", "State 5"],
    };
  },
};
</script>
