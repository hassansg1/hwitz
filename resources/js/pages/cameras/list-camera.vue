<template>
  <div id="camera-list">
    <div class="mb-24">
      <div class="d-flex text-normal color-secondary mb-12 count">
        <div class="align-self-center me-12">Entries per page:</div>

        <select name="" id="">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
    </div>

    <div class="mb-64">
      <div></div>
      <div class="table-responsive mb-28">
        <div class="row">
          <div class="col-xl-6 col-lg-8">
            <table>
              <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Proof</th>
              </tr>
              <tr class="border_bottom">
                <td>
                  <span class="text-medium color-primary">09138</span>
                </td>
                <td class="w-50">
                  <span class="text-medium">12.03.2023</span>
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
                  <span class="text-medium">12.03.2023</span>
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
                  <span class="text-medium">12.03.2023</span>
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
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <span class="text-normal color-secondary me-12 align-self-center"
          >Showing results 9 of 10</span
        >
        <span class="pagination-btn">
          <img src="/images/icons/left-arrow.png" />
        </span>
        <span class="pagination-btn active-btn">1</span>
        <span class="pagination-btn">2</span>
        <span class="pagination-btn">3</span>
        <span class="pagination-btn">
          <img src="/images/icons/right-arrow.png" />
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedBadge: 1,
    };
  },

  methods: {
    selectBadge(index) {
      this.selectedBadge = index;
    },
  },
};
</script>
