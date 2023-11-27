<template>
  <div class="mt-44 user-notification system-notification">
    <div class="table-responsive mb-44">
      <table>
        <tr>
          <th>Building</th>
          <th>Laundry out of order</th>
          <th>Work Order</th>
          <th>FOB Failure</th>
          <th>Porting Request</th>
          <th>Tilt/Impact Laundry</th>
        </tr>
        <tr>
          <td>
            <div class="d-flex">
              <div class="form-check me-12">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  checked
                />
              </div>
              <div class="text-medium-bold align-self-center">139 N Pine</div>
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
              <div class="mt-auto mb-6 me-12">
                <p class="text-normal user mb-4 d-none">John Doe</p>

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
