<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="extension-history-modal"
        tabindex="-1"
        aria-labelledby="extensionOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            Extension History
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
          </div>

          <div class="modal-body text-start">
            <div class="row">
              <div class="mb-66">
                <div id="extension_history_div" class="table-responsive mb-28">
                  <table>
                    <thead>
                    <tr>
                      <th>Extended By
                      </th>
                      <th>Old Date
                      </th>
                      <th>Extended date
                      </th>
                      <th>Date
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="data in history"
                        class="border_bottom">
                      <td class="text-medium">
                        {{ data.user.name }} ({{ data.user.usertype.name }})
                      </td>
                      <td class="text-medium">
                        {{ formatDateTime(data.previous_time) }} Midnight
                      </td>
                      <td class="text-medium">
                        {{ formatDateTime(data.extended_time) }} Midnight
                      </td>
                      <td class="text-medium">
                        {{ formatDateTime(data.created_at) }}
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button @click="printDiv('extension_history_div')" class="btn bkg-primary mr-10">Print Report</button>
              <button data-bs-dismiss="modal"
                      aria-label="Close"
                      class="btn bkg-danger">Close
              </button>
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
    return {
      history: {},
      packageId: null,
    };
  },
  expose: ["viewExtensionHistory"],
  methods: {
    viewExtensionHistory(packageId) {
      this.showLoader();
      let params = {packageId: packageId};
      this.$http
          .post("/viewExtensionHistory", params)
          .then((response) => {
            this.history = response.data.data;
            $('#extension-history-modal').modal('show');
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    }
  },
};
</script>
