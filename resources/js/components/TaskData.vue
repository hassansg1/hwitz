<template>
  <main class="row">
    <section class="col-lg-6 col-12">
      <div class="card bg-white border-0 shadow py-4 px-4">
        <div class="col-12">
          <h4 class="notifications">Notifications</h4>
          <div class="d-block align-items-end">
            <div
              class="nav flex-row nav-pills me-3 nav nav-underline position-relative"
              style="margin-bottom: 30px"
              id="v-pills-tab"
              role="tablist"
              aria-orientation="vertical"
            >
              <div class="d-flex align-items-center">
                <button
                  class="nav-link active nav__btn active-underline d-flex align-items-center"
                  id="v-pills-home-tab"
                  data-bs-toggle="pill"
                  data-bs-target="#v-pills-home"
                  type="button"
                  role="tab"
                  aria-controls="v-pills-home"
                  aria-selected="true"
                >
                  All
                </button>
                <div
                  class="nav-item-label"
                  style="
                    padding: 3px 12px;
                    margin-left: -16px;
                    margin-right: 50px;
                    font-weight: bold;
                  "
                >
                  23
                </div>
              </div>
              <button
                class="nav-link nav__btn"
                id="v-pills-profile-tab"
                data-bs-toggle="pill"
                data-bs-target="#v-pills-profile"
                type="button"
                role="tab"
                aria-controls="v-pills-profile"
                aria-selected="false"
              >
                In Progress
              </button>
              <button
                class="nav-link nav__btn"
                id="v-pills-profile-tab"
                data-bs-toggle="pill"
                data-bs-target="#v-pills-profile"
                type="button"
                role="tab"
                aria-controls="v-pills-profile"
                aria-selected="false"
              >
                Done
              </button>
            </div>
            <div id="v-pills-tabContent">
              <div
                class="tab-pane fade show active d-flex"
                id="v-pills-home"
                role="tabpanel"
                aria-labelledby="v-pills-home-tab"
                tabindex="0"
              >
                <div class="d-flex">
                  <div class="position-relative">
                    <img :src="'/images/Ellipse14.png'" alt="" />
                    <span class="active-dot"></span>
                  </div>
                  <div style="padding-left: 15px">
                    <p class="m-0" style="font-size: 17px">
                      <span class="fw-bold">Julia Fox</span> wants to edit
                      <span class="fw-bold">Resident’s Access document</span>
                    </p>
                    <p
                      class="m-0 fw-normal"
                      style="font-size: 12px; color: #4e4e4e"
                    >
                      5 min ago
                    </p>

                    <div class="urgent-task">
                      <button class="btn">282 N Pine</button>
                      <button class="btn">Administrative</button>
                    </div>
                    <div style="margin-top: 30px">
                      <button class="btn btn-primary accept-btn">Accept</button>
                      <button class="btn btn-primary accept-btn">Deny</button>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="tab-pane fade"
                id="v-pills-profile"
                role="tabpanel"
                aria-labelledby="v-pills-profile-tab"
                tabindex="0"
              >
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta,
                incidunt!
              </div>
              <div
                class="tab-pane fade"
                id="v-pills-profile"
                role="tabpanel"
                aria-labelledby="v-pills-profile-tab"
                tabindex="0"
              >
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta,
                incidunt!rrrrrrrrrrr
              </div>
            </div>
            <div>
              <div class="d-flex tab-content">
                <div class="position-relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="39"
                    height="39"
                    viewBox="0 0 39 39"
                    fill="none"
                  >
                    <circle
                      cx="19.5"
                      cy="19.5"
                      r="19.5"
                      fill="url(#pattern0)"
                    />
                    <defs>
                      <pattern
                        id="pattern0"
                        patternContentUnits="objectBoundingBox"
                        width="1"
                        height="1"
                      >
                        <use
                          xlink:href="#image0_114_9524"
                          transform="translate(-0.0539906) scale(0.00469484)"
                        />
                      </pattern>
                      <image
                        id="image0_114_9524"
                        width="236"
                        height="213"
                        xlink:href="data:image/png;base64,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"
                      />
                    </defs>
                  </svg>
                  <span class="active-dot"></span>
                </div>
                <div style="padding-left: 15px">
                  <p class="m-0" style="font-size: 17px">
                    <span class="fw-bold"> Michael Jordan</span> added a file to
                    <span class="fw-bold">Admin</span>
                  </p>
                  <p
                    class="m-0 fw-normal"
                    style="
                      font-size: 12px;
                      color: #4e4e4e;
                      padding-bottom: 15px;
                    "
                  >
                    5 min ago
                  </p>
                  <div class="d-flex align-items-center user-select-all">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="36"
                      height="36"
                      viewBox="0 0 36 36"
                      fill="none"
                    >
                      <rect width="36" height="36" rx="4" fill="#47C5FE" />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M14.4617 11.5238C13.8869 11.5238 13.4209 12.0071 13.4209 12.6032V23.3972C13.4209 23.9933 13.8869 24.4766 14.4617 24.4766H21.5395C22.1144 24.4766 22.5804 23.9933 22.5804 23.3972V15.8414H19.0415C18.6966 15.8414 18.417 15.5514 18.417 15.1938V11.5238H14.4617ZM19.666 12.4397L21.6972 14.5461H19.666V12.4397ZM12.1719 12.6032C12.1719 11.2917 13.1971 10.2285 14.4617 10.2285H19.0415C19.2071 10.2285 19.366 10.2967 19.4831 10.4182L23.6465 14.7358C23.7636 14.8573 23.8294 15.022 23.8294 15.1938V23.3972C23.8294 24.7087 22.8042 25.7719 21.5395 25.7719H14.4617C13.1971 25.7719 12.1719 24.7087 12.1719 23.3972V12.6032Z"
                        fill="white"
                      />
                    </svg>
                    <div class="px-3">
                      <h5 class="m-0 fs-6 fw-bold">Document_01</h5>
                      <p
                        class="m-0"
                        style="font-size: 13px !important; font-weight: 400"
                      >
                        PDF. 1.4 MB
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex" style="padding: 20px 20px">
                <div class="position-relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="39"
                    height="39"
                    viewBox="0 0 39 39"
                    fill="none"
                  >
                    <circle
                      cx="19.5"
                      cy="19.5"
                      r="19.5"
                      fill="url(#pattern0)"
                    />
                    <defs>
                      <pattern
                        id="pattern0"
                        patternContentUnits="objectBoundingBox"
                        width="1"
                        height="1"
                      >
                        <use
                          xlink:href="#image0_114_9524"
                          transform="translate(-0.0539906) scale(0.00469484)"
                        />
                      </pattern>
                      <image
                        id="image0_114_9524"
                        width="236"
                        height="213"
                        xlink:href="data:image/png;base64,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"
                      />
                    </defs>
                  </svg>
                  <span class="active-dot"></span>
                </div>
                <div style="padding-left: 15px">
                  <p class="m-0" style="font-size: 17px">
                    <span class="fw-bold"> Michael Jordan</span> added a file to
                    <span class="fw-bold">Admin</span>
                  </p>
                  <p
                    class="m-0 fw-normal"
                    style="
                      font-size: 12px;
                      color: #4e4e4e;
                      padding-bottom: 15px;
                    "
                  >
                    5 min ago
                  </p>
                </div>
              </div>
              <div class="d-flex" style="padding: 20px 20px">
                <div class="position-relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="39"
                    height="39"
                    viewBox="0 0 39 39"
                    fill="none"
                  >
                    <circle
                      cx="19.5"
                      cy="19.5"
                      r="19.5"
                      fill="url(#pattern0)"
                    />
                    <defs>
                      <pattern
                        id="pattern0"
                        patternContentUnits="objectBoundingBox"
                        width="1"
                        height="1"
                      >
                        <use
                          xlink:href="#image0_114_9524"
                          transform="translate(-0.0539906) scale(0.00469484)"
                        />
                      </pattern>
                      <image
                        id="image0_114_9524"
                        width="236"
                        height="213"
                        xlink:href="data:image/png;base64,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"
                      />
                    </defs>
                  </svg>
                  <span class="active-dot"></span>
                </div>
                <div style="padding-left: 15px">
                  <p class="m-0" style="font-size: 17px">
                    <span class="fw-bold"> Michael Jordan</span> added a file to
                    <span class="fw-bold">Admin</span>
                  </p>
                  <p
                    class="m-0 fw-normal"
                    style="
                      font-size: 12px;
                      color: #4e4e4e;
                      padding-bottom: 15px;
                    "
                  >
                    5 min ago
                  </p>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="mark-read">
                  <a href="#">Mark all as read</a>
                </div>
                <div>
                  <button class="btn btn-dark all-notification">
                    View all notifications
                  </button>
                </div>
              </div>
            </div>
            <div class="see-more">
              <a href="#">View more</a>
            </div>
          </div>
        </div>
      </div>
      <section class="pending-task">
        <h4>Pending Tasks Search</h4>
        <p>TYPE OF PAYMENT</p>
        <div
          class="col-md d-flex col g-2 justify-content-between"
          style="height: 206px; flex-wrap: wrap; margin-bottom: 150px"
        >
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
          <div class="form-floating">
            <input
              type="email"
              class="form-control form-control-tags shadow"
              id="floatingInputGrid"
              placeholder="PARKING SPOTS ..."
              value="PARKING SPOTS ..."
            />
            <label
              for="floatingInputGrid"
              style="
                font-size: 14px;
                font-weight: 600;
                color: #cccccc;
                padding-left: 20px;
                margin-top: 3px;
              "
              >SEARCH BY NAME</label
            >
          </div>
        </div>
      </section>
      <section>
        <div class="d-flex align-items-center justify-content-between">
          <h4 style="font-size: 20px; font-weight: 700">Security & Access</h4>
          <div class="d-flex align-items-center">
            <button class="btn btn-primary add-new-task">+ Add new task</button>
            <div>
              <ul class="nav">
                <li class="nav-item">
                  <a
                    class="nav-link active nav-link-text"
                    aria-current="page"
                    href="#"
                    >Select</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-text" href="#">Select All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-text" href="#">Delete All</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="task-list">
          <h6 class="active-list-item">Review Camera Records</h6>
          <div class="d-flex align-items-center">
            <p class="m-0 active-list-item">16.01.2023</p>
            <button class="btn btn-primary normal-btn active-list-item">
              Normal
            </button>
            <a href="#">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="26"
                viewBox="0 0 26 26"
                fill="none"
              >
                <circle cx="13" cy="13" r="13" fill="#C2FF69" />
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M18.9228 8.76103C19.2285 9.04555 19.2367 9.5148 18.9413 9.80913L11.5005 17.2214C11.3536 17.3677 11.1507 17.4495 10.9393 17.4474C10.728 17.4454 10.5268 17.3598 10.383 17.2106L7.04748 13.7515C6.75821 13.4516 6.77625 12.9826 7.08777 12.704C7.39929 12.4255 7.88633 12.4428 8.17559 12.7428L10.9583 15.6286L17.8344 8.77879C18.1299 8.48446 18.6172 8.47651 18.9228 8.76103Z"
                  fill="black"
                />
              </svg>
            </a>
          </div>
        </div>
        <div class="task-list" style="background-color: #262626; color: white">
          <h6 class="">Give access to control checklist</h6>
          <div class="d-flex align-items-center">
            <p class="m-0">16.01.2023</p>
            <button
              class="btn btn-primary normal-btn"
              style="color: #262626; background-color: #ffb82e !important"
            >
              Medium
            </button>
            <a href="#">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="26"
                viewBox="0 0 26 26"
                fill="none"
              >
                <circle cx="13" cy="13" r="13" fill="white" />
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M18.9228 8.76103C19.2285 9.04555 19.2367 9.5148 18.9413 9.80913L11.5005 17.2214C11.3536 17.3677 11.1507 17.4495 10.9393 17.4474C10.728 17.4454 10.5268 17.3598 10.383 17.2106L7.04748 13.7515C6.75821 13.4516 6.77625 12.9826 7.08777 12.704C7.39929 12.4255 7.88633 12.4428 8.17559 12.7428L10.9583 15.6286L17.8344 8.77879C18.1299 8.48446 18.6172 8.47651 18.9228 8.76103Z"
                  fill="black"
                />
              </svg>
            </a>
          </div>
        </div>
        <div class="task-list">
          <h6 class="">Review Maintenance Checklist</h6>
          <div class="d-flex align-items-center">
            <p class="m-0">16.01.2023</p>
            <button
              class="btn btn-primary normal-btn"
              style="color: white; background-color: #ff6969 !important"
            >
              High
            </button>
            <a href="#">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="26"
                viewBox="0 0 26 26"
                fill="none"
              >
                <circle cx="13" cy="13" r="13" fill="#CCCCCC" />
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M18.9228 8.76103C19.2285 9.04555 19.2367 9.5148 18.9413 9.80913L11.5005 17.2214C11.3536 17.3677 11.1507 17.4495 10.9393 17.4474C10.728 17.4454 10.5268 17.3598 10.383 17.2106L7.04748 13.7515C6.75821 13.4516 6.77625 12.9826 7.08777 12.704C7.39929 12.4255 7.88633 12.4428 8.17559 12.7428L10.9583 15.6286L17.8344 8.77879C18.1299 8.48446 18.6172 8.47651 18.9228 8.76103Z"
                  fill="white"
                />
              </svg>
            </a>
          </div>
        </div>
        <div
          class="d-flex align-items-center justify-content-between"
          style="margin-top: 60px"
        >
          <h4 style="font-size: 20px; font-weight: 700">Spaces</h4>
          <div class="d-flex align-items-center">
            <button class="btn btn-primary add-new-task">+ Add new task</button>
            <div>
              <ul class="nav">
                <li class="nav-item">
                  <a
                    class="nav-link active nav-link-text"
                    aria-current="page"
                    href="#"
                    >Select</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-text" href="#">Select All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link nav-link-text" href="#">Delete All</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="task-list mt-4">
          <h6 class="">Update numeric parking spots</h6>
          <div class="d-flex align-items-center">
            <p class="m-0">16.01.2023</p>
            <button class="btn btn-primary normal-btn" style="color: black">
              Normal
            </button>
            <a href="#">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="26"
                viewBox="0 0 26 26"
                fill="none"
              >
                <circle cx="13" cy="13" r="13" fill="#C2FF69" />
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M18.9228 8.76103C19.2285 9.04555 19.2367 9.5148 18.9413 9.80913L11.5005 17.2214C11.3536 17.3677 11.1507 17.4495 10.9393 17.4474C10.728 17.4454 10.5268 17.3598 10.383 17.2106L7.04748 13.7515C6.75821 13.4516 6.77625 12.9826 7.08777 12.704C7.39929 12.4255 7.88633 12.4428 8.17559 12.7428L10.9583 15.6286L17.8344 8.77879C18.1299 8.48446 18.6172 8.47651 18.9228 8.76103Z"
                  fill="black"
                />
              </svg>
            </a>
          </div>
        </div>
      </section>
    </section>

    <section class="col-lg-6 col-12">
      <div class="p-2 heading">
        <h4 style="font-size: 20px; padding: 10px 32px; font-weight: 700">
          Give access to control checklist
        </h4>
      </div>
      <div class="row">
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Created by </label>
          <div class="d-flex align-items-center input-tag">
            <img :src="'/images/Ellipse1.png'" />
            <h6>John Doe</h6>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label"
            >Date Created</label
          >
          <input
            type="date"
            class="form-control"
            id="exampleInputEmail1"
            aria-describedby="emailHelp"
          />
        </div>
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Due Date</label>
          <input
            type="date"
            class="form-control"
            id="exampleInputEmail1"
            aria-describedby="emailHelp"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Assignee</label>
          <div class="input-tag d-flex align-items-center">
            <div class="d-flex align-items-center">
              <img :src="'/images/Ellipse1.png'" />
              <h6>John Doe</h6>
            </div>
            <div class="d-flex align-items-center">
              <img :src="'/images/Ellipse1.png'" />
              <h6>John Doe</h6>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Building</label>
          <input
            type="email"
            class="form-control"
            id="exampleInputEmail1"
            aria-describedby="emailHelp"
            value="238 N Pine"
            placeholder="238 N Pine"
          />
        </div>
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Unit</label>
          <input
            type="email"
            class="form-control"
            id="exampleInputEmail1"
            aria-describedby="emailHelp"
            placeholder="1C - Unit"
            value="1C - Unit"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <label for="exampleInputEmail1" class="form-label">Description</label>
          <textarea
            class="form-control"
            placeholder="lorem ipsum lorem ipsum "
            id="floatingTextarea2"
            style="height: 300px"
          >
Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae ipsam sit distinctio autem voluptas maiores quam perspiciatis blanditiis quibusdam eaque quidem optio dicta, consectetur, aliquid rerum, at odio amet officia.</textarea
          >
        </div>
      </div>
      <div class="col mt-5 d-flex">
        <div style="padding-right: 20px">
          <img :src="'/images/profile.png'" alt="image" />
        </div>
        <div class="w-100">
          <div class="position-relative" style="height: 200px">
            <textarea
              id="messageTextarea"
              class="form-control"
              style="height: 200px"
            >
            </textarea>
            <div style="top: 163px; left: 17px; position: absolute">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="16"
                viewBox="0 0 15 16"
                fill="none"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M8.79685 1.03849C10.1147 -0.265435 12.2484 -0.265435 13.5662 1.03849C14.8879 2.34627 14.8879 4.46953 13.5662 5.77731L6.36867 12.899C5.58086 13.6785 4.30645 13.6785 3.51865 12.899C2.72695 12.1156 2.72695 10.8426 3.51865 10.0593L9.75653 3.88716C10.0163 3.63016 10.4352 3.63238 10.6922 3.89212C10.9492 4.15186 10.9469 4.57075 10.6872 4.82775L4.44932 10.9999C4.18099 11.2654 4.18099 11.6929 4.44932 11.9584C4.72154 12.2277 5.16577 12.2277 5.438 11.9584L12.6356 4.83672C13.4339 4.04679 13.4339 2.76901 12.6356 1.97908C11.8333 1.1853 10.5298 1.1853 9.72753 1.97908L2.76283 8.87036C2.69358 8.93888 2.66663 8.96561 2.64182 8.99135C2.09724 9.55649 1.78619 10.3008 1.76746 11.0788C1.76661 11.1142 1.76656 11.1518 1.76656 11.2487C1.76656 11.4704 1.76675 11.5577 1.77015 11.6282C1.84769 13.2374 3.15059 14.5348 4.79202 14.6122C4.86387 14.6156 4.95276 14.6158 5.17651 14.6158C5.2744 14.6158 5.3127 14.6157 5.34881 14.6149C6.14093 14.5962 6.89636 14.2861 7.4679 13.7469C7.49393 13.7223 7.52095 13.6956 7.5902 13.6271L12.6356 8.63495C12.8953 8.37795 13.3142 8.38017 13.5712 8.63991C13.8282 8.89964 13.826 9.31854 13.5662 9.57554L8.51485 14.5737C8.45357 14.6343 8.41481 14.6726 8.37592 14.7093C7.56531 15.4741 6.49705 15.9114 5.37999 15.9377C5.32643 15.939 5.27179 15.939 5.18516 15.939H5.15583C4.95934 15.939 4.83682 15.939 4.72967 15.9339C2.41842 15.8249 0.559399 13.9937 0.448482 11.6919C0.44335 11.5853 0.443353 11.4636 0.44336 11.2697L0.44336 11.2402C0.443359 11.1545 0.443358 11.1002 0.444641 11.0469C0.471397 9.93577 0.915741 8.87566 1.689 8.07321C1.7261 8.0347 1.76487 7.99634 1.82619 7.93567L8.79685 1.03849Z"
                  fill="#999999"
                />
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="21"
                height="20"
                viewBox="0 0 21 20"
                fill="none"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10.501 3.21111C6.75214 3.21111 3.71306 6.25019 3.71306 9.99908C3.71306 13.748 6.75214 16.7871 10.501 16.7871C14.2499 16.7871 17.289 13.748 17.289 9.99908C17.289 6.25019 14.2499 3.21111 10.501 3.21111ZM2.35547 9.99908C2.35547 5.50041 6.00236 1.85352 10.501 1.85352C14.9997 1.85352 18.6466 5.50041 18.6466 9.99908C18.6466 14.4978 14.9997 18.1447 10.501 18.1447C6.00236 18.1447 2.35547 14.4978 2.35547 9.99908ZM6.88078 8.41522C6.88078 8.04033 7.18469 7.73643 7.55958 7.73643H8.46465C8.83953 7.73643 9.14344 8.04033 9.14344 8.41522C9.14344 8.79011 8.83953 9.09402 8.46465 9.09402H7.55958C7.18469 9.09402 6.88078 8.79011 6.88078 8.41522ZM11.8586 8.41522C11.8586 8.04033 12.1625 7.73643 12.5374 7.73643H13.4425C13.8174 7.73643 14.1213 8.04033 14.1213 8.41522C14.1213 8.79011 13.8174 9.09402 13.4425 9.09402H12.5374C12.1625 9.09402 11.8586 8.79011 11.8586 8.41522ZM7.70855 11.8809C8.04386 11.7132 8.45159 11.8491 8.61925 12.1844C8.92243 12.7908 9.65288 13.1668 10.501 13.1668C11.3492 13.1668 12.0796 12.7908 12.3828 12.1844C12.5505 11.8491 12.9582 11.7132 13.2935 11.8809C13.6288 12.0485 13.7648 12.4563 13.5971 12.7916C12.9952 13.9953 11.6893 14.5244 10.501 14.5244C9.31281 14.5244 8.00686 13.9953 7.40498 12.7916C7.23732 12.4563 7.37324 12.0485 7.70855 11.8809Z"
                  fill="#999999"
                />
              </svg>
            </div>
            <button
              id="myButton"
              class="btn dark-btn btn-sm position-absolute p-2 rounded-pill comment-btn"
              type="button"
              style="bottom: 8px; right: 8px"
            >
              Comment
            </button>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
