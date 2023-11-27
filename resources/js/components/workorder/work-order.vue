<template>
  <div class="work-order">
    <div>
      <div class="row gap-3 mb-28">
        <div class="col-lg-3 col-md-6 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Building</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="selectedBuilding"
              placeholder=""
              label="name"
              track-by="id"
              :options="buildings"
              @input="loadData(null,selectedBuilding)"
            ></multiselect>
          </div>
        </div>

        <div class="col-lg-3 col-md-6  multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Status</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="status"
              placeholder=""
              label="name"
              track-by="id"
              :options="statuses"
              @input="loadData(null,selectedBuilding)"
            ></multiselect>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Priority</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="priority"
              placeholder=""
              label="name"
              track-by="id"
              :options="priorities"
              @input="loadData(null, selectedBuilding)"
            ></multiselect>
          </div>
        </div>
      </div>
      <div
        class="row card"
        v-if="!this.selectedBuilding || this.selectBuilding == ''"
      >
        <div
          class="col-lg-12 card-body text-normal color-secondary-dark text-center red"
        >
          Please select a building
        </div>
      </div>
      <div
        class="row g-4"
        v-else-if="this.workOrders && this.workOrders.completed"
      >
        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Created</h3>
          <div
            class="card border-standered mb-8"
            v-for="workOrder in workOrders.created"
          >
            <WorkOrderModal :workOrder="workOrder" />
            <workOrderEmail :workOrder="workOrder" />
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon" />
              <div class="">
                <div :id="'dropdownMenuOffset'+workOrder.id" data-bs-toggle="dropdown" aria-expanded="false" class="">
                  <span class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                  :class="[
                      { 'bkg-danger': workOrder.priority === 'High' },
                      { 'bkg-gray': workOrder.priority === 'Medium' },
                      { 'bkg-success text-dark': workOrder.priority === 'Low' },
                    ]">
                    {{workOrder.priority}}
                  </span> 
                  <span class="select-cursor">
                    <img src="/images/icons/down-arrow.png" alt="icon">
                  </span>
                </div> 
                <div class="dropdown">
                  <div :aria-labelledby="'dropdownMenuOffset'+workOrder.id" class="dropdown-menu dropdown-menu-end">
                    <div class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor" @click="updateWorkOrderPriority(workOrder,'High')">
                      <div>High</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor" @click="updateWorkOrderPriority(workOrder,'Medium')">
                      <div>Medium</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor" @click="updateWorkOrderPriority(workOrder,'Low')">
                      <div>Low</div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading" :title="workOrder.subject">
                    {{ workOrder.subject }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{ workOrder.maintainer ? workOrder.maintainer.name : "-" }}
                    {{ workOrder.unit_no && workOrder.maintainer ? "("+workOrder.unit_no+")" :  '' }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <img
                      :src="
                        workOrder.maintainer &&
                        workOrder.maintainer.profile_picture
                          ? workOrder.maintainer.profile_picture
                          : '/images/default-user.jpg'
                      "
                      class="avatar-sm"
                    />
                    <span
                      v-if="
                        workOrder.maintainer && workOrder.maintainer.status == 1
                      "
                      class="verifiyIcon"
                    >
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
                    <!-- <span>
                      <i
                        class="fa fa-envelope select-cursor color-primary"
                        data-bs-toggle="modal"
                        title="Send email"
                        :data-bs-target="'#workOrderEmailModal' + workOrder.id"
                        style="vertical-align: middle"
                      ></i>
                    </span> -->
                  </div>
                </div>
              </div>
            </div>
            <hr class="mb-11" />

            <div class="mt-4 mb-8">
              <div
                id="dropdownMenuOffset"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="dropdown-item">Created</span>
                <span class="select-cursor">
                  <img src="/images/icons/down-arrow.png" alt="icon"
                /></span>
              </div>
              <div class="dropdown">
                <div
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="dropdownMenuOffset"
                >
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Inprogress')"
                  >
                    <div>In Progress</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Due')"
                  >
                    <div>Expired</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Resolved')"
                  >
                    <div>Completed</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray-light text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'close')"
                  >
                    <div>Archived</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div>
              <p class="mb-28 text-medium-lg">{{ workOrder.subject }}</p>
            </div> -->

            <div class="text-center">
              <p
                class="color-primary text-medium-md mb-0 select-cursor"
                data-bs-toggle="modal"
                :data-bs-target="'#workOrderModal' + workOrder.id"
              >
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">In Progress</h3>
          <div
            class="card border-standered mb-8"
            v-for="workOrder in workOrders.in_progress"
          >
            <WorkOrderModal :workOrder="workOrder" />
            <workOrderEmail :workOrder="workOrder" />
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon" /><div class="">
                <div :id="'dropdownMenuOffset'+workOrder.id" data-bs-toggle="dropdown" aria-expanded="false" class="">
                  <span class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                  :class="[
                      { 'bkg-danger': workOrder.priority === 'High' },
                      { 'bkg-gray': workOrder.priority === 'Medium' },
                      { 'bkg-success text-dark': workOrder.priority === 'Low' },
                    ]">
                    {{workOrder.priority}}
                  </span> 
                  <span class="select-cursor">
                    <img src="/images/icons/down-arrow.png" alt="icon">
                  </span>
                </div> 
                <div class="dropdown">
                  <div :aria-labelledby="'dropdownMenuOffset'+workOrder.id" class="dropdown-menu dropdown-menu-end">
                    <div class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor" @click="updateWorkOrderPriority(workOrder,'High')">
                      <div>High</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor" @click="updateWorkOrderPriority(workOrder,'Medium')">
                      <div>Medium</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor" @click="updateWorkOrderPriority(workOrder,'Low')">
                      <div>Low</div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading" :title="workOrder.subject">
                    {{ workOrder.subject }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{ workOrder.maintainer ? workOrder.maintainer.name : "-" }}
                    {{ workOrder.unit_no && workOrder.maintainer ? "("+workOrder.unit_no+")" :  '' }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <img
                      :src="
                        workOrder.maintainer &&
                        workOrder.maintainer.profile_picture
                          ? workOrder.maintainer.profile_picture
                          : '/images/default-user.jpg'
                      "
                      class="avatar-sm"
                    />
                    <span
                      v-if="
                        workOrder.maintainer && workOrder.maintainer.status == 1
                      "
                      class="verifiyIcon"
                    >
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
                    <!-- <span>
                      <i
                        class="fa fa-envelope select-cursor color-primary"
                        data-bs-toggle="modal"
                        title="Send email"
                        :data-bs-target="'#workOrderEmailModal' + workOrder.id"
                        style="vertical-align: middle"
                      ></i>
                    </span> -->
                  </div>
                </div>
              </div>
            </div>
            <hr class="mb-11" />

            <div class="mt-4 mb-8">
              <div
                id="dropdownMenuOffset"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="dropdown-item bkg-primary">In Progress</span>
                <span class="select-cursor">
                  <img src="/images/icons/down-arrow.png" alt="icon"
                /></span>
              </div>
              <div class="dropdown">
                <div
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Reopen')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Due')"
                  >
                    <div>Expired</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Resolved')"
                  >
                    <div>Completed</div>
                  </div>
                  <!-- <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray-light text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'close')"
                  >
                    <div>Archived</div>
                  </div> -->
                </div>
              </div>
            </div>

            <!-- <div>
              <p class="mb-28 text-medium-lg">{{ workOrder.subject }}</p>
            </div> -->

            <div class="text-center">
              <p
                class="color-primary text-medium-md mb-0 select-cursor"
                data-bs-toggle="modal"
                :data-bs-target="'#workOrderModal' + workOrder.id"
              >
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Expired</h3>
          <div
            class="card border-standered mb-8"
            v-for="workOrder in workOrders.expired"
          >
            <WorkOrderModal :workOrder="workOrder" />
            <workOrderEmail :workOrder="workOrder" />
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon" /><div class="">
                <div :id="'dropdownMenuOffset'+workOrder.id" data-bs-toggle="dropdown" aria-expanded="false" class="">
                  <span class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                  :class="[
                      { 'bkg-danger': workOrder.priority === 'High' },
                      { 'bkg-gray': workOrder.priority === 'Medium' },
                      { 'bkg-success text-dark': workOrder.priority === 'Low' },
                    ]">
                    {{workOrder.priority}}
                  </span> 
                  <span class="select-cursor">
                    <img src="/images/icons/down-arrow.png" alt="icon">
                  </span>
                </div> 
                <div class="dropdown">
                  <div :aria-labelledby="'dropdownMenuOffset'+workOrder.id" class="dropdown-menu dropdown-menu-end">
                    <div class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor" @click="updateWorkOrderPriority(workOrder,'High')">
                      <div>High</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor" @click="updateWorkOrderPriority(workOrder,'Medium')">
                      <div>Medium</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor" @click="updateWorkOrderPriority(workOrder,'Low')">
                      <div>Low</div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading" :title="workOrder.subject">
                    {{ workOrder.subject }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{ workOrder.maintainer ? workOrder.maintainer.name : "-" }}
                    {{ workOrder.unit_no && workOrder.maintainer ? "("+workOrder.unit_no+")" :  '' }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <img :src="workOrder.maintainer &&
                        workOrder.maintainer.profile_picture
                          ? workOrder.maintainer.profile_picture
                          : '/images/default-user.jpg'" class="avatar-sm" />
                    <span class="verifiyIcon" v-if="
                        workOrder.maintainer && workOrder.maintainer.status == 1
                      ">
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
                    <!-- <span>
                      <i
                        class="fa fa-envelope select-cursor color-primary"
                        data-bs-toggle="modal"
                        title="Send email"
                        :data-bs-target="'#workOrderEmailModal' + workOrder.id"
                        style="vertical-align: middle"
                      ></i>
                    </span> -->
                  </div>
                </div>
              </div>
            </div>
            <hr class="mb-11" />

            <div class="mt-4 mb-8">
              <div
                id="dropdownMenuOffset"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="dropdown-item bkg-danger">Expired</span>
                <span class="select-cursor">
                  <img src="/images/icons/down-arrow.png" alt="icon"
                /></span>
              </div>
              <div class="dropdown">
                <div
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Reopen')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Inprogress')"
                  >
                    <div>In Progress</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Resolved')"
                  >
                    <div>Completed</div>
                  </div>
                </div>
              </div>
            </div>
<!-- 
            <div>
              <p class="mb-28 text-medium-lg">{{ workOrder.subject }}</p>
            </div> -->

            <div class="text-center">
              <p
                class="color-primary text-medium-md mb-0 select-cursor"
                data-bs-toggle="modal"
                :data-bs-target="'#workOrderModal' + workOrder.id"
              >
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
          <h3 class="h3 mb-44 text-center text-md-start">Done</h3>
          <div
            class="card border-standered mb-8"
            v-for="workOrder in workOrders.completed"
          >
            <WorkOrderModal :workOrder="workOrder" />
            <workOrderEmail :workOrder="workOrder" />
            <div class="mb-11 d-flex justify-content-between">
              <img src="/images/icons/vehicle.png" alt="icon" /><div class="">
                <div :id="'dropdownMenuOffset'+workOrder.id" data-bs-toggle="dropdown" aria-expanded="false" class="">
                  <span class="dropdown-item" style="width: 75px !important;margin-right: 2px !important;"
                  :class="[
                      { 'bkg-danger': workOrder.priority === 'High' },
                      { 'bkg-gray': workOrder.priority === 'Medium' },
                      { 'bkg-success text-dark': workOrder.priority === 'Low' },
                    ]">
                    {{workOrder.priority}}
                  </span> 
                  <span class="select-cursor">
                    <img src="/images/icons/down-arrow.png" alt="icon">
                  </span>
                </div> 
                <div class="dropdown">
                  <div :aria-labelledby="'dropdownMenuOffset'+workOrder.id" class="dropdown-menu dropdown-menu-end">
                    <div class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor" @click="updateWorkOrderPriority(workOrder,'High')">
                      <div>High</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor" @click="updateWorkOrderPriority(workOrder,'Medium')">
                      <div>Medium</div>
                    </div> 
                    <div class="mb-9 text-center mx-auto drop-option bkg-success text-dark select-cursor" @click="updateWorkOrderPriority(workOrder,'Low')">
                      <div>Low</div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="align-self-center">
                <div>
                  <h3 class="h3 mb-0 card-main-heading" :title="workOrder.subject">
                    {{ workOrder.subject }}
                  </h3>
                  <p class="text-normal-sm mb-12 color-primary">
                    {{ workOrder.maintainer ? workOrder.maintainer.name : "-" }}
                    {{ workOrder.unit_no && workOrder.maintainer ? "("+workOrder.unit_no+")" :  '' }}
                  </p>
                </div>
              </div>
              <div class="align-self-center">
                <div class="d-inline-block mx-auto mx-lg-0">
                  <div class="position-relative text-center">
                    <img
                      :src="
                        workOrder.maintainer &&
                        workOrder.maintainer.profile_picture
                          ? workOrder.maintainer.profile_picture
                          : '/images/default-user.jpg'
                      "
                      class="avatar-sm"
                    />
                    <span
                      v-if="
                        workOrder.maintainer && workOrder.maintainer.status == 1
                      "
                      class="verifiyIcon"
                    >
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
                    <!-- <span>
                      <i
                        class="fa fa-envelope select-cursor color-primary"
                        data-bs-toggle="modal"
                        title="Send email"
                        :data-bs-target="'#workOrderEmailModal' + workOrder.id"
                        style="vertical-align: middle"
                      ></i>
                    </span> -->
                  </div>
                </div>
              </div>
            </div>
            <hr class="mb-11" />

            <div class="mt-4 mb-8">
              <div
                id="dropdownMenuOffset"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span>
                  <span class="dropdown-item bkg-success text-dark">Done</span>
                </span>
                <span class="select-cursor">
                  <img src="/images/icons/down-arrow.png" alt="icon"
                /></span>
              </div>
              <div class="dropdown">
                <div
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="dropdownMenuOffset"
                >
                  <!-- <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Reopen')"
                  >
                    <div>Created</div>
                  </div> -->
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-danger select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Due')"
                  >
                    <div>Expired</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-primary select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'Inprogress')"
                  >
                    <div>In Progress</div>
                  </div>
                  <div
                    class="mb-9 text-center mx-auto drop-option bkg-gray-light text-dark select-cursor"
                    @click="changeWorkOrderStatus(workOrder.id, 'close')"
                  >
                    <div>Archived</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div>
              <p class="mb-28 text-medium-lg">{{ workOrder.subject }}</p>
            </div> -->

            <div class="text-center">
              <p
                class="color-primary text-medium-md mb-0 select-cursor"
                data-bs-toggle="modal"
                :data-bs-target="'#workOrderModal' + workOrder.id"
              >
                Show More
              </p>
            </div>
          </div>
        </div>

        <div class="col-lg-12 text-center">
          <!-- <p
            class="color-primary text-medium-md mb-0 select-cursor"
            v-if="!this.showAll"
            @click="LoadAllWorkOrders"
          >
            Show all
          </p>
          <p
            class="color-primary text-medium-md mb-0 select-cursor"
            v-else
            @click="loadData(selectedBuilding)"
          >
            Show less
          </p> -->
          <pagination :pagination="pagination"></pagination>
        </div>
      </div>
      <div class="row card" v-else>
        <div
          class="col-lg-12 card-body text-normal color-secondary-dark text-center"
        >
          No Data found
        </div>
      </div>
    </div>

    <!-- <div class="mt-5"> -->
    <!-- <sendWorkOrderModal /> -->
    <!-- </div> -->
  </div>
</template>

<script>
import WorkOrderModal from "./work-order-modal.vue";
import workOrderEmail from "./work-order-email.vue";
export default {
  components: {
    WorkOrderModal,
    workOrderEmail,
  },
  created() {
    // this.getBuildings();
  },
  mounted() {
    if (this.$gate.building_id > 0) {
      console.log(this.$gate.building_id, "asdsad");
      // this.selectedBuilding = this.$gate.building_id;
    }
  },
  data() {
    return {
      isShowPersonList: false,
      isShowDropdown: false,
      selectedPerson: "/images/worker.png",
      statuses: [
        { id: "Open", name: "Open" },
        { id: "Inprogress", name: "Inprogress" },
        { id: "Resolved", name: "Resolved" },
        { id: "Close", name: "Close" },
        { id: "Reopen", name: "Reopen" },
        { id: "Due", name: "Due" },
      ],
      priorities: [
        { id: "Low", name: "Low" },
        { id: "Medium", name: "Medium" },
        { id: "High", name: "High" },
      ],
      priority: "",
      status: "",
      
      workOrders: [],
      buildings: [],
      selectedBuilding: "",
      showAll: false,

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
    updateWorkOrderPriority(order,priority){
      console.log(order,'ORDER');
      console.log(priority,'priority');
      order.priority = priority;
      this.showLoader();
      this.$http
        .get("/updateWorkOrderPriority/"+order.id+"/"+priority)
        .then((response) => {
          Toast.fire({
            icon: "success",
            title: "Work Order priority changed to " + priority,
          });
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
    },
    toggleDropdown() {
      this.isShowDropdown = !this.isShowDropdown;
    },
    togglePersonList() {
      this.isShowPersonList = !this.isShowPersonList;
    },
    selectBuilding(index) {
      this.selectedBuilding = this.allBuildings[index];
    },

    selectPerson(index) {
      this.selectedPerson = this.personList[index];
    },
    loadData(pageNumber = null,building = 0, toastMessage = false) {
      this.showLoader();
      this.showAll = false;
      console.log(building);
      console.log(this.selectedBuilding,'asdsaasd')
      let building_id = this.$gate.building_id;
      if (building != 0) {
        this.selectBuilding = building;
        building_id = building.id;
      }
      
      if(building == 0 && this.selectedBuilding){
        building_id = this.selectedBuilding.id;
      }

      let status = this.status ? this.status.id : "";
      let priority = this.priority ? this.priority.id : "";

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;


      // let params = {
      this.params.building_id = this.selectedBuilding ? this.selectedBuilding.id : 0;
      this.params.status = this.status ? this.status.id : '';
      this.params.priority = this.priority ? this.priority.id : '';

      let params = this.params;
      this.$http
        .get(
          "/workOrders",{params}
        )
        .then((response) => {
          this.pagination = response.data;
          this.workOrders = response.data.data;
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
          this.removeLoader();
        });
    },
    LoadAllWorkOrders() {
      this.showLoader();
      let building_id = this.$gate.building_id;

      let status = this.status ? this.status.id : "";
      let priority = this.priority ? this.priority.id : "";

      if (this.selectedBuilding != "") building_id = this.selectedBuilding.id;

      this.$http
        .get(
          "/workOrders?building_id=" +
            building_id +
            "&all=true&priority=" +
            priority +
            "&status=" +
            status
        )
        .then((response) => {
          this.showAll = true;
          this.workOrders = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getBuildings() {
      if(this.selectedBuilding) return;
      this.$http
        .get("/buildings")
        .then((response) => {
          this.buildings = response.data.data;
          if (this.$gate.building_id > 0) {
            this.selectedBuilding = this.buildings.find(
              (obj) => obj.id === this.$gate.building_id
            );
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    changeWorkOrderStatus(id, status, modalFlag = false) {
      console.log(id, status, modalFlag);
      this.showLoader();

      this.$http
        .get("/changeWorkOrderStatus?id=" + id + "&state=" + status)
        .then((response) => {
          this.loadData(null, this.selectedBuilding, "Work Order status changed to " + status);
          // if (modalFlag) $("#taskModal" + id).modal("hide");
          // this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
