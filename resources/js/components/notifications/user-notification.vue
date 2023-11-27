<template>
  <div class="mt-44 user-notification">
    <div>
      <div class="text-normal color-secondary mb-18">
        Entries per page <span class="color-dark">09</span>
      </div>
    </div>
    <div class="table-responsive mb-44">
      <table>
        <tr>
          <th>Residents</th>
          <th>Mail Code</th>
          <th>Entry</th>
          <th>Message Phone</th>
        </tr>
        <tr>
          <td>
            <div class="d-flex">
              <div class="avatar-sm me-8">
                <img src="/images/test-avatar.png" class="avatar-img" alt="" />
              </div>
              <div class="text-medium-bold align-self-center">Liam Smith</div>
            </div>
          </td>
          <td>
            <div class="d-flex">
              <div class="me-4" v-for="index in 4">
                <p class="text-normal user mb-4">John Doe</p>
                <div class="avatar-md">
                  <img
                    src="/images/test-avatar.png"
                    class="avatar-img"
                    alt=""
                  />
                </div>
              </div>
              <div class="mt-auto mb-6">
                <div
                  class="add-new select-cursor"
                  data-bs-toggle="modal"
                  data-bs-target="#notificationModal"
                >
                  <i class="fa fa-plus"></i>
                </div>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex">
              <div class="me-4" v-for="index in 1">
                <p class="text-normal user mb-4">John Doe</p>
                <div class="avatar-md">
                  <img
                    src="/images/test-avatar.png"
                    class="avatar-img"
                    alt=""
                  />
                </div>
              </div>
              <div class="mt-auto mb-6">
                <div
                  class="add-new select-cursor"
                  data-bs-toggle="modal"
                  data-bs-target="#notificationModal"
                >
                  <i class="fa fa-plus"></i>
                </div>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex">
              <div class="me-4" v-for="index in 4">
                <p class="text-normal user mb-4">John Doe</p>
                <div class="avatar-md">
                  <img
                    src="/images/test-avatar.png"
                    class="avatar-img"
                    alt=""
                  />
                </div>
              </div>
              <div class="mt-auto mb-6 me-12">
                <div class="more-users">+4</div>
              </div>
              <div class="mt-auto mb-6">
                <div
                  class="add-new select-cursor"
                  data-bs-toggle="modal"
                  data-bs-target="#notificationModal"
                >
                  <i class="fa fa-plus"></i>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <div class="d-flex justify-content-end mb-44">
      <button class="btn btn-dark">Update Notifications</button>
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
import assignNotificationModal from "./assign-notification-modal.vue";

export default {};
</script>
