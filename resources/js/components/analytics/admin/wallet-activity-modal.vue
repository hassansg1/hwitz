<template>
  <div class="a-admin-components">
    <div
      class="modal fade"
      id="walletActivityModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h3 class="h3 mb-13" v-if="data.wallet">
              Wallet activity for {{ data.wallet.nick_name }}
            </h3>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-27">
              <div class="table-responsive mb-28">
                <table>
                  <tr>
                    <th>Action</th>
                    <th>Action Date</th>
                    <th>Performed By</th>
                  </tr>
                  <tr v-for="data in data.items" class="border_bottom">
                    <td>
                      {{ data.wallet_logs }}
                    </td>
                    <td>
                      <span class="text-medium">{{
                        formatDateTime(data.created)
                      }}</span>
                    </td>
                    <td>
                      <span v-if="data.created_by" class="text-medium">{{
                        data.created_by.name
                      }}</span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div id="action-btn" class="d-flex justify-content-end">
              <button
                type="button"
                class="btn btn-dark me-36"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                Cancel
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
  created() {},
  data() {
    return {
      data: {},
    };
  },
  expose: ["walletActivityModal"],
  methods: {
    walletActivityModal(id) {
      this.showLoader();
      let params = { id: id };
      this.$http
        .post("/walletActivityModal", params)
        .then((response) => {
          this.data = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
          console.error(error);
        });
    },
  },
};
</script>
