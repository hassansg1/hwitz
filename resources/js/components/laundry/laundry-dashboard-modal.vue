<template>
  <div class="packages-section">
    <div
      class="modal fade"
      id="lDashboardModal"
      tabindex="-1"
      aria-labelledby="lDashboardModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-40">
              <h1 class="h1 mb-12">Machine #54 reservations</h1>
              <p class="w-50 mx-auto text-normal color-secondary">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Aliquid, nihil esse a sapiente inventore delectus fugiat.
                Reprehenderit laborum animi quo.
              </p>
            </div>
            <div class="d-flex flex-row flex-wrap mb-28">
              <div
                class="align-self-center text-normal-bold-md color-secondary me-12 mb-12"
              >
                Entries
              </div>
              <div
                class="badge-gray me-12 mb-12"
                v-for="index in 4"
                :class="{ active: index == selectedItem }"
                @click="selectIt(index)"
              >
                Last {{ index + 5 }} days
              </div>
            </div>
            <div class="table-responsive mb-28">
              <table>
                <tr>
                  <th>Date</th>
                  <th>Laundry Room Appliance</th>
                  <th>Go to:</th>
                  <th>Transaction Type</th>
                  <th>Charge</th>
                  <th>Running Balance</th>
                  <th>Proof</th>
                </tr>
                <tr class="border_bottom" v-for="index in selectedItem">
                  <td>
                    <span class="text-medium color-secondary-dark"
                      >April 13, 2023</span
                    >
                  </td>

                  <td>
                    <span class="text-medium color-secondary-dark"
                      >238 N Pine Washer 1</span
                    >
                  </td>

                  <td>
                    <span class="text-medium-bold color-primary"
                      >User profile</span
                    >
                  </td>

                  <td>
                    <span class="text-medium color-secondary-dark"
                      >Appliance Start</span
                    >
                  </td>

                  <td>
                    <span class="text-medium color-danger">$10</span>
                  </td>

                  <td>
                    <span class="text-medium color-primary">$10</span>
                  </td>

                  <td>
                    <div class="camera-pic-container-sm position-relative pt-7">
                      <img
                        src="/images/camera-pic.png"
                        class="avatar-img"
                        alt=""
                      />
                      <img
                        src="/images/icons/expand.png"
                        class="position-absolute select-cursor expand-icon"
                        alt=""
                      />
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <!-- <div class="d-flex justify-content-center">
              <span class="pagination-btn">
                <img src="/images/icons/left-arrow.png" />
              </span>
              <span class="pagination-btn active">1</span>
              <span class="pagination-btn">2</span>
              <span class="pagination-btn">3</span>
              <span class="pagination-btn">
                <img src="/images/icons/right-arrow.png" />
              </span>
            </div> -->
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
      selectedItem: 1,
    };
  },
  methods: {
    selectIt(index) {
      this.selectedItem = index;
    },
  },
};
</script>
