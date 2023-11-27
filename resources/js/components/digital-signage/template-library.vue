<template>
  <div class="digital-section">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
        >Select all</a
      >
      <a class="align-self-center a-link mb-16 mb-lg-0" href="">View all</a>
    </div>
    <div class="mb-50">
      <button
        class="btn bkg-success"
        data-bs-toggle="modal"
        data-bs-target="#addNewDisplayModal"
      >
        + add new display
      </button>
    </div>

    <div class="row gy-3">
      <div class="col-xl-3 col-lg-4 col-md-6" v-for="index in 8">
        <div class="card-advertisement">
          <div class="position-relative">
            <div class="top-image">
              <img src="/images/test-bg.jpg" class="camera-img" alt="" />
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
                <!-- <div
                  class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                >
                  Laundry room
                </div> -->
                <!-- <div
                  class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                >
                  Laundry
                </div> -->
                <div
                  class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                >
                  Lobby
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <div>
                <button class="btn btn-dark">Edit</button>
              </div>

              <div class="align-self-end">
                <div class="form-check form-switch custom-switch p-0">
                  <label class="switch mb-0">
                    <input type="checkbox" checked role="switch" />
                    <span class="slider round"></span>
                  </label>
                </div>
                <p class="text-end text-normal color-secondary mb-0">Active</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
