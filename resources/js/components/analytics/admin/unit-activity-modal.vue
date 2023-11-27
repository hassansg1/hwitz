<template>
  <div class="a-admin-components">
    <div
      class="modal fade"
      id="activityModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h3 class="h3 mb-13" v-if="data.unit && data.unit.building">
              Unit activity for {{ data.unit.building.name ?? "" }} ({{
                data.unit.unit_no
              }}), {{ data.unit.building.city }} {{ data.unit.building.state }}
              {{ data.unit.building.zipcode }}
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
                    <th>User Type</th>
                    <th>Action</th>
                    <th>Performed By</th>
                    <th>Action Date</th>
                    <th>Fobs affected by action</th>
                  </tr>
                  <tr v-for="data in data.items" class="border_bottom">
                    <td>
                      <span class="text-medium" v-if="data.triggeredBy">
                        {{ data.triggeredBy.usertype.name }}
                      </span>
                    </td>
                    <td>{{ data.entity_name }} {{ data.action_name }}</td>
                    <td>
                      <span class="text-medium" v-if="data.triggeredBy">{{
                        data.triggeredBy.name
                      }}</span>
                    </td>
                    <td>
                      <span class="text-medium">{{
                        formatDate(data.triggered_at)
                      }}</span>
                    </td>
                    <td>
                      <li v-for="fob in data.fobsList">Fob # {{ fob }}</li>
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
  expose: ["loadUnitActivityData"],
  methods: {
    loadUnitActivityData(unitId) {
      this.showLoader();
      let params = { unit_id: unitId };
      this.$http
        .post("/loadUnitActivityData", params)
        .then((response) => {
          this.data = response.data;
          console.log(this.data.unit);
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
