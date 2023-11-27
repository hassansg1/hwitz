<template>
  <div class="a-admin-components">
    <div
      class="modal fade"
      id="userEntranceModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h3 class="h3 mb-13" v-if="data.user && data.user.usertype">
              User Entrances for
              {{ data.user.name }}
              ({{ data.user.usertype.name }})
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
                    <th>Building</th>
                    <th>Entrance</th>
                  </tr>
                  <tr v-for="data in data.items" class="border_bottom">
                    <td>
                      <span v-if="data.unit && data.unit.building">{{
                        data.unit.building.name
                      }}</span>
                    </td>
                    <td>
                      <span v-if="data.asset">{{ data.asset.name }}</span>
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
  expose: ["userEntranceModal"],
  methods: {
    userEntranceModal(id) {
      this.showLoader();
      let params = { id: id };
      this.$http
        .post("/userEntranceModal", params)
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
