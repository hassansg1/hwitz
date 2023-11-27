<template>
  <div class="task-all">
    <div>
      <div class="row gy-3 mb-28">
        <div class="col-lg-3">
          <input
              type="text"
              class="form-control"
              placeholder="Search by name"
              v-model="searchTask"
              @keyup.enter="loadData()"
          />
        </div>
        <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select building</label
            >
            <multiselect 
                :selectLabel="''"
                :deselectLabel= "''"
                @input="loadUnitsAndTasks"
                v-model="building"
                tag-placeholder="Add this as new tag"
                placeholder=""
                label="name"
                track-by="id"
                :options="buildings"
                :multiple="false"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Select unit</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="unit"
                placeholder=""
                label="unit_no"
                track-by="id"
                :options="units"
                :multiple="false"
                @input="loadUsersAndTasks"
            ></multiselect>
          </div>
          <!-- <select
            class="form-select"
            v-model="unit"
            @change="loadUsersAndTasks"
          >
            <option value="" selected>Select Unit</option>
            <option :value="unit.id" v-for="unit in units">
              {{ unit.unit_no }}
            </option>
          </select> -->
        </div>
        <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Select user</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="user"
                placeholder=""
                label="name"
                track-by="id"
                :options="users"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
          <!-- <select
            class="form-select"
            aria-label="Default select example"
            v-model="user"
            @change="loadData()"
          >
            <option value="" selected>Select User</option>
            <option :value="user.id" v-for="user in c">
              {{ user.firstname + " " + user.lastname }}
            </option>
          </select> -->
        </div>
        <!-- <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select task type</label
            >
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="task_name"
                placeholder=""
                label="name"
                track-by="id"
                :options="taskTypes"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
        </div> -->
        <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select priority</label
            >
            <multiselect 
                :selectLabel="''"
                :deselectLabel= "''"
                v-model="task_priority"
                placeholder=""
                label="name"
                track-by="id"
                :options="priorities"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
          <!-- <select
            class="form-select"
            aria-label="Default select example"
            v-model="task_priority"
            @change="loadData()"
          >
            <option value="" selected>Select Task Priority</option>
            <option value="3">High Priority</option>
            <option value="2">Medium Priority</option>
            <option value="1">Low Priority</option>
          </select> -->
        </div>
        <div class="col-lg-3 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select task state</label
            >
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                v-model="task_state"
                placeholder=""
                label="name"
                track-by="id"
                :options="taskStates"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
          <!-- <select
            class="form-select"
            aria-label="Default select example"
            v-model="task_state"
            @change="loadData()"
          >
            <option value="" selected>Select Task State</option>
            <option value="O">Open</option>
            <option value="C">Complete</option>
          </select> -->
        </div>
        <div class="col-lg-3 multiselect-border">
          <div>
            <!-- <DatePicker v-model="range" is-range>
              <template v-slot="{ inputValue, inputEvents }">
                <div class="flex justify-center items-center">
                  <input
                    :value="`${inputValue.start} - ${inputValue.end}`"
                    v-on="inputEvents.start"
                    class="form-control"
                  />
                </div>
              </template>
            </DatePicker> -->

            <input type="date" id="task_date_filter" @input="loadData()"
                   class="form-control datefilter task_date_filter"
                   name="date_filter" placeholder="Select Date" autocomplete="off">
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Created</h3>
          <div
              class="card border-standered mb-8"
              v-for="createdTask in tasks.created"
              :key="createdTask.id"
          >
            <TaskModal :id="createdTask.id" :task="createdTask"/>
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon"/>
              <div class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                :class="[
                    { 'bkg-danger': createdTask.priorities === 3 },
                    { 'bkg-gray': createdTask.priorities === 2 },
                    { 'bkg-success text-dark': createdTask.priorities === 1 },
                  ]">
                  <span v-if="createdTask.priorities === 3">High</span>
                  <span v-if="createdTask.priorities === 2">Medium</span>
                  <span v-if="createdTask.priorities === 1">Low</span>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading">
                    {{ createdTask.name }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{
                      createdTask.target_user
                          ? createdTask.target_user.firstname +
                          " " +
                          createdTask.target_user.lastname
                          : ""
                    }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <div class="position-relative avatar-sm overflow-visible">
                    <img
                        :src="
                        createdTask.target_user &&
                        createdTask.target_user.profile_picture
                          ? createdTask.target_user.profile_picture
                          : '/images/default-user.jpg'
                      "
                        class="avatar-img"
                    />
                    <span class="verifiyIcon">
                      <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="10"
                          height="10"
                          viewBox="0 0 38 38"
                          fill="none"
                      >
                        <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
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
            <hr class="mb-11"/>

            <div class="mb-8 mt-3">
              <div
                  id="dropdownMenuOffset"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              >
                <span>
                  <span class="dropdown-item">Created</span>
                </span>
                <span>
                  <img
                      src="/images/icons/down-arrow.png"
                      alt="icon"
                      class="select-cursor"
                  /></span>
              </div>
              <div class="dropdown">
                <div
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                    v-for="option of workOptions"
                    class="mb-9 text-center mx-auto drop-option"
                    style="width: 120px"
                    :class="[
                      { 'bkg-danger': option.type === 'Stuck' },
                      { 'bkg-success text-dark': option.type === 'Completed' },
                      { 'bkg-primary': option.type === 'Working' },
                    ]"
                  >
                    <div v-if="option.isSelected === false">
                      {{ option.type }}
                    </div> -->
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor"
                      @click="changeTaskState(createdTask.id, 'stuck')"
                  >
                    <div>Stuck</div>
                  </div>
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                      @click="changeTaskState(createdTask.id, 'completed')"
                  >
                    <div>Completed</div>
                  </div>
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                      @click="changeTaskState(createdTask.id, 'working')"
                  >
                    <div>Working</div>
                  </div>
                  <!-- </div> -->
                </div>
              </div>
            </div>

            <div>
              <p class="mb-28 text-medium-lg">
                <template v-if="createdTask.subject_type === 'Document'">
                  <template v-if="createdTask.packet_name">
                    {{ createdTask.packet_name }} <br/>
                  </template>
                  <template v-if="createdTask.templateString">
                    {{ createdTask.templateString }} <br/>
                  </template>
                </template>
                <template v-if="createdTask.name === 'Approve Profile Image'">
                  <template v-if="createdTask.userstring">
                    {{ createdTask.userstring }}
                  </template>
                </template>
              </p>
            </div>

            <div
                class="text-center"
                data-bs-toggle="modal"
                :data-bs-target="'#taskModal' + createdTask.id"
            >
              <p class="color-primary text-medium-md mb-0 select-cursor">
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Working</h3>
          <div
              class="card border-standered mb-8"
              v-for="workingTasks in tasks.working"
              :key="workingTasks.id"
          >
            <TaskModal :id="workingTasks.id" :task="workingTasks"/>
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon"/>
              <div class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                :class="[
                    { 'bkg-danger': workingTasks.priorities === 3 },
                    { 'bkg-gray': workingTasks.priorities === 2 },
                    { 'bkg-success text-dark': workingTasks.priorities === 1 },
                  ]">
                  <span v-if="workingTasks.priorities === 3">High</span>
                  <span v-if="workingTasks.priorities === 2">Medium</span>
                  <span v-if="workingTasks.priorities === 1">Low</span>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading">
                    {{ workingTasks.name }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{
                      workingTasks.target_user
                          ? workingTasks.target_user.firstname +
                          " " +
                          workingTasks.target_user.lastname
                          : ""
                    }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <div class="position-relative avatar-sm overflow-visible">
                    <img
                        :src="
                        workingTasks.target_user &&
                        workingTasks.target_user.profile_picture
                          ? workingTasks.target_user.profile_picture
                          : '/images/default-user.jpg'
                      "
                        class="avatar-img"
                    />
                    <span class="verifiyIcon">
                      <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="10"
                          height="10"
                          viewBox="0 0 38 38"
                          fill="none"
                      >
                        <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
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
            <hr class="mb-11"/>

            <div class="mb-8 mt-3">
              <div
                  id="dropdownMenuOffset"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              >
                <span class="dropdown-item bkg-primary">Working</span>
                <span>
                  <img
                      src="/images/icons/down-arrow.png"
                      alt="icon"
                      class="select-cursor"
                  /></span>
              </div>
              <div class="dropdown">
                <div
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                      class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                      @click="changeTaskState(workingTasks.id, 'created')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                      @click="changeTaskState(workingTasks.id, 'completed')"
                  >
                    <div>Completed</div>
                  </div>
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor"
                      @click="changeTaskState(workingTasks.id, 'stuck')"
                  >
                    <div>Stuck</div>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="mb-28 text-medium-lg">
                <template v-if="workingTasks.subject_type === 'Document'">
                  <template v-if="workingTasks.packet_name">
                    {{ workingTasks.packet_name }} <br/>
                  </template>
                  <template v-if="workingTasks.templateString">
                    {{ workingTasks.templateString }} <br/>
                  </template>
                </template>
                <template v-if="workingTasks.name === 'Approve Profile Image'">
                  <template v-if="workingTasks.userstring">
                    {{ workingTasks.userstring }}
                  </template>
                </template>
              </p>
            </div>

            <div
                class="text-center"
                data-bs-toggle="modal"
                :data-bs-target="'#taskModal' + workingTasks.id"
            >
              <p class="color-primary text-medium-md mb-0 select-cursor">
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Stuck</h3>
          <div
              class="card border-standered mb-8"
              v-for="stuckTasks in tasks.stuck"
              :key="stuckTasks.id"
          >
            <TaskModal :id="stuckTasks.id" :task="stuckTasks"/>
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon"/>
              <div class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                :class="[
                    { 'bkg-danger': stuckTasks.priorities === 3 },
                    { 'bkg-gray': stuckTasks.priorities === 2 },
                    { 'bkg-success text-dark': stuckTasks.priorities === 1 },
                  ]">
                  <span v-if="stuckTasks.priorities === 3">High</span>
                  <span v-if="stuckTasks.priorities === 2">Medium</span>
                  <span v-if="stuckTasks.priorities === 1">Low</span>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading">
                    {{ stuckTasks.name }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{
                      stuckTasks.target_user
                          ? stuckTasks.target_user.firstname +
                          " " +
                          stuckTasks.target_user.lastname
                          : ""
                    }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <div class="position-relative avatar-sm overflow-visible">
                      <img
                          :src="
                          stuckTasks.target_user &&
                          stuckTasks.target_user.profile_picture
                            ? stuckTasks.target_user.profile_picture
                            : '/images/default-user.jpg'
                        "
                          class="avatar-img"
                      />
                      <span class="verifiyIcon">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="10"
                            height="10"
                            viewBox="0 0 38 38"
                            fill="none"
                        >
                          <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
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
            <hr class="mb-11"/>

            <div class="mb-8 mt-3">
              <div
                  id="dropdownMenuOffset"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              >
                <span class="dropdown-item bkg-danger">Stuck</span>
                <span>
                  <img
                      src="/images/icons/down-arrow.png"
                      alt="icon"
                      class="select-cursor"
                  /></span>
              </div>
              <div class="dropdown">
                <div
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                      class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                      @click="changeTaskState(stuckTasks.id, 'created')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                      @click="changeTaskState(stuckTasks.id, 'completed')"
                  >
                    <div>Completed</div>
                  </div>
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                      @click="changeTaskState(stuckTasks.id, 'working')"
                  >
                    <div>Working</div>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="mb-28 text-medium-lg">
                <template v-if="stuckTasks.subject_type === 'Document'">
                  <template v-if="stuckTasks.packet_name">
                    {{ stuckTasks.packet_name }} <br/>
                  </template>
                  <template v-if="stuckTasks.templateString">
                    {{ stuckTasks.templateString }} <br/>
                  </template>
                </template>
                <template v-if="stuckTasks.name === 'Approve Profile Image'">
                  <template v-if="stuckTasks.userstring">
                    {{ stuckTasks.userstring }}
                  </template>
                </template>
              </p>
            </div>

            <div
                class="text-center"
                data-bs-toggle="modal"
                :data-bs-target="'#taskModal' + stuckTasks.id"
            >
              <p class="color-primary text-medium-md mb-0 select-cursor">
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Completed</h3>
          <div
              class="card border-standered mb-8"
              v-for="completedTasks in tasks.completed"
              :key="completedTasks.id"
          >
            <TaskModal :id="completedTasks.id" :task="completedTasks"/>
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon"/>
              <div class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                :class="[
                    { 'bkg-danger': completedTasks.priorities === 3 },
                    { 'bkg-gray': completedTasks.priorities === 2 },
                    { 'bkg-success text-dark': completedTasks.priorities === 1 },
                  ]">
                  <span v-if="completedTasks.priorities === 3">High</span>
                  <span v-if="completedTasks.priorities === 2">Medium</span>
                  <span v-if="completedTasks.priorities === 1">Low</span>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3
                      class="h3 mb-0 card-main-heading"
                      :title="completedTasks.name"
                  >
                    {{ completedTasks.name }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{
                      completedTasks.target_user
                          ? completedTasks.target_user.firstname +
                          " " +
                          completedTasks.target_user.lastname
                          : ""
                    }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <div class="position-relative avatar-sm overflow-visible">
                        <img
                          :src="
                          completedTasks.target_user &&
                          completedTasks.target_user.profile_picture
                            ? completedTasks.target_user.profile_picture
                            : '/images/default-user.jpg'
                        "
                          class="avatar-img"
                      />

                      <div class="verifiyIcon">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="10"
                            height="10"
                            viewBox="0 0 38 38"
                            fill="none"
                            class=""
                        >
                          <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
                          <path
                              fill-rule="evenodd"
                              clip-rule="evenodd"
                              d="M24.9228 14.761C25.2285 15.0455 25.2367 15.5148 24.9413 15.8091L17.5005 23.2214C17.3536 23.3677 17.1507 23.4495 16.9393 23.4474C16.728 23.4454 16.5268 23.3598 16.383 23.2106L13.0475 19.7515C12.7582 19.4516 12.7762 18.9826 13.0878 18.704C13.3993 18.4255 13.8863 18.4428 14.1756 18.7428L16.9583 21.6286L23.8344 14.7788C24.1299 14.4845 24.6172 14.4765 24.9228 14.761Z"
                              fill="black"
                          />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="mb-11"/>

            <div class="mb-8 mt-3">
              <div
                  id="dropdownMenuOffset"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              >
                <span class="dropdown-item bkg-success text-dark"
                >Completed</span
                >
                <span>
                  <img
                      src="/images/icons/down-arrow.png"
                      alt="icon"
                      class="select-cursor"
                  /></span>
              </div>
              <div class="dropdown">
                <div
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                      class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                      @click="changeTaskState(completedTasks.id, 'created')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-success bkg-danger select-cursor"
                      @click="changeTaskState(completedTasks.id, 'stuck')"
                  >
                    <div>Stuck</div>
                  </div>
                  <div
                      class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                      @click="changeTaskState(completedTasks.id, 'working')"
                  >
                    <div>Working</div>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="mb-28 text-medium-lg">
                <template v-if="completedTasks.subject_type === 'Document'">
                  <template v-if="completedTasks.packet_name">
                    {{ completedTasks.packet_name }} <br/>
                  </template>
                  <template v-if="completedTasks.templateString">
                    {{ completedTasks.templateString }} <br/>
                  </template>
                </template>
                <template
                    v-if="completedTasks.name === 'Approve Profile Image'"
                >
                  <template v-if="completedTasks.userstring">
                    {{ completedTasks.userstring }}
                  </template>
                </template>
              </p>
            </div>

            <div
                class="text-center"
                data-bs-toggle="modal"
                :data-bs-target="'#taskModal' + completedTasks.id"
            >
              <p class="color-primary text-medium-md mb-0 select-cursor">
                Show More
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-12 text-center mt-64">
          <!-- <a class="align-self-center a-link mb-16 mb-lg-0" href="javscript:void(0)" @click="loadData(true)">View all</a> -->
          <pagination :pagination="pagination"></pagination>
        </div>
      </div>
    </div>
    <!-- <TaskModal /> -->
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import TaskModal from "./task-modal.vue";
import {ref} from "vue";

export default {
  components: {
    TaskModal,
    DatePicker,
  },
  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
    const dragValue = ref(null);
    return {
      range,
      dragValue,
      NewRange: "",
      dateRange: false,
      isShowCreatedDropdown: false,
      isShowWorkingDropdown: false,
      isShowCompletedDropdown: false,
      isShowStuckDropdown: false,

      sCreatedArray: [],
      sWorkingArray: [],
      sStuckArray: [],
      sCompletedArray: [],

      tasks: [],
      searchTask: "",
      buildings: [],
      building: "",
      units: [],
      unit: "",
      users: [],
      user: "",
      task_name: "",
      task_priority: "",
      task_state: "",

      taskTypes: [
        {
          id: "Accept 5/10/30 Days Notice Document",
          name: "Accept 5/10/30 Days Notice Document",
        },
        {id: "Accept Document", name: "Accept Document"},
        {
          id: "Accept Landlord Packtet Document",
          name: "Accept Landlord Packtet Document",
        },
        {
          id: "Accept Notices to Residents Document",
          name: "Accept Notices to Residents Document",
        },
        {id: "AddendumPacket", name: "AddendumPacket"},
        {
          id: "Additional Rent Service Subscription",
          name: "Additional Rent Service Subscription",
        },
        {
          id: "Amenities Package (Additional Rent)",
          name: "Amenities Package (Additional Rent)",
        },
        {id: "Approve Profile Image", name: "Approve Profile Image"},
        {id: "Sign Addendum", name: "Sign Addendum"},
        {id: "Sign Lease", name: "Sign Lease"},
      ],
      taskStates: [
        {id: "O", name: "Open"},
        {id: "C", name: "Complete"},
      ],
      priorities: [
        {id: "3", name: "High Priority"},
        {id: "2", name: "Medium Priority"},
        {id: "1", name: "Low Priority"},
      ],

      // pagination start
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      search: '',
      params: {},

      //pagination end
    };
  },
  methods: {
    loadData(pageNumber = null, toastMessage = false) {
      this.showLoader();

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      // this.params.building_id = this.building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;
      // this.params.search = this.search;


      // let params = {
      this.params.search_item = this.searchTask;
      this.params.building = this.building ? this.building.id : '';
      this.params.unit = this.unit ? this.unit.id : '';
      this.params.user = this.user ? this.user.id : '';
      this.params.name = this.task_name ? this.task_name.id : '';
      this.params.state = this.task_state ? this.task_state.id : '';
      this.params.priority = this.task_priority ? this.task_priority.id : '';
      // };
      // if(loadAll) params.showAll = 1;


      let params = this.params;
      this.$http
          .get("/tasks", {params})
          .then((response) => {
            this.pagination = response.data;
            response = response.data.data;
            console.log(response, "RESPONSE---")
            this.tasks = response;
            if(toastMessage){
              Toast.fire({
                icon: "success",
                title: toastMessage,
              });
            }
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadUnitsAndTasks() {
      this.loadData();
      this.$http
          .get("/building/" + this.building.id + "/units")
          .then((response) => {
            response = response.data.units;
            this.units = response;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadUsersAndTasks() {
      this.loadData();
      this.$http
          .get("/getUnitUsers/" + this.unit.id)
          .then((response) => {
            response = response.data.users;
            this.users = response;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    fetchBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            response = response.data.data;
            this.buildings = response;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    changeTaskState(id, status, modalFlag = false) {
      this.showLoader();

      this.$http
          .get("/change-task-state?id=" + id + "&state=" + status)
          .then((response) => {
            let toastMessage = "Task status changed to " + status;
            this.loadData(null, toastMessage);
            if (modalFlag) $("#taskModal" + id).modal("hide");
            // this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },

    toggleCreatedDropdown() {
      this.isShowCreatedDropdown = !this.isShowCreatedDropdown;
    },
    toggleWorkingDropdown() {
      this.isShowWorkingDropdown = !this.isShowWorkingDropdown;
    },
    toggleStuckDropdown() {
      this.isShowStuckDropdown = !this.isShowStuckDropdown;
    },
    toggleCompletedDropdown() {
      this.isShowCompletedDropdown = !this.isShowCompletedDropdown;
    },

    selectCreatedData(option) {
      if (!this.sCreatedArray.includes(option)) {
        this.sCreatedArray.push(option);
      }
    },
    selectWorkingData(option) {
      if (!this.sWorkingArray.includes(option)) {
        this.sWorkingArray.push(option);
      }
    },
    selectStuckData(option) {
      if (!this.sStuckArray.includes(option)) {
        this.sStuckArray.push(option);
      }
    },
    selectCompletedData(option) {
      if (!this.sCompletedArray.includes(option)) {
        this.sCompletedArray.push(option);
      }
    },

    toggleDateRange() {
      this.dateRange = !this.dateRange;
    },
    closeDateRange() {
      this.dateRange = false;
    },
  },
  created() {
    this.loadData();
  },
  mounted() {
  },
  computed: {
    selectDragAttribute() {
      const dateStart = this.range.start.toLocaleDateString();
      const dateEnd = this.range.end.toLocaleDateString();
      const datee = dateStart + " - " + dateEnd;
      console.log("what is range", datee);
      this.NewRange = datee;
      return {
        popover: {
          visibility: "hover",
          isInteractive: false,
        },
      };
    },
  },
};
</script>
