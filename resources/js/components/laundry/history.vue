<template>
    <div class="a-admin-components">
      <div
        class="modal fade"
        id="laundryHistory"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <h3 class="h3 mb-13">
                History
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
                        <tr v-for="history in data" class="border_bottom">
                            <td>
                              <div>
                                <strong>Date & Time:</strong>
                                {{ formatDateTime(history.created)}}
                                <span v-if="history.state == 1 && history.status == 1">Washer 1 goes online</span>
                                <span v-if="history.state == 2 && history.status == 0">Washer 1 is broken and goes out of order</span>
                              </div>
                              <div v-if="history.comment">
                                  <span>Comment : </span>
                                  <span>{{ history.comment }}</span>
                              </div>
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
        data: [],
      };
    },
    expose: [],
    methods: {
        loadData(id){
            this.showLoader();
            this.$http.get("/getLaundryHistory/"+id)
                .then((response) => {
                    this.data = response.data;
                    $('#laundryHistory').modal('show');
                    this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
                });
        }
    },
  };
  </script>
  