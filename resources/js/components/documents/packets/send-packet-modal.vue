<template>
  <div
    class="documents-packets modal fade"
    id="sendPacketModal"
    tabindex="-1"
    aria-labelledby="sendPacketModal"
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
          <div class="text-center mb-28">
            <h1 class="h1 mb-12">Send document</h1>
            <p class="text-medium-md color-secondary mb-0">
              Complete these steps to send your document
            </p>
          </div>
          <div class="d-flex justify-content-center mb-64">
            <div
              class="num-circle me-12 select-cursor"
              :class="{ 'bkg-primary': page1 || page2 || page3 }"
              @click="goDirect(1)"
            >
              1
            </div>
            <div
              class="num-circle me-12 select-cursor"
              :class="{ 'bkg-primary': page2 || page3 }"
              @click="goDirect(2)"
            >
              2
            </div>
            <div
              class="num-circle select-cursor"
              :class="{ 'bkg-primary': page3 }"
              @click="goDirect(3)"
            >
              3
            </div>
          </div>

          <div class="" v-if="page1">
            <div class="row g-3 mb-24">
              <div class="col-lg-3 col-md-6 multiselect-border-shadow">
                <div class="dropdown-wrapper">
                  <label class="dropdown-label text-capitalize">Unit</label>
                  <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                    v-model="unit"
                    tag-placeholder=""
                    placeholder=""
                    label="name"
                    track-by="id"
                    :options="units"
                    :multiple="false"
                  ></multiselect>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 multiselect-border-shadow">
                <div class="dropdown-wrapper">
                  <label class="dropdown-label text-capitalize">Status</label>
                  <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                    v-model="status"
                    tag-placeholder=""
                    placeholder=""
                    label="name"
                    track-by="id"
                    :options="statuses"
                    :multiple="false"
                  ></multiselect>
                </div>
              </div>
            </div>

            <div class="mb-24">
              <div class="text-start">
                <div class="table-responsive">
                  <table class="border">
                    <tr>
                      <th>
                        <div>
                          <input
                            class="form-check-input me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                      </th>
                      <th>Action</th>
                      <th>Building</th>
                      <th>Unit</th>
                      <th>Fname</th>
                      <th>Lname</th>
                      <th>Email</th>
                      <th>Phone</th>
                    </tr>

                    <tr class="border_bottom">
                      <td>
                        <div>
                          <input
                            class="form-check-input me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                      </td>
                      <td>
                        <img src="/images/icons/edit.png" />
                      </td>
                      <td>
                        <span class="text-medium color-secondary"
                          >238 N Pine</span
                        >
                      </td>
                      <td>
                        <div class="text-medium color-secondary">1B</div>
                      </td>

                      <td>
                        <div class="text-medium color-secondary">PunkegO</div>
                      </td>

                      <td>
                        <div class="text-medium color-secondary">
                          Oriantation
                        </div>
                      </td>

                      <td>
                        <div class="text-medium color-secondary">
                          punkaj@gmail.com
                          <span class="color-primary">(Verified)</span>
                        </div>
                      </td>

                      <td>
                        <div class="text-medium color-secondary">
                          321-456-6789
                          <span class="color-danger">(Not Verified)</span>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="text-center">
              <div class="d-flex justify-content-center mb-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="rentNotice"
                />
                <label
                  class="text-medium align-self-center mt-8"
                  for="rentNotice"
                  >Is this related to rent notice</label
                >
              </div>

              <button class="btn btn-primary" @click="next(2)">Next</button>
            </div>
          </div>

          <div v-if="page2">
            <div class="w-75 mx-auto mb-72">
              <div class="row mb-28">
                <div class="col-lg-3 color-secondary text-normal-bold-md mb-12">
                  Select task priority
                </div>
                <div class="col-lg-9">
                  <div class="row g-2 mb-24">
                    <div class="col-lg-6" v-for="item in 4">
                      <div class="form-check d-flex">
                        <input
                          class="form-check-input align-self-center me-12"
                          type="radio"
                          name="flexRadioDefault"
                          :id="'flexRadioDefault1' + item"
                          checked
                        />
                        <label
                          class="form-check-label text-normal align-self-center color-secondary"
                          :for="'flexRadioDefault1' + item"
                        >
                          Yellow : Daily Popup Reminder
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-28">
                <div class="col-lg-3 color-secondary text-normal-bold-md mb-12">
                  Signature type
                </div>
                <div class="col-lg-9">
                  <div class="row g-2 mb-24">
                    <div class="col-lg-6" v-for="item in 5">
                      <div class="form-check d-flex">
                        <input
                          class="form-check-input align-self-center me-12"
                          type="radio"
                          name="flexRadioDefault2"
                          :id="'flexRadioDefault' + item"
                          checked
                        />
                        <label
                          class="form-check-label text-normal align-self-center color-secondary"
                          :for="'flexRadioDefault' + item"
                        >
                          E-Sign
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center">
              <button class="btn btn-dark me-20 mb-12" @click="back(2)">
                &lt;&lt; Back
              </button>
              <button class="btn btn-primary mb-12" @click="next(3)">
                Next
              </button>
            </div>
          </div>

          <div v-if="page3">
            <div class="row mb-24">
              <div class="col-lg-7 col-md-10">
                <div class="version-template p-24 table-responsive">
                  <table>
                    <tr>
                      <th>Template Name</th>
                      <th>Template version</th>
                      <th>Document ID#</th>
                      <th>Created Date</th>
                    </tr>
                    <tr>
                      <td>Lease 9-16-20</td>
                      <td>2020_09_16_1</td>
                      <td>20200916154</td>
                      <td>09/16/2020</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row g-4 mb-28">
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-header">PDF File</div>
                  <div
                    class="card-body"
                    style="overflow-y: scroll; height: 400px"
                  >
                    <vue-pdf-embed
                      class="border"
                      :source="'/docs/testpdfFile.pdf'"
                    />
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div>
                  <p class="text-normal-bold-md color-secondary mb-4">
                    versions
                  </p>
                  <p class="text-normal-bold-md color-primary mb-10">
                    version 2
                  </p>
                  <div class="row p-12 bkg-primary border-standered mb-16">
                    <div class="col-9">
                      <h3 class="h3 align-self-center mb-0 text-white">
                        Title 01
                      </h3>
                    </div>
                    <div
                      class="col-3 align-self-center text-normal-bold-md text-white"
                    >
                      preview
                    </div>
                  </div>

                  <div>
                    <div class="row justify-content-end">
                      <div class="col-11">
                        <p class="text-normal-bold-md color-secondary mb-4">
                          Amendments
                        </p>

                        <p class="text-normal-bold-md text-dark mb-4">
                          Amendment 1
                        </p>
                        <div
                          class="row p-12 bkg-secondary border-standered mb-16"
                        >
                          <div class="col-9">
                            <h3 class="h3 align-self-center mb-0 text-dark">
                              Title 01
                            </h3>
                          </div>
                          <div
                            class="col-3 align-self-center text-normal-bold-md color-primary"
                          >
                            preview
                          </div>
                        </div>

                        <p class="text-normal-bold-md text-dark mb-4">
                          Amendment 2
                        </p>
                        <div
                          class="row p-12 bkg-secondary border-standered mb-16"
                        >
                          <div class="col-9">
                            <h3 class="h3 align-self-center mb-0 text-dark">
                              Title 02
                            </h3>
                          </div>
                          <div
                            class="col-3 align-self-center text-normal-bold-md color-primary"
                          >
                            preview
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div>
                    <p class="text-normal-bold-md color-secondary mb-10">
                      versions
                    </p>
                    <p class="text-normal-bold-md color-primary mb-10">
                      version 1
                    </p>
                    <div class="row p-12 bkg-secondary border-standered mb-16">
                      <div class="col-9">
                        <h3 class="h3 align-self-center mb-0 text-dark">
                          Title 01
                        </h3>
                      </div>
                      <div
                        class="col-3 align-self-center text-normal-bold-md color-primary"
                      >
                        preview
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-dark me-20 mb-12" @click="back(3)">
                &lt;&lt; Back
              </button>
              <button class="btn btn-primary mb-12">Send</button>
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
      page1: true,
      page2: false,
      page3: false,
      unit: "",
      status: "",
      statuses: [
        {
          name: "status 1",
        },
        {
          name: "status 2",
        },
        {
          name: "status 3",
        },
        {
          name: "status 4",
        },
        {
          name: "status 5",
        },
        {
          name: "status 6",
        },
      ],
      units: [
        {
          name: "unit 1",
        },
        {
          name: "unit 2",
        },
        {
          name: "unit 3",
        },
        {
          name: "unit 4",
        },
        {
          name: "unit 5",
        },
        {
          name: "unit 6",
        },
        {
          name: "unit 7",
        },
      ],
    };
  },
  methods: {
    next(value) {
      switch (value) {
        case 2:
          this.page1 = false;
          this.page2 = true;
          this.page3 = false;
          break;

        case 3:
          this.page1 = false;
          this.page2 = false;
          this.page3 = true;
          break;
      }
    },
    back(value) {
      switch (value) {
        case 2:
          this.page1 = true;
          this.page2 = false;
          this.page3 = false;
          break;

        case 3:
          this.page1 = false;
          this.page2 = true;
          this.page3 = false;
          break;
      }
    },

    goDirect(value) {
      switch (value) {
        case 1:
          this.page1 = true;
          this.page2 = false;
          this.page3 = false;
          break;

        case 2:
          this.page1 = false;
          this.page2 = true;
          this.page3 = false;
          break;

        case 3:
          this.page1 = false;
          this.page2 = false;
          this.page3 = true;
          break;
      }
    },
  },
};
</script>
