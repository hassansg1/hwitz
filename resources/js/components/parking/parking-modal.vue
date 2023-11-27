<template>
  <div class="parking-section">
    <div
      class="modal fade"
      id="parkingModal"
      tabindex="-1"
      aria-labelledby="parkingModal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
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
            <div class="table-responsive mb-28">
              <table>
                <tr>
                  <th>Lot No.</th>
                  <th>Assign to</th>
                  <th>Status</th>
                  <th>Video Date</th>
                  <th>Proof</th>
                </tr>
                <tr class="border_bottom">
                  <td>
                    <span class="text-medium color-primary">09138</span>
                  </td>
                  <td class="w-50">
                    <span class="text-medium">John Doe</span>
                  </td>

                  <td>
                    <span class="text-medium occupied">occupied</span>
                  </td>

                  <td>
                    <span class="text-medium color-primary">Play video</span>
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
                <tr class="border_bottom">
                  <td>
                    <span class="text-medium color-primary">09138</span>
                  </td>
                  <td class="w-50">
                    <span class="text-medium">John Doe</span>
                  </td>

                  <td>
                    <span class="text-medium occupied">occupied</span>
                  </td>

                  <td>
                    <span class="text-medium color-primary">Play video</span>
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
                <tr class="border_bottom">
                  <td>
                    <span class="text-medium color-primary">09138</span>
                  </td>
                  <td class="w-50">
                    <span class="text-medium">John Doe</span>
                  </td>

                  <td>
                    <span class="text-medium occupied">occupied</span>
                  </td>

                  <td>
                    <span class="text-medium color-primary">Play video</span>
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
            <div class="d-flex justify-content-center">
              <span class="pagination-btn">
                <img src="/images/icons/left-arrow.png" />
              </span>
              <span class="pagination-btn active">1</span>
              <span class="pagination-btn">2</span>
              <span class="pagination-btn">3</span>
              <span class="pagination-btn">
                <img src="/images/icons/right-arrow.png" />
              </span>
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
    return {};
  },
  methods: {},
};
</script>
