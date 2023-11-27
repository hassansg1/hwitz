<template>
  <div
    class="documents-packets modal fade"
    id="versionModal"
    tabindex="-1"
    aria-labelledby="versionModal"
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
            <h1 class="h1 mb-12">
              Lease template Template
              <img src="/images/icons/edit.png" alt="" />
            </h1>
          </div>

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
                <p class="text-normal-bold-md color-secondary mb-4">versions</p>
                <p class="text-normal-bold-md color-primary mb-10">version 2</p>
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
            <button class="btn btn-dark me-20 mb-12">Create Amendment</button>
            <button class="btn btn-primary mb-12">Edit</button>
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
    return {};
  },
  methods: {},
};
</script>
