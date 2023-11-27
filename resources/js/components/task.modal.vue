<template>
  <div
    class="modal fade task-modal-main"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl modal-customize">
      <div class="modal-content">
        <div class="modal-header border-bottom-0">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body text-start">
          <section id="modal-seceen-one">
            <div class="container py-5">
              <div class="row">
                <div class="col-lg-8">
                  <div class="d-flex mb-4">
                    <h1 class="main-heading">Windows cleaning</h1>
                    <div>
                      <div class="btn btn-secondary px-5">Created</div>
                      <button
                        type="button"
                        class="bg-white border-0 dropdown-toggle dropdown-toggle-split"
                        id="dropdownMenuReference"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-bs-reference="parent"
                      ></button>
                      <div
                        class="dropdown-menu p-2 shadow-sm"
                        aria-labelledby="dropdownMenuReference"
                      >
                        <div clss="mb-3">
                          <a class="btn btn-primary w-100 mb-2" href="#"
                            >Working</a
                          >
                        </div>
                        <div clss="mb-3">
                          <a class="btn btn-danger w-100 mb-2" href="#"
                            >Stuck</a
                          >
                        </div>
                        <div clss="mb-3">
                          <a class="btn btn-success w-100 mb-2" href="#"
                            >Completed</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="instructions" class="mb-4">
                    <p class="label">instructions</p>
                    <div class="description">
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adispiscing
                        elit. Nam hendrerit nisi sed sollicitudin. Lorem ipsum
                        dolor sit amet, consectetur adispiscing elit. Nam
                        hendrerit nisi sed sollicitudin. Lorem ipsum dolor sit
                        amet, consectetur adispiscing elit. Nam hendrerit nisi
                        sed sollicitudin.
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="row mb-4">
                    <div class="col-12">
                      <p class="label">Created by</p>
                      <div class="d-flex description">
                        <div>
                          <img
                            src="/images/Rectangle.png"
                            class="avator"
                            alt="avatar"
                          />
                        </div>
                        <p class="person-name">John Doe</p>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-6">
                      <p class="label">Date Created</p>
                      <div class="description">2023-05-30</div>
                    </div>
                    <div class="col-6">
                      <p class="label">Due Date</p>
                      <div class="description">2023-05-30</div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-12">
                      <p class="label">Assignee</p>
                      <div class="d-flex flex-wrap gap-3 description">
                        <div class="d-flex p-1">
                          <div>
                            <img
                              src="/images/Rectangle.png"
                              class="avator"
                              alt="avatar"
                            />
                          </div>
                          <p class="person-name">John Doe</p>
                        </div>
                        <div class="d-flex p-1">
                          <div>
                            <img
                              src="/images/Rectangle.png"
                              class="avator"
                              alt="avatar"
                            />
                          </div>
                          <p class="person-name">John Doe</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-6">
                      <p class="label">Building</p>
                      <div class="description">2023-05-30</div>
                    </div>
                    <div class="col-6">
                      <p class="label">Unit</p>
                      <div class="description">2023-05-30</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-8">
                  <div class="card">
                    <div class="card-header">PDF File</div>
                    <div
                      class="card-body"
                      style="overflow-y: scroll; height: 400px"
                    >
                      <vue-pdf-embed
                        class="border"
                        :source="'/test.pdf'"
                        disableAnnotationLayer="false"
                        disableTextLayer="false"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="mt-auto">
                    <button class="btn btn-dark">Deny</button>
                    <button class="btn btn-primary">Accept</button>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <hr class="my-5" />

          <section id="modal-seceen-two">
            <div class="container py-5">
              <div class="row">
                <div class="col-lg-8">
                  <div class="d-flex mb-4">
                    <h1 class="main-heading">Windows cleaning</h1>
                    <div>
                      <div class="btn btn-secondary px-5">Created</div>
                      <button
                        type="button"
                        class="bg-white border-0 dropdown-toggle dropdown-toggle-split"
                        id="dropdownMenuReference"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-bs-reference="parent"
                      ></button>
                      <div
                        class="dropdown-menu p-2 shadow-sm"
                        aria-labelledby="dropdownMenuReference"
                      >
                        <div clss="mb-3">
                          <a class="btn btn-primary w-100 mb-2" href="#"
                            >Working</a
                          >
                        </div>
                        <div clss="mb-3">
                          <a class="btn btn-danger w-100 mb-2" href="#"
                            >Stuck</a
                          >
                        </div>
                        <div clss="mb-3">
                          <a class="btn btn-success w-100 mb-2" href="#"
                            >Completed</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="instructions" class="mb-4">
                    <p class="label">instructions</p>
                    <div class="description">
                      <p>
                        Lorem ipsum dolor sit amet, consectetur adispiscing
                        elit. Nam hendrerit nisi sed sollicitudin. Lorem ipsum
                        dolor sit amet, consectetur adispiscing elit. Nam
                        hendrerit nisi sed sollicitudin. Lorem ipsum dolor sit
                        amet, consectetur adispiscing elit. Nam hendrerit nisi
                        sed sollicitudin.
                      </p>
                    </div>
                  </div>

                  <div id="attachments" class="mb-4">
                    <p class="label">Attachments</p>
                    <div class="d-flex gap-3">
                      <div
                        class="d-flex align-items-center text-center btn-attachment"
                      >
                        <div
                          class="mx-auto text-center text-white btn-attachment-icon"
                        >
                          +
                        </div>
                      </div>

                      <div>
                        <img
                          src="/images/Rectangle.png"
                          class="img-fluid"
                          alt=""
                        />
                      </div>
                      <div>
                        <img
                          src="/images/Rectangle.png"
                          class="img-fluid"
                          alt=""
                        />
                      </div>
                      <div>
                        <img
                          src="/images/Rectangle.png"
                          class="img-fluid"
                          alt=""
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="row mb-4">
                    <div class="col-12">
                      <p class="label">Created by</p>
                      <div class="d-flex description">
                        <div>
                          <img
                            src="/images/Rectangle.png"
                            class="avator"
                            alt="avatar"
                          />
                        </div>
                        <p class="person-name">John Doe</p>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-6">
                      <p class="label">Date Created</p>
                      <div class="description">2023-05-30</div>
                    </div>
                    <div class="col-6">
                      <p class="label">Due Date</p>
                      <div class="description">2023-05-30</div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-12">
                      <p class="label">Assignee</p>
                      <div class="d-flex flex-wrap gap-3 description">
                        <div class="d-flex p-1">
                          <div>
                            <img
                              src="/images/Rectangle.png"
                              class="avator"
                              alt="avatar"
                            />
                          </div>
                          <p class="person-name">John Doe</p>
                        </div>
                        <div class="d-flex p-1">
                          <div>
                            <img
                              src="/images/Rectangle.png"
                              class="avator"
                              alt="avatar"
                            />
                          </div>
                          <p class="person-name">John Doe</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-6">
                      <p class="label">Building</p>
                      <div class="description">2023-05-30</div>
                    </div>
                    <div class="col-6">
                      <p class="label">Unit</p>
                      <div class="description">2023-05-30</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row gx-5">
                <div class="col-md-8">
                  <div class="d-flex gap-3">
                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="avator"
                        alt="avatar"
                      />
                    </div>
                    <div class="comments p-2 w-100">
                      <textarea class="border-0" rows="5"></textarea>
                      <div>
                        <img src="/images/attachment.png" alt="" />
                        <img src="/images/emoji.png" alt="" />
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="col-md-4 align-self-end justify-content-end text-md-start text-end mt-md-0 mt-2"
                >
                  <button class="btn btn-dark px-4 pb-2">Comment</button>
                </div>
              </div>
            </div>
          </section>

          <hr class="my-5" />

          <section id="modal-seceen-four">
            <div class="row">
              <div class="mx-auto col-md-6">
                <h1 class="h1 text-center">Handicap parking request</h1>
                <p class="text-medium text-center color-secondary">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit.
                  Error, eius quia illo repudiandae inventore eveniet veniam
                  praesentium cupiditate assumenda ab!
                </p>

                <div class="d-flex justify-content-between mb-3">
                  <div>
                    <p class="h3 mb-0">Parking area</p>
                    <p class="text-normal color-primary mb-0">user name</p>
                  </div>
                  <div>
                    <div class="d-flex justify-content-end">
                      <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                          <img :src="'/images/profile.png'" />
                          <span class="verifiyIcon">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="10"
                              height="10"
                              viewBox="0 0 38 38"
                              fill="none"
                            >
                              <circle cx="19" cy="19" r="19" fill="#C2FF69" />
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M24.9228 14.761C25.2285 15.0455 25.2367 15.5148 24.9413 15.8091L17.5005 23.2214C17.3536 23.3677 17.1507 23.4495 16.9393 23.4474C16.728 23.4454 16.5268 23.3598 16.383 23.2106L13.0475 19.7515C12.7582 19.4516 12.7762 18.9826 13.0878 18.704C13.3993 18.4255 13.8863 18.4428 14.1756 18.7428L16.9583 21.6286L23.8344 14.7788C24.1299 14.4845 24.6172 14.4765 24.9228 14.761Z"
                                fill="black"
                              />
                            </svg>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="border mb-3 p-2 rounded-4">
                  <div class="d-flex">
                    <div class="align-self-center me-3">
                      <img src="/images/icons/clock.png" class="icon" alt="" />
                    </div>
                    <div>
                      <p class="mb-0 text-normal-bold-md color-secondary-light">
                        Assign to unit
                      </p>
                      <p class="mb-0 text-normal-bold-lg color-dark">
                        1D - Unit
                      </p>
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-center">
                  <button class="btn btn-dark me-1 px-4">Reject</button>
                  <button class="btn btn-primary px-4 ms-1">Approve</button>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <div class="mb-5">
            <div
              class="d-inline"
              style="
                height: 40px;
                width: 40px;
                border-radius: 50%;
                background-color: blue;
              "
            >
              <img src="/images/profile.png" />
            </div>
            <div
              class="d-inline"
              style="
                height: 40px;
                width: 40px;
                border-radius: 50%;
                background-color: blue;
                margin-left: -30px;
              "
            >
              <img src="/images/profile.png" />
            </div>
            <div
              class="d-inline"
              style="
                height: 40px;
                width: 40px;
                border-radius: 50%;
                background-color: blue;
                margin-left: -30px;
              "
            >
              <img src="/images/profile.png" />
            </div>
            <div
              class="d-inline"
              style="
                height: 40px;
                width: 40px;
                border-radius: 50%;
                background-color: blue;
                margin-left: -30px;
              "
            >
              <img src="/images/profile.png" />
            </div>
            <div
              class="d-inline"
              style="
                height: 40px;
                width: 40px;
                border-radius: 50%;
                background-color: blue;
                margin-left: -30px;
              "
            >
              <img src="/images/profile.png" />
            </div>
          </div>
          <div class="modal-body text-start">
            <div class="row">
              <div class="col-lg-8">
                <div class="d-flex mb-37">
                  <div>
                    <h3 class="h3 me-47">Windows cleaning</h3>
                  </div>

                  <div class="mb-8">
                    <div
                      id="dropdownMenuOffset"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <span v-for="option of workOptions">
                        <span
                          v-if="option.isSelected === true"
                          class="dropdown-item"
                          :class="[
                            { 'bkg-danger': option.type === 'Stuck' },
                            {
                              'bkg-success text-dark':
                                option.type === 'Completed',
                            },
                            { 'bkg-primary': option.type === 'Working' },
                            { 'bkg-gray': option.type === 'Created' },
                          ]"
                          >{{ option.type }}</span
                        >
                      </span>
                      <span>
                        <img src="/images/icons/down-arrow.png" alt="icon"
                      /></span>
                    </div>
                    <div class="dropdown">
                      <div
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="dropdownMenuOffset"
                      >
                        <div
                          v-for="option of workOptions"
                          class="mb-9 text-center mx-auto drop-option"
                          v-if="option.isSelected === false"
                          :class="[
                            { 'bkg-danger': option.type === 'Stuck' },
                            {
                              'bkg-success text-dark':
                                option.type === 'Completed',
                            },
                            { 'bkg-primary': option.type === 'Working' },
                            { 'bkg-gray': option.type === 'Created' },
                          ]"
                        >
                          <div>
                            {{ option.type }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Instructions
                  </p>
                  <textarea name="" id="" rows="5"></textarea>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Attachment
                  </p>
                  <div class="d-flex gap-3">
                    <div
                      class="d-flex align-items-center text-center btn-attachment"
                    >
                      <label
                        class="mx-auto text-center text-white btn-attachment-icon"
                      >
                        +
                        <input type="file" />
                      </label>
                    </div>

                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-34">
                  <div class="mb-6">
                    <RichMultiSelect
                      :optionsArray="dataArray"
                      :header="'Created by'"
                    />
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row g-5">
                    <div class="col-lg-6">
                      <label class="text-normal-bold-md mb-12 color-secondary">
                        Date Created
                      </label>
                      <input type="text" class="form-control" />
                    </div>
                    <div class="col-lg-6">
                      <label class="text-normal-bold-md mb-12 color-secondary">
                        Due Date
                      </label>
                      <input type="text" class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="mb-34">
                    <div class="mb-6">
                      <RichMultiSelect
                        :optionsArray="dataArray"
                        :header="'Assignee'"
                      />
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row g-5">
                    <div class="col-lg-6">
                      <label class="text-normal-bold-md mb-12 color-secondary">
                        Building
                      </label>
                      <input type="text" class="form-control" />
                    </div>
                    <div class="col-lg-6">
                      <label class="text-normal-bold-md mb-12 color-secondary">
                        unit
                      </label>
                      <input type="text" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row gx-5">
              <div class="col-lg-8">
                <div class="d-flex gap-3">
                  <div>
                    <img
                      src="/images/worker.png"
                      class="avator-sm"
                      alt="avatar"
                    />
                  </div>

                  <div class="position-relative w-100">
                    <textarea class="w-100" rows="5"></textarea>
                    <div class="position-absolute attachment-section">
                      <img src="/images/attachment.png" class="me-7" alt="" />
                      <img src="/images/emoji.png" class="me-7" alt="" />
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="col-lg-4 align-self-end justify-content-end text-md-start text-end mt-md-0 mt-2"
              >
                <button class="btn btn-dark px-4 pb-2">Comment</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RichMultiSelect from "../components/utils/rich-multi-select.vue";
export default {
  components: {
    RichMultiSelect,
  },
  data() {
    return {
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
    };
  },
};
</script>
