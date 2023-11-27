<template>
  <div class="doors-section">
    <div
      class="modal fade"
      id="doorHistoryModal"
      tabindex="-1"
      aria-labelledby="doorHistoryModallabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-bottom-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <h1 class="h1 mb-32">Door Enterance 2</h1>
            <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
              <div>
                <a class="align-self-center a-link me-20" href=""
                  >Download .xls</a
                >
                <a class="align-self-center a-link me-20" href=""
                  >Print report</a
                >
              </div>
            </div>
            <div class="row gy-3">
              <div class="col-xl-6">
                <div class="main-section-img position-relative">
                  <img src="/images/test-Img.png" class="main-img" alt="" />

                  <div class="left-overlay">
                    <div class="d-flex justify-content-between">
                      <div class="align-self-end">
                        <div class="bg-light-opacity text-white">
                          <div class="d-flex">
                            <div class="green-dot me-8"></div>
                            <div class="text-medium">Video</div>
                          </div>
                          <h3 class="h3 mb-4 d-none d-lg-block">
                            Pine Enterance 1
                          </h3>
                        </div>
                      </div>
                      <div class="align-self-end">
                        <div class="bg-light-opacity">
                          <div class="icon-sm">
                            <img
                              src="/images/icons/left_arrow.png"
                              class="icon-img"
                              alt=""
                            />
                          </div>
                        </div>
                      </div>

                      <div class="align-self-end">
                        <div class="bg-light-opacity">
                          <div class="icon-sm">
                            <img
                              src="/images/icons/download.png"
                              class="icon-img"
                              alt=""
                            />
                          </div>
                        </div>
                      </div>

                      <div class="align-self-end d-none d-md-block">
                        <div class="bkg-danger text-white border-13 px-16 py-4">
                          report incident
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
  },
  data() {
    return {
      selectedBadge: 1,
      value: [{ name: "10", code: "1" }],
      options: [
        { name: "10", code: "1" },
        { name: "25", code: "2" },
        { name: "50", code: "3" },
        { name: "100", code: "4" },
      ],
    };
  },

  methods: {
    selectBadge(index) {
      this.selectedBadge = index;
    },
  },
};
</script>
