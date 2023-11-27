<template>
  <div class="non-performance-unit">
    <div class="row g-3 mb-5">
      <div class="col-lg-3">
        <h3 class="h3 mb-24">First notice</h3>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                          <div class="position-relative avatar-sm overflow-visible">
                            <img
                            :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium text-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center">
          <button class="btn bkg-success">+Add unit</button>
        </div>
      </div>

      <div class="col-lg-3">
        <h3 class="h3 mb-24">Second notice</h3>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal color-primary mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium color-secondary mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center">
          <button class="btn bkg-success">+Add unit</button>
        </div>
      </div>

      <div class="col-lg-3">
        <h3 class="h3 mb-24">Thired notice</h3>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button class="btn bkg-success">+Add unit</button>
        </div>
      </div>

      <div class="col-lg-3">
        <h3 class="h3 mb-24">Block Services</h3>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 color-dark" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button class="btn bkg-success">+Add unit</button>
        </div>
      </div>
    </div>

    <div class="text-center">
      <div
          class="d-inline-block text-medium color-primary border-b select-cursor"
          @click="toggleCollapse()"
      >
        {{ isCollapse ? "Show more" : "Hide" }}
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        <h3 class="h3 mb-24">Blacklist - Eviction</h3>
        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-dark text-danger">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 text-danger" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col">
            <div class="card bg-dark text-danger">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                        <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 text-danger" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-dark text-danger">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0">1B - Unit</h3>
                    <p class="text-normal mb-8">Jane Doe</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                      <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                          :src="'/images/test-avatar.png'"
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

                <hr class="mt-0 mb-12 text-danger" />

                <p class="text-medium mb-8">
                  Notice sent - 5 days to second notice
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center">
          <button class="btn bkg-success">+Add unit</button>
        </div>
      </div>
    </div>

    <!-- <div class="row fixed-bottom">
      <div class="col-12 text-center">
        <div class="text-medium color-primary btn" @click="toggleCollapse()">
          {{ isCollapse ? "Show Detail" : "Hide" }}
        </div>
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      isCollapse: true,
    };
  },

  methods: {
    toggleCollapse() {
      this.isCollapse = !this.isCollapse;
    },
  },
};
</script>
