<template>
  <div class="doors-section">
    <div
      class="modal fade"
      id="doorsModal"
      tabindex="-1"
      aria-labelledby="doorModalLabel"
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
            <h3 class="h3 mb-28">Add Exception</h3>

            <div class="d-flex mb-28">
              <div class="me-20">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M9.99878 3.49459C6.40554 3.49459 3.49264 6.40749 3.49264 10.0007C3.49264 13.594 6.40554 16.5069 9.99878 16.5069C13.592 16.5069 16.5049 13.594 16.5049 10.0007C16.5049 6.40749 13.592 3.49459 9.99878 3.49459ZM2.19141 10.0007C2.19141 5.68884 5.68689 2.19336 9.99878 2.19336C14.3107 2.19336 17.8062 5.68884 17.8062 10.0007C17.8062 14.3126 14.3107 17.8081 9.99878 17.8081C5.68689 17.8081 2.19141 14.3126 2.19141 10.0007ZM9.78191 5.6633C10.1412 5.6633 10.4325 5.95459 10.4325 6.31392V9.56699H11.9506C12.3099 9.56699 12.6012 9.85828 12.6012 10.2176C12.6012 10.5769 12.3099 10.8682 11.9506 10.8682H9.78191C9.42258 10.8682 9.13129 10.5769 9.13129 10.2176V6.31392C9.13129 5.95459 9.42258 5.6633 9.78191 5.6633Z"
                    fill="#47C5FE"
                  />
                </svg>
              </div>

              <div>
                <p class="text-medium-bold mb-4">Schedule doors</p>
                <p class="text-sm color-secondary mb-4">
                  Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                  Suscipit, beatae!
                </p>
              </div>
            </div>

            <div class="row gy-3 mb-40 doors-multiselect-section">
              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.23352 1.05273H13.7683C15.0687 1.05273 16.1229 2.10693 16.1229 3.40735V16.5932C16.1229 17.8936 15.0687 18.9478 13.7683 18.9478H6.23352C4.9331 18.9478 3.87891 17.8936 3.87891 16.5932V3.40735C3.87891 2.10693 4.9331 1.05273 6.23352 1.05273ZM6.23352 2.4655C5.71335 2.4655 5.29168 2.88718 5.29168 3.40735V16.5932C5.29168 17.1134 5.71335 17.535 6.23352 17.535H13.7683C14.2885 17.535 14.7101 17.1134 14.7101 16.5932V3.40735C14.7101 2.88718 14.2885 2.4655 13.7683 2.4655H6.23352Z"
                            fill="black"
                          />
                          <path
                            d="M12.8263 10.9421C12.8263 11.4623 12.4046 11.884 11.8845 11.884C11.3643 11.884 10.9426 11.4623 10.9426 10.9421C10.9426 10.422 11.3643 10.0003 11.8845 10.0003C12.4046 10.0003 12.8263 10.422 12.8263 10.9421Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Name</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M10.0005 3.54175C6.43342 3.54175 3.54175 6.43342 3.54175 10.0005C3.54175 13.5675 6.43342 16.4592 10.0005 16.4592C13.5675 16.4592 16.4592 13.5675 16.4592 10.0005C16.4592 6.43342 13.5675 3.54175 10.0005 3.54175ZM2.25 10.0005C2.25 5.72 5.72 2.25 10.0005 2.25C14.2809 2.25 17.7509 5.72 17.7509 10.0005C17.7509 14.2809 14.2809 17.7509 10.0005 17.7509C5.72 17.7509 2.25 14.2809 2.25 10.0005ZM10.0005 6.12524C7.86024 6.12524 6.12524 7.86024 6.12524 10.0005C6.12524 10.3572 5.83607 10.6463 5.47936 10.6463C5.12266 10.6463 4.83349 10.3572 4.83349 10.0005C4.83349 7.14683 7.14683 4.83349 10.0005 4.83349C10.3572 4.83349 10.6463 5.12266 10.6463 5.47936C10.6463 5.83607 10.3572 6.12524 10.0005 6.12524ZM14.1171 5.88383C14.3693 6.13606 14.3693 6.545 14.1171 6.79723L11.5865 9.32784C11.6743 9.53449 11.7228 9.76181 11.7228 10.0005C11.7228 10.9517 10.9517 11.7228 10.0005 11.7228C9.04926 11.7228 8.27815 10.9517 8.27815 10.0005C8.27815 9.04926 9.04926 8.27815 10.0005 8.27815C10.2391 8.27815 10.4665 8.32669 10.6731 8.41444L13.2037 5.88383C13.4559 5.6316 13.8649 5.6316 14.1171 5.88383ZM10.0005 9.56989C9.76267 9.56989 9.56989 9.76267 9.56989 10.0005C9.56989 10.2383 9.76267 10.4311 10.0005 10.4311C10.2383 10.4311 10.4311 10.2383 10.4311 10.0005C10.4311 9.76267 10.2383 9.56989 10.0005 9.56989Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Action</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.23352 1.05273H13.7683C15.0687 1.05273 16.1229 2.10693 16.1229 3.40735V16.5932C16.1229 17.8936 15.0687 18.9478 13.7683 18.9478H6.23352C4.9331 18.9478 3.87891 17.8936 3.87891 16.5932V3.40735C3.87891 2.10693 4.9331 1.05273 6.23352 1.05273ZM6.23352 2.4655C5.71335 2.4655 5.29168 2.88718 5.29168 3.40735V16.5932C5.29168 17.1134 5.71335 17.535 6.23352 17.535H13.7683C14.2885 17.535 14.7101 17.1134 14.7101 16.5932V3.40735C14.7101 2.88718 14.2885 2.4655 13.7683 2.4655H6.23352Z"
                            fill="black"
                          />
                          <path
                            d="M12.8263 10.9421C12.8263 11.4623 12.4046 11.884 11.8845 11.884C11.3643 11.884 10.9426 11.4623 10.9426 10.9421C10.9426 10.422 11.3643 10.0003 11.8845 10.0003C12.4046 10.0003 12.8263 10.422 12.8263 10.9421Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Doors</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.23352 1.05273H13.7683C15.0687 1.05273 16.1229 2.10693 16.1229 3.40735V16.5932C16.1229 17.8936 15.0687 18.9478 13.7683 18.9478H6.23352C4.9331 18.9478 3.87891 17.8936 3.87891 16.5932V3.40735C3.87891 2.10693 4.9331 1.05273 6.23352 1.05273ZM6.23352 2.4655C5.71335 2.4655 5.29168 2.88718 5.29168 3.40735V16.5932C5.29168 17.1134 5.71335 17.535 6.23352 17.535H13.7683C14.2885 17.535 14.7101 17.1134 14.7101 16.5932V3.40735C14.7101 2.88718 14.2885 2.4655 13.7683 2.4655H6.23352Z"
                            fill="black"
                          />
                          <path
                            d="M12.8263 10.9421C12.8263 11.4623 12.4046 11.884 11.8845 11.884C11.3643 11.884 10.9426 11.4623 10.9426 10.9421C10.9426 10.422 11.3643 10.0003 11.8845 10.0003C12.4046 10.0003 12.8263 10.422 12.8263 10.9421Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Repeats every</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.23352 1.05273H13.7683C15.0687 1.05273 16.1229 2.10693 16.1229 3.40735V16.5932C16.1229 17.8936 15.0687 18.9478 13.7683 18.9478H6.23352C4.9331 18.9478 3.87891 17.8936 3.87891 16.5932V3.40735C3.87891 2.10693 4.9331 1.05273 6.23352 1.05273ZM6.23352 2.4655C5.71335 2.4655 5.29168 2.88718 5.29168 3.40735V16.5932C5.29168 17.1134 5.71335 17.535 6.23352 17.535H13.7683C14.2885 17.535 14.7101 17.1134 14.7101 16.5932V3.40735C14.7101 2.88718 14.2885 2.4655 13.7683 2.4655H6.23352Z"
                            fill="black"
                          />
                          <path
                            d="M12.8263 10.9421C12.8263 11.4623 12.4046 11.884 11.8845 11.884C11.3643 11.884 10.9426 11.4623 10.9426 10.9421C10.9426 10.422 11.3643 10.0003 11.8845 10.0003C12.4046 10.0003 12.8263 10.422 12.8263 10.9421Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Repeat on</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M7.16656 2.15234C7.52777 2.15234 7.82059 2.44516 7.82059 2.80637V3.89642H12.1808V2.80637C12.1808 2.44516 12.4736 2.15234 12.8348 2.15234C13.196 2.15234 13.4888 2.44516 13.4888 2.80637V3.89642H15.4509C16.7754 3.89642 17.849 4.97009 17.849 6.29452V15.4509C17.849 16.7754 16.7754 17.849 15.4509 17.849H4.55045C3.22601 17.849 2.15234 16.7754 2.15234 15.4509V6.29452C2.15234 4.97009 3.22601 3.89642 4.55045 3.89642H6.51253V2.80637C6.51253 2.44516 6.80535 2.15234 7.16656 2.15234ZM6.51253 5.20448H4.55045C3.94843 5.20448 3.4604 5.69251 3.4604 6.29452V8.25661H16.541V6.29452C16.541 5.69251 16.0529 5.20448 15.4509 5.20448H13.4888V5.85851C13.4888 6.21972 13.196 6.51253 12.8348 6.51253C12.4736 6.51253 12.1808 6.21972 12.1808 5.85851V5.20448H7.82059V5.85851C7.82059 6.21972 7.52777 6.51253 7.16656 6.51253C6.80535 6.51253 6.51253 6.21972 6.51253 5.85851V5.20448ZM16.541 9.56467H3.4604V15.4509C3.4604 16.0529 3.94843 16.541 4.55045 16.541H15.4509C16.0529 16.541 16.541 16.0529 16.541 15.4509V9.56467Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Start date</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M7.16656 2.15234C7.52777 2.15234 7.82059 2.44516 7.82059 2.80637V3.89642H12.1808V2.80637C12.1808 2.44516 12.4736 2.15234 12.8348 2.15234C13.196 2.15234 13.4888 2.44516 13.4888 2.80637V3.89642H15.4509C16.7754 3.89642 17.849 4.97009 17.849 6.29452V15.4509C17.849 16.7754 16.7754 17.849 15.4509 17.849H4.55045C3.22601 17.849 2.15234 16.7754 2.15234 15.4509V6.29452C2.15234 4.97009 3.22601 3.89642 4.55045 3.89642H6.51253V2.80637C6.51253 2.44516 6.80535 2.15234 7.16656 2.15234ZM6.51253 5.20448H4.55045C3.94843 5.20448 3.4604 5.69251 3.4604 6.29452V8.25661H16.541V6.29452C16.541 5.69251 16.0529 5.20448 15.4509 5.20448H13.4888V5.85851C13.4888 6.21972 13.196 6.51253 12.8348 6.51253C12.4736 6.51253 12.1808 6.21972 12.1808 5.85851V5.20448H7.82059V5.85851C7.82059 6.21972 7.52777 6.51253 7.16656 6.51253C6.80535 6.51253 6.51253 6.21972 6.51253 5.85851V5.20448ZM16.541 9.56467H3.4604V15.4509C3.4604 16.0529 3.94843 16.541 4.55045 16.541H15.4509C16.0529 16.541 16.541 16.0529 16.541 15.4509V9.56467Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      End date</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.99878 3.49459C6.40554 3.49459 3.49264 6.40749 3.49264 10.0007C3.49264 13.594 6.40554 16.5069 9.99878 16.5069C13.592 16.5069 16.5049 13.594 16.5049 10.0007C16.5049 6.40749 13.592 3.49459 9.99878 3.49459ZM2.19141 10.0007C2.19141 5.68884 5.68689 2.19336 9.99878 2.19336C14.3107 2.19336 17.8062 5.68884 17.8062 10.0007C17.8062 14.3126 14.3107 17.8081 9.99878 17.8081C5.68689 17.8081 2.19141 14.3126 2.19141 10.0007ZM9.78191 5.6633C10.1412 5.6633 10.4325 5.95459 10.4325 6.31392V9.56699H11.9506C12.3099 9.56699 12.6012 9.85828 12.6012 10.2176C12.6012 10.5769 12.3099 10.8682 11.9506 10.8682H9.78191C9.42258 10.8682 9.13129 10.5769 9.13129 10.2176V6.31392C9.13129 5.95459 9.42258 5.6633 9.78191 5.6633Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      Start time</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="multiselect-border-shadow mb-18">
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      ><span class="me-10">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.99878 3.49459C6.40554 3.49459 3.49264 6.40749 3.49264 10.0007C3.49264 13.594 6.40554 16.5069 9.99878 16.5069C13.592 16.5069 16.5049 13.594 16.5049 10.0007C16.5049 6.40749 13.592 3.49459 9.99878 3.49459ZM2.19141 10.0007C2.19141 5.68884 5.68689 2.19336 9.99878 2.19336C14.3107 2.19336 17.8062 5.68884 17.8062 10.0007C17.8062 14.3126 14.3107 17.8081 9.99878 17.8081C5.68689 17.8081 2.19141 14.3126 2.19141 10.0007ZM9.78191 5.6633C10.1412 5.6633 10.4325 5.95459 10.4325 6.31392V9.56699H11.9506C12.3099 9.56699 12.6012 9.85828 12.6012 10.2176C12.6012 10.5769 12.3099 10.8682 11.9506 10.8682H9.78191C9.42258 10.8682 9.13129 10.5769 9.13129 10.2176V6.31392C9.13129 5.95459 9.42258 5.6633 9.78191 5.6633Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      End time</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="value1"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="name"
                      track-by="code"
                      :options="options1"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>
            </div>

            <h3 class="h3 mb-28">Calendar View</h3>

            <div class="d-flex flex-row flex-wrap mb-20">
              <div class="align-self-center text-medium-bold me-28">
                March 2023
              </div>
              <div class="d-flex align-self-center me-28">
                <span class="pagination-btn">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 21 21"
                    fill="none"
                  >
                    <path
                      d="M13 5.5L8 10.5L13 15.5"
                      stroke="#999999"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg> </span
                ><span class="align-self-center text-medium-bold me-10"
                  >5 to 10</span
                >

                <span class="pagination-btn">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 21 21"
                    fill="none"
                  >
                    <path
                      d="M8 5.5L13 10.5L8 15.5"
                      stroke="#999999"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </span>
              </div>
              <div class="text-medium-bold align-self-center me-28">
                Active exception(s):
                <span class="text-normal-bold-md color-secondary"
                  >Test Standard</span
                >
              </div>
              <div class="text-medium-bold align-self-center">
                Custom exception(s):
                <span class="text-normal-bold-md color-secondary"
                  >Exception 01</span
                >
              </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 mb-28">
              <div class="dropdown-wrapper">
                <label class="dropdown-label">Buildings</label>
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>

            <!-- <h1>kjdhfsdj fjsfh jksf</h1> -->
            <div id="schedule" class="mb-20 overflow-hidden"></div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary">Schedule</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
  components: {
    Multiselect,
  },
  data: function () {
    return {
      startValue: [{ name: "07:00 AM", code: "1" }],
      startOptions: [
        { units: "07:00 AM", code: "1" },
        { name: "08:00 AM", code: "2" },
        { name: "09:00 AM", code: "3" },
        { name: "10:00 AM", code: "4" },
      ],

      endValue: [{ name: "07:00 AM", code: "1" }],
      endOptions: [
        { units: "07:00 AM", code: "1" },
        { name: "08:00 AM", code: "2" },
        { name: "09:00 AM", code: "3" },
        { name: "10:00 AM", code: "4" },
      ],

      value: [{ name: "Building 1", code: "1" }],
      options: [
        { units: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],
      value1: [{ name: "payment 1", code: "1" }],
      options1: [
        { name: "payment 1", code: "1" },
        { name: "payment 2", code: "2" },
        { name: "payment 3", code: "3" },
        { name: "payment 4", code: "4" },
      ],
    };
  },
  mounted() {
    const script0 = document.createElement("script");
    script0.setAttribute(
      "src",
      "/public/jquery-schedule/dist/jquery.schedule.min.js"
    ); // Replace 'path/to/jquery-schedule.js' with the correct path to the script
    script0.async = true;

    // Wait for the script to load before initializing jquery-schedule
    script0.onload = () => {
      // $("#schedule").jqs();
      // Full options
      $("#schedule").jqs({
        mode: "edit",
        hour: 24,
        days: 7,
        periodDuration: 30,
        data: [],
        periodOptions: true,
        periodColors: [],
        periodTitle: "",
        periodBackgroundColor: "rgba(82, 155, 255, 0.5)",
        periodBorderColor: "#2a3cff",
        periodTextColor: "#000",
        periodRemoveButton: "Remove",
        periodDuplicateButton: "Duplicate",
        periodTitlePlaceholder: "Title",
        daysList: [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday",
          "Sunday",
        ],
        onInit: function () {},
        onAddPeriod: function () {},
        onRemovePeriod: function () {},
        onDuplicatePeriod: function () {},
        onClickPeriod: function () {},
      });

      console.log("jquery-schedule initialized");
    };
    document.head.appendChild(script0);

    console.log(script0);
  },
};
</script>
