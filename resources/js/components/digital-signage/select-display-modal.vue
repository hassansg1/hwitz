<template>
  <div class="digital-section">
    <div
      class="modal fade"
      id="selectDisplayModal"
      tabindex="-1"
      aria-labelledby="selectDisplayLabel"
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
            <div class="text-center mb-50">
              <h3 class="h3 mb-12">Select Display</h3>
              <p class="text-medium-bold color-secondary w-75 mx-auto">
                Select the ads or displays you want to showcase in your space.
              </p>
            </div>

            <div class="row gy-3 mb-50">
              <div class="col-xl-3 col-lg-4 col-md-6" v-for="index in 8">
                <div class="card-advertisement">
                  <div class="position-relative">
                    <div class="top-image">
                      <img
                        src="/images/test-bg.jpg"
                        class="camera-img"
                        alt=""
                      />
                    </div>
                  </div>

                  <div class="card-body">
                    <div
                      class="d-flex justify-content-between flex-row flex-wrap mb-24"
                    >
                      <div>
                        <h3 class="h3">Promotion 1</h3>
                      </div>
                      <div class="d-flex flex-row flex-wrap">
                        <div
                          class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                        >
                          gym
                        </div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-between">
                      <div>
                        <button class="btn btn-dark">Edit</button>
                      </div>

                      <div class="align-self-end checkbox">
                        <div class="d-flex">
                          <input
                            class="form-check-input mt-0 me-6"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                          <p
                            class="align-self-center text-end text-normal color-secondary mb-0"
                          >
                            Assign
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end select-display-modal">
              <button class="btn btn-dark me-20">cancel</button>
              <button class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
