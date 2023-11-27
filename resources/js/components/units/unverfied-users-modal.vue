<template>
  <div>
    <div
        class="modal fade unit-modal"
        id="unverified-users-modal"
        tabindex="-1"
        aria-labelledby="unitOccupiedModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            Unverified Users
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
                <div id="unverified_div" class="table-responsive mb-28">
                  <table>
                    <thead>
                    <tr>
                      <th>Unit</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="unit in users">
                      <td>
                        {{ unit.unit_no }}
                      </td>
                      <td>
                        {{ unit.firstname }} {{ unit.lastname }}
                      </td>
                      <td :class="{ red: unit.email_verified === 0 }">
                        {{ unit.email }}
                      </td>
                      <td :class="{ red: unit.mobile_verification === 'No' }">
                        {{ unit.mobile }}
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button @click="printDiv('unverified_div')" class="btn bkg-primary">Print Report</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  data() {
    return {
      "users": {},
    };
  },
};
</script>
