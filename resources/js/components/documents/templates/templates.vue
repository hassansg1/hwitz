<template>
  <div class="documents-packets">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href="">Select</a>
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
        >Select all</a
      >
      <a class="align-self-center a-link mb-16 mb-lg-0" href="">Delete all</a>
    </div>

    <div class="row g-3 mb-16">
      <div
        class="col-xl-3 col-lg-4 col-md-6 col-12"
        v-for="item in visibleItemsP1"
      >
        <div class="bkg-secondary doc-card">
          <div class="row justify-content-center">
            <div class="col-9 bg-white top-radius p-20 pb-0">
              <div class="top-bar"></div>
              <p class="text-medium-lg mb-0">Your Name</p>
              <p class="text-sm-bold text-success mb-0">Industrial designer</p>
              <p class="text-sm mb-0">test@mail.com</p>
              <p class="text-sm mb-0">+1 234 5678 890</p>
            </div>
          </div>
          <div class="bg-white p-12 bottom-radius">
            <div class="row">
              <div class="col-9 text-truncate">
                <h3 class="h3">{{ item }}</h3>
              </div>
              <div class="col-3 align-self-center mb-8">
                <div class="d-flex justify-content-between">
                  <img
                    class="select-cursor"
                    src="/images/icons/edit.png"
                    alt=""
                    data-bs-toggle="modal"
                    data-bs-target="#editTemplateModal"
                  />
                  <img
                    class="select-cursor"
                    src="/images/icons/v-dots.png"
                    alt=""
                    data-bs-toggle="modal"
                    data-bs-target="#amendTemplateModal"
                  />
                </div>
              </div>
            </div>
            <div class="row mb-4">
              <div
                class="col-6 text-uppercase text-sm-semibold color-secondary-dark"
              >
                Version
              </div>
              <div
                class="col-3 px-0"
                id="dropdownMenuOffset"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <div
                  class="text-uppercase text-sm-semibold color-secondary-dark select-cursor"
                >
                  Pending
                </div>
                <div class="dropdown">
                  <div
                    class="dropdown-menu dropdown-menu-end border-standered p-3 template-dropdown"
                    aria-labelledby="dropdownMenuOffset"
                  >
                    <p class="text-sm mb-16 color-secondary">Pending</p>
                    <div class="d-flex flex-wrap justify-content-center mb-16">
                      <div class="template-avatar me-8 mb-8" v-for="item in 8">
                        <img
                          src="/images/test-avatar.png"
                          class="avatar-img"
                          alt="user-avatar"
                        />
                      </div>
                    </div>
                    <div class="text-end">
                      <button class="btn btn-primary">Alert</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-3 px-0">
                <div
                  class="text-uppercase text-sm-semibold color-secondary-dark select-cursor"
                  id="testId"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Signed
                </div>
                <div class="dropdown">
                  <ul
                    class="dropdown-menu dropdown-menu-end border-standered"
                    aria-labelledby="dropdownMenuOffset"
                  >
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Review
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Edit Template
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Create Amendment
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Share with
                    </li>
                    <li class="px-2 py-1 border-bottom text-sm-bold">
                      Change name
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Make a copy
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Download
                    </li>
                    <li
                      class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    >
                      Delete
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-6 text-uppercase text-normal-bold-lg">
                Version 7.1
              </div>
              <div class="col-3 px-0">
                <div class="num-container bkg-danger text-white">5</div>
              </div>
              <div class="col-3 px-0">
                <div class="num-container bkg-success">10</div>
              </div>
            </div>

            <div class="row mb-4" v-for="item in 2">
              <div class="col-6 text-uppercase text-normal-sm">
                Amendment {{ item }}
              </div>
              <div class="col-3 px-0">
                <div class="num-container bkg-danger text-white">5</div>
              </div>
              <div class="col-3 px-0">
                <div class="num-container bkg-success">10</div>
              </div>
            </div>
            <div class="mb-8">
              <a
                class="a-link"
                data-bs-toggle="modal"
                data-bs-target="#versionModal"
                >See all versions</a
              >
            </div>
            <div class="dropdown">
              <div data-bs-toggle="dropdown" aria-expanded="false">
                <button class="btn btn-secondary" type="button">
                  <span class="dot bkg-success me-8"></span> Low priority
                </button>
                <img src="/images/icons/down-arrow.png" />
              </div>

              <ul class="dropdown-menu py-2 px-3">
                <li>
                  <a class="dropdown-item btn bkg-danger text-white"
                    >High priority</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a class="a-link" @click="loadMoreP1()">{{ showTextP1 }}</a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      p1: [],
      p2: [],
      visibleItemsP1: [],
      visibleItemsP2: [],
      showAllP1: false,
      showAllP2: false,
    };
  },
  methods: {
    loadMoreP1() {
      if (!this.showAllP1) {
        this.visibleItemsP1 = this.p1;
        this.showAllP1 = true;
      } else {
        const visibleItemCount = Math.ceil(this.p1.length / 2);
        this.visibleItemsP1 = this.p1.slice(0, visibleItemCount);
        this.showAllP1 = false;
      }
    },

    loadMoreP2() {
      if (!this.showAllP2) {
        this.visibleItemsP2 = this.p2;
        this.showAllP2 = true;
      } else {
        const visibleItemCount = Math.ceil(this.p2.length / 2);
        this.visibleItemsP2 = this.p2.slice(0, visibleItemCount);
        this.showAllP2 = false;
      }
    },
  },

  mounted() {
    this.p1 = [
      "Contract template",
      "Lease document",
      "Template 01",
      "Owners document",
      "Contract template",
      "Lease document",
      "Template 01",
      "Owners document",
    ];
    this.p2 = [
      "Contract template",
      "Lease document",
      "Template 01",
      "Owners document",
      "Contract template",
      "Lease document",
      "Template 01",
      "Owners document",
    ];

    const visibleItemCountp1 = Math.ceil(this.p1.length / 2);
    this.visibleItemsP1 = this.p1.slice(0, visibleItemCountp1);

    const visibleItemCountp2 = Math.ceil(this.p2.length / 2);
    this.visibleItemsP2 = this.p1.slice(0, visibleItemCountp2);
  },

  computed: {
    showTextP1() {
      return !this.showAllP1 ? "show more" : "show less";
    },
    showTextP2() {
      return !this.showAllP2 ? "show more" : "show less";
    },
  },
};
</script>
