<template>
  <div>
    <div
      class="modal fade task-modal"
      :id="'taskModal' + id"
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
          <div class="modal-body text-start">
            <div class="row">
              <div class="col-lg-8">
                <div class="d-flex mb-37">
                  <div>
                    <h3 class="h3 me-47">{{ task.name }}</h3>
                  </div>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Details
                  </p>
                  <textarea
                    name=""
                    id=""
                    rows="5"
                    v-html="processTaskSubject(task)"
                    disabled
                  ></textarea>
                </div>
              </div>
              <div class="col-lg-4">
                <p class="text-normal-bold-md color-secondary-dark mb-10">
                  Created by
                </p>
                <div class="person-wrapper mb-22">
                  <div class="d-flex">
                    <div class="d-flex me-27 mb-9">
                      <div class="avatar-sm me-8">
                        <img
                          :src="
                            task.created_by && task.created_by.profile_picture
                              ? task.created_by.profile_picture
                              : '/images/default-user.jpg'
                          "
                          class="avatar-img"
                          alt=""
                        />
                      </div>
                      <span class="align-self-center text-medium-bold">
                        {{
                          task.created_by
                            ? task.created_by.firstname +
                              " " +
                              task.created_by.lastname
                            : "System"
                        }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Date created
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ formatDate(task.created) }}
                        </p>
                      </div>
                    </div>

                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Due date
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ formatDate(task.expiry) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-34" v-if="task.task_state == 'C' ">
                  <div class="row">
                    <div class="col-lg-12 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Resolved By
                      </p>
                      <div class="person-wrapper mb-22">
                        <div class="d-flex">
                          <div class="d-flex me-27 mb-9">
                            <div class="avatar-sm me-8">
                              <img
                                :src="
                                  task.modified_by && task.modified_by.profile_picture
                                    ? task.modified_by.profile_picture
                                    : '/images/default-user.jpg'
                                "
                                class="avatar-img"
                                alt=""
                              />
                            </div>
                            <span class="align-self-center text-medium-bold">
                              {{
                                task.modified_by
                                  ? task.modified_by.firstname +
                                    " " +
                                    task.modified_by.lastname
                                  : "-"
                              }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-lg-12 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Resolved At
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ formatDate(task.modified) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <p class="text-normal-bold-md color-secondary-dark mb-10">
                  Assignee
                </p>
                <div class="person-wrapper mb-22">
                  <div class="d-flex flex-wrap">
                    <div class="d-flex me-27 mb-9">
                      <div class="avatar-sm me-8">
                        <img
                          :src="
                            task.target_user && task.target_user.profile_picture
                              ? task.target_user.profile_picture
                              : '/images/default-user.jpg'
                          "
                          class="avatar-img"
                          alt=""
                        />
                      </div>
                      <span class="align-self-center text-medium-bold">
                        {{
                          task.target_user
                            ? task.target_user.firstname +
                              " " +
                              task.target_user.lastname
                            : "-"
                        }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Building
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ task.building ? task.building.name : "-" }}
                        </p>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Unit
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ task.unit ? task.unit.unit_no : "-" }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row gx-5" v-if="task.task_state != 'C' ">
              <div class="col-lg-8"></div>
              <div
                class="col-lg-4 align-self-end justify-content-end text-md-start text-end mt-md-0 mt-2"
              >
                <!-- <button class="btn btn-dark me-24">Deny</button>
                <button class="btn btn-primary">Accept</button> -->
                <button
                  class="btn btn-primary width-btn"
                  @click="$parent.changeTaskState(task.id, 'completed', true)"
                >
                  Resolve
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
  },
  data() {
    return {
      source1: "/docs/pdf-test.pdf",
      workOptions: [
        {
          id: 1,
          type: "Created",
          isSelected: true,
        },
        {
          id: 2,
          type: "Working",
          isSelected: false,
        },
        {
          id: 3,
          type: "Stuck",
          isSelected: false,
        },
        {
          id: 4,
          type: "Completed",
          isSelected: false,
        },
      ],
      dataArray: [
        { id: 1, name: "john doe", image: "/images/worker.png" },
        { id: 2, name: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, name: "jane doe 2", image: "/images/Ellipse14.png" },

        { id: 4, name: "john doe", image: "/images/worker.png" },
        { id: 5, name: "jane doe", image: "/images/Ellipse14.png" },
        { id: 6, name: "jane doe 2", image: "/images/Ellipse1.png" },
      ],

      newdataArray: [
        { id: 1, value: "12.04.2022" },
        { id: 2, value: "12.05.2022" },
        { id: 3, value: "12.06.2022" },
        { id: 4, value: "12.07.2022" },
        { id: 5, value: "12.08.2022" },
        { id: 6, value: "12.09.2022" },
      ],
      building: [
        { id: 1, value: "128 A Pine" },
        { id: 2, value: "128 B Pine" },
        { id: 3, value: "128 C Pine" },
        { id: 4, value: "128 D Pine" },
        { id: 5, value: "128 E Pine" },
        { id: 6, value: "128 F Pine" },
      ],
      unit: [
        { id: 1, value: "Block A" },
        { id: 2, value: "Block B" },
        { id: 3, value: "Block C" },
        { id: 4, value: "Block D" },
        { id: 5, value: "Block E" },
        { id: 6, value: "Block F" },
      ],
    };
  },
  props: ["id", "task"],
};
</script>
