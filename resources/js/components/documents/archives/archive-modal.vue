<template>
  <div
    class="documents-packets archives modal fade"
    id="archiveModal"
    tabindex="-1"
    aria-labelledby="archiveModal"
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
              <h1 class="h1 mb-12">All envelope documents</h1>
              <p class="text-medium-md color-secondary mb-0">
                Lorem ipsum dolor sit amet.
              </p>
            </div>

            <div class="table-responsive mb-40">
              <table>
                <tr>
                  <th>Signatory</th>
                  <th>Unsigned document</th>
                  <th>Signed document</th>
                  <th>Certificate</th>
                  <th>Check Sum</th>
                </tr>
                <tr v-for="item in 4">
                  <td>Two guarantor</td>
                  <td>Amended Urban {{ item }}.PDF</td>
                  <td>Amended Urban {{ item }}.PDF</td>
                  <td>
                    <div class="d-flex">
                      <div class="template-avatar me-8">
                        <img
                          src="/images/icons/certificate.png"
                          class="avatar-img"
                          alt="icon"
                        />
                      </div>
                      <div class="align-self-center color-primary">
                        Urban sky
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex justify-content-between">
                      <div class="me-12 color-primary">Zip</div>
                      <div>
                        <button class="btn btn-primary">Download</button>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>

            <div class="d-flex justify-content-center">
              <button class="btn btn-dark me-12">cencel</button>
              <button class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePdfEmbed from "vue-pdf-embed/dist/vue2-pdf-embed";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    VuePdfEmbed,
    VueEditor,
  },
  data() {
    return {
      page1: true,
      page2: false,
      page3: false,
      unit: "",
      status: "",
      content: "<p>Some initial content</p>",
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
