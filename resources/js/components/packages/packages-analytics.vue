<template>
  <div class="packages-section">
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
          <th>Locker</th>
          <th>Provider</th>
          <th>Picker</th>
          <th>Picked</th>
          <th>Picked up by</th>
          <th>Drop off at</th>
          <th>Picked up at</th>
          <th>Pick up video</th>
          <th>Package Picture</th>
        </tr>
        <tr class="border_bottom" v-for="index in selectedItem">
          <td>
            <span class="text-medium color-primary">09138</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark">FedEx</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark">John Doe</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark">Yes</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark">John Doe</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark"
              >April 13, 2023 10:30 am</span
            >
          </td>
          <td>
            <span class="text-medium color-secondary-dark"
              >April 13, 2023 10:30 am</span
            >
          </td>

          <td>
            <span class="text-medium color-primary">Play video</span>
          </td>

          <td>
            <div class="camera-pic-container-sm position-relative pt-7">
              <img src="/images/camera-pic.png" class="avatar-img" alt="" />
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
