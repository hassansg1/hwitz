<template>
  <div
    class="documents-packets modal fade"
    id="amendTemplateModal"
    tabindex="-1"
    aria-labelledby="amendTemplateModal"
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
          <div class="px-lg-5 px-md-3">
            <div class="text-center mb-50">
              <h1 class="h1 mb-12">Amend template</h1>
              <p class="text-medium-md color-secondary mb-0">
                Amend past templates and update your documents
              </p>
            </div>

            <div class="row mb-24">
              <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                  <label for="input1" class="form-label">Template name</label>
                  <input type="text" class="form-control" id="input1" />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-2 col-md-4 col-5">
                <p class="text-normal-bold-md color-secondary mb-20">
                  Template Type
                </p>
              </div>
              <div class="col-lg-10 col-md-8">
                <div class="d-flex flex-row flex-wrap">
                  <div class="form-check d-flex me-20">
                    <input
                      class="form-check-input align-self-center me-12"
                      type="radio"
                      name="flexRadioDefault"
                      id="flexRadioDefault1"
                      checked
                    />
                    <label
                      class="form-check-label text-normal align-self-center color-secondary"
                      for="flexRadioDefault1"
                    >
                      Yellow : Daily Popup Reminder
                    </label>
                  </div>
                  <div class="form-check d-flex">
                    <input
                      class="form-check-input align-self-center me-12"
                      type="radio"
                      name="flexRadioDefault"
                      id="flexRadioDefault2"
                      checked
                    />
                    <label
                      class="form-check-label text-normal align-self-center color-secondary"
                      for="flexRadioDefault2"
                    >
                      Yellow : Daily Popup Reminder
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-8">
              <div class="col-12">
                <p class="text-normal-bold-md color-secondary mb-8">
                  Description
                </p>
                <p class="text-normal-bold-md color-secondary-light mb-20">
                  (Instructions: Create new content or copy and paste from
                  another applictation. To add merge fields, click on the short
                  code then paste (CNTRL + V))
                </p>
              </div>
            </div>

            <div class="row mb-24">
              <div class="col-lg-9">
                <ckeditor
                  :editor="editor"
                  v-model="editorData"
                  :config="editorConfig"
                ></ckeditor>
              </div>
            </div>

            <div class="mb-24">
              <h3 class="h3">Short Codes</h3>
              <div class="row g-3">
                <div class="col-lg-3 col-md-6" v-for="item in 5">
                  <div class="row">
                    <div class="col-12">
                      <p class="text-medium-bold">Initial {{ item }}</p>
                      <div class="mb-16" v-for="item in 10">
                        <img src="/images/icons/clipboard.svg" alt="icon" />
                        <span class="text-medium">
                          &#123; &#123; code {{ item }} &#125; &#125;</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-28">
              <div class="row">
                <div class="col-12">
                  <p class="text-normal-bold-md color-secondary">
                    Who is signing this template?
                  </p>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-lg-7">
                  <div class="row g-3">
                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Guarantor
                        </p>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Supplier
                        </p>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Service provider (Laundry, Security & Misc)
                        </p>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Service provider (TV)
                        </p>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Service provider (Phone)
                        </p>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="d-flex">
                        <div>
                          <input
                            class="form-check-input align-self-center me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>
                        <p
                          class="text-normal-bold-md align-self-center mb-0 color-secondary"
                        >
                          Service provider (Data)
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-70">
              <div class="row g-3">
                <div class="col-lg-7 col-md-8">
                  <div class="row">
                    <div class="col-6">
                      <p class="text-normal-bold-md color-secondary">
                        Portfolio wide
                      </p>
                      <div class="d-flex flex-row flex-wrap">
                        <div class="d-flex me-20">
                          <div>
                            <input
                              class="form-check-input align-self-center me-12"
                              type="radio"
                              value=""
                              name="Portfolio"
                              id="flexradioYes"
                            />
                          </div>
                          <label
                            class="align-self-center text-normal color-secondary"
                            for="flexradioYes"
                            >Yes</label
                          >
                        </div>
                        <div class="d-flex">
                          <div>
                            <input
                              class="form-check-input align-self-center me-12"
                              type="radio"
                              value=""
                              name="Portfolio"
                              id="flexradioNo"
                            />
                          </div>
                          <label
                            class="align-self-center text-normal color-secondary"
                            for="flexradioNo"
                            >No</label
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <p class="text-normal-bold-md color-secondary">
                        Signature request
                      </p>
                      <div class="d-flex flex-row flex-wrap">
                        <div class="d-flex me-20">
                          <div>
                            <input
                              class="form-check-input align-self-center me-12"
                              type="radio"
                              value=""
                              name="Signature"
                              id="SignatureYes"
                            />
                          </div>
                          <label
                            class="align-self-center text-normal color-secondary"
                            for="SignatureYes"
                            >Yes</label
                          >
                        </div>
                        <div class="d-flex">
                          <div>
                            <input
                              class="form-check-input align-self-center me-12"
                              type="radio"
                              value=""
                              name="Signature"
                              id="SignatureNo"
                            />
                          </div>
                          <label
                            class="align-self-center text-normal color-secondary"
                            for="SignatureNo"
                            >No</label
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-5">
                  <div class="">
                    <div style="max-height: 200px; overflow: auto">
                      <div class="d-flex mb-12">
                        <input
                          class="form-check-input align-self-center me-12"
                          type="checkbox"
                          value=""
                          id="flexCheckDefault"
                        />
                        <a class="a-link">select all</a>
                      </div>

                      <div class="d-flex mb-12" v-for="item in 5">
                        <input
                          class="form-check-input align-self-center me-12"
                          type="checkbox"
                          value=""
                          id="flexCheckDefault"
                        />
                        <div class="template-avatar align-self-center me-8">
                          <img
                            src="/images/test-avatar.png"
                            class="avatar-img"
                            alt=""
                          />
                        </div>
                        <div class="align-self-center">
                          <p class="text-medium-bold mb-0">Emma Jones</p>
                          <p class="color-primary text-normal mb-0">
                            123-345-5678
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-28">
              <div class="col-12 text-end">
                <button class="btn btn-dark me-20 mb-12">cencel</button>
                <button class="btn btn-primary me-20 mb-12">
                  Save & Go Back
                </button>
                <button class="btn btn-primary mb-12">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePdfEmbed from "vue-pdf-embed/dist/vue2-pdf-embed";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

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
      editor: ClassicEditor,
      editorData: "<p>Content of the editor.</p>",
      editorConfig: {
        // The configuration of the editor.
      },
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
  },
};
</script>
