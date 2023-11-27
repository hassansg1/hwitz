<template>
  <div>
    <div
      class="modal fade staff-modal"
      id="staffModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
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
            <div class="mb-27">
              <h3 class="h3 mb-13">Assign buildings</h3>
              <p class="mb-0 text-medium-bold color-secondary">
              </p>
            </div>

            <div class="row text-start g-3 mb-70">
              <div
                class="col-xl-3 col-lg-4 col-mg-6"
                v-for="building in buildings"
              >
                <div class="card">
                  <img
                    src="/images/card-head.png"
                    class="card-img-top"
                    alt="..."
                  />

                  <div class="card-body">
                    <div class="d-flex justify-content-between mb-8">
                      <div class="d-flex">
                        <h3 class="h3 mb-0 building-heading me-12">
                          {{ building.name }}
                        </h3>
                        <div class="me-12">
                          <img
                            class="select-cursor"
                            src="/images/icons/warning.png"
                            alt="warning"
                            id="dropdownMenuOffset"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                          />

                          <div class="dropdown">
                            <ul
                              class="dropdown-menu dropdown-menu-end border-standered"
                              aria-labelledby="dropdownMenuOffset"
                            >
                              <li
                                class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                              >
                                Attention Needed
                              </li>
                              <li class="px-2 py-1 border-bottom text-sm-bold">
                                <span class="color-danger">335</span> Unverified
                                users
                              </li>
                              <li class="px-2 py-1 border-bottom text-sm-bold">
                                <span class="color-danger">35</span> Urgent
                                tasks
                              </li>
                              <li class="px-2 py-1 border-bottom text-sm-bold">
                                <span class="color-danger">12</span> Overdue
                                regular tasks
                              </li>
                              <li class="px-2 py-1 border-bottom text-sm-bold">
                                <span class="color-danger">07</span> Documents
                                pending to approve
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div>
                        <img src="/images/icons/edit.png" alt="edit" />
                      </div>
                    </div>
                    <p class="text-sm-bold color-secondary-dark mb-32">
                      Lorem ipsum dolor sit amet consectetur,
                    </p>

                    <div class="d-flex justify-content-between">
                      <div>
                        <p class="text-normal-bold-md color-secondary mb-6">
                          Staff assigned
                        </p>
                        <div class="staff-list">
                          <div class="mb-33">
                            <div
                              class="d-inline avatar-sm"
                              v-if="
                                building.staff_users &&
                                building.staff_users.length > 0
                              "
                              v-for="users in building.staff_users"
                            >
                              <img
                                :src="
                                  users.profile_picture
                                    ? users.profile_picture
                                    : '/images/default-user.jpg'
                                "
                                class="avatar-img"
                                :title="users.firstname + ' ' + users.lastname"
                              />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div>
                        <p class="text-normal-bold-md color-secondary mb-6">
                          Assigned
                        </p>
                        <div class="form-check">
                          <input
                            class="form-check-input assign-checkbox"
                            type="checkbox"
                            value=""
                            id="assigned"
                            v-model="building.is_assigned_to_user"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="action-btn" class="d-flex justify-content-end">
              <button class="btn btn-dark me-36" data-bs-dismiss="modal">
                Cancel
              </button>
              <button
                class="btn bkg-primary text-white"
                @click="assignBuildingToUser"
              >
                Save
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
  name: "StaffModal",
  props: ["buildings", "assigned_building_ids"],
  computed: {
    getCheckedValue() {
      return (item) => {
        return this.assigned_building_ids.includes(item);
      };
    },
  },
  data() {
    return {};
  },
  methods: {
    assignBuildingToUser() {
      const plucked = this.buildings.map((building) => ({
        building_id: building.id,
        is_assigned_to_user: building.is_assigned_to_user,
      }));
      console.log(plucked);
      this.showLoader();
      this.$http
        .post("/assignBuildings/" + this.$route.params.id, plucked)
        .then((response) => {
          Toast.fire({
            icon: "success",
            title: "Changes saved successfully.",
          });
          this.$parent.fetchAssignedBuildings();
          $("#staffModal").modal("hide");
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  created() {},
};
</script>
