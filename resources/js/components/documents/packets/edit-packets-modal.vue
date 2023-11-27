<template>
  <div
    class="documents-packets modal fade"
    id="editPacketModal"
    tabindex="-1"
    aria-labelledby="editPacketModal"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl documents-modal-customize">
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
          <div class="documents-packets">
            <div class="d-flex mb-28">
              <div class="icon-lg me-20">
                <img
                  src="/images/icons/pencil.png"
                  class="icon-img"
                  alt="icon"
                />
              </div>
              <div>
                <p class="text-medium-bold mb-8">Edit Packet</p>
                <p class="text-sm color-secondary">Lorem ipsum ets hyct</p>
              </div>
            </div>

            <div class="row g-3 mb-28">
              <div class="col-lg-3 col-md-6">
                <div class="dropdown-wrapper">
                  <label class="dropdown-label text-capitalize"
                    >Select packet</label
                  >
                  <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                    v-model="packet"
                    tag-placeholder=""
                    placeholder=""
                    label="name"
                    track-by="id"
                    :options="packets"
                    :multiple="false"
                  ></multiselect>
                </div>
              </div>
            </div>
            <div class="bkg-secondary p-36 border-17 mb-28">
              <div class="d-flex flex-wrap mb-16">
                <div class="me-12 mb-12 mb-md-0">
                  <h3 class="h3">All templates</h3>
                </div>
              </div>

              <div class="row g-3">
                <div
                  class="col-xl-3 col-lg-4 col-md-6 col-12"
                  v-for="item in 6"
                >
                  <div class="bg-gray doc-card">
                    <div class="row justify-content-center">
                      <div class="col-9 bg-white top-radius p-12 pb-0">
                        <div class="top-bar"></div>
                        <p class="text-sm-bold mb-0">Your Name</p>
                        <p class="text-xsm-bold text-success mb-0">
                          Industrial designer
                        </p>
                        <p class="text-xsm mb-0">test@mail.com</p>
                        <p class="text-xsm mb-0">+1 234 5678 890</p>
                      </div>
                    </div>
                    <div class="bg-white py-8 px-12 bottom-radius">
                      <div class="row">
                        <div class="col-12 text-truncate">
                          <h3 class="text-medium-bold">Title {{ item }}</h3>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <div
                          class="col-6 text-uppercase text-xsm-semibold color-secondary-dark"
                        >
                          Versions
                        </div>
                        <div
                          class="col-3 px-0 text-uppercase text-xsm-semibold color-secondary-dark"
                        >
                          Pending
                        </div>
                        <div
                          class="col-3 px-0 text-uppercase text-xsm-semibold color-secondary-dark"
                        >
                          Signed
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-6 text-uppercase text-sm-bold">
                          Version 7.1
                        </div>
                        <div class="col-3 px-0">
                          <div class="num-container-sm bkg-danger text-white">
                            5
                          </div>
                        </div>
                        <div class="col-3 px-0">
                          <div class="num-container-sm bkg-success">10</div>
                        </div>
                      </div>

                      <div class="row mb-4" v-for="item in 2">
                        <div class="col-6 text-uppercase text-xsm">
                          Amendment {{ item }}
                        </div>
                        <div class="col-3 px-0">
                          <div class="num-container-sm bkg-danger text-white">
                            5
                          </div>
                        </div>
                        <div class="col-3 px-0">
                          <div class="num-container-sm bkg-success">10</div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-between mb-4">
                        <div class="align-self-center">
                          <a class="a-link mb-0 text-normal"
                            >See all versions</a
                          >
                        </div>
                        <div class="align-self-center">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-end">
              <button class="btn btn-primary update-btn">Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePdfEmbed from "vue-pdf-embed/dist/vue2-pdf-embed";
export default {
  components: {
    VuePdfEmbed,
  },
  data() {
    return {
      packet: "",
      packets: [
        {
          name: "packet 1",
        },
        {
          name: "packet 2",
        },
        {
          name: "packet 3",
        },
        {
          name: "packet 4",
        },
        {
          name: "packet 5",
        },
        {
          name: "packet 6",
        },
      ],
    };
  },
};
</script>
