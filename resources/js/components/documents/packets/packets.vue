<template>
  <div class="documents-packets">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href="">Select</a>
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
        >Select all</a
      >
      <a class="align-self-center a-link mb-16 mb-lg-0" href="">Delete all</a>
    </div>

    <div class="bkg-secondary p-36 border-17 mb-24">
      <div class="d-flex flex-wrap mb-16">
        <div class="me-12 mb-12 mb-md-0">
          <h3 class="h3">Packets 01</h3>
        </div>

        <div class="me-20 mb-12 mb-md-0">
          <img src="/images/icons/edit.png" />
        </div>

        <div class="me-12 mb-12 mb-md-0">
          <button
            class="btn px-32 py-9 bkg-primary text-dark"
            data-bs-toggle="modal"
            data-bs-target="#sendPacketModal"
          >
            send
          </button>
        </div>
        <div class="me-12 mb-12 mb-md-0">
          <button
            class="btn px-32 py-9 bkg-gray text-white edit-btn"
            data-bs-toggle="modal"
            data-bs-target="#editPacketModal"
          >
            edit packets
          </button>
        </div>
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
                <p class="text-sm-bold text-success mb-0">
                  Industrial designer
                </p>
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
                    <img src="/images/icons/edit.png" alt="" />
                    <img src="/images/icons/v-dots.png" alt="" />
                  </div>
                </div>
              </div>
              <div class="row mb-4">
                <div
                  class="col-6 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Versions
                </div>
                <div
                  class="col-3 px-0 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Pending
                </div>
                <div
                  class="col-3 px-0 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Signed
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
              <div class="mb-4">
                <a class="a-link">See all versions</a>
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

    <div class="bkg-secondary p-36 border-17 mb-24">
      <div class="d-flex flex-wrap mb-16">
        <div class="me-12 mb-12 mb-md-0">
          <h3 class="h3">Packets 02</h3>
        </div>

        <div class="me-20 mb-12 mb-md-0">
          <img src="/images/icons/edit.png" />
        </div>

        <div class="me-12 mb-12 mb-md-0">
          <button class="btn px-32 py-9 bkg-primary text-dark">send</button>
        </div>
        <div class="me-12 mb-12 mb-md-0">
          <button class="btn px-32 py-9 bkg-gray text-white edit-btn">
            edit packets
          </button>
        </div>
      </div>

      <div class="row g-3 mb-16">
        <div
          class="col-xl-3 col-lg-4 col-md-6 col-12"
          v-for="item in visibleItemsP2"
        >
          <div class="bkg-secondary doc-card">
            <div class="row justify-content-center">
              <div class="col-9 bg-white top-radius p-20 pb-0">
                <div class="top-bar"></div>
                <p class="text-medium-lg mb-0">Your Name</p>
                <p class="text-sm-bold text-success mb-0">
                  Industrial designer
                </p>
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
                    <img src="/images/icons/edit.png" alt="" />
                    <img src="/images/icons/v-dots.png" alt="" />
                  </div>
                </div>
              </div>
              <div class="row mb-4">
                <div
                  class="col-6 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Versions
                </div>
                <div
                  class="col-3 px-0 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Pending
                </div>
                <div
                  class="col-3 px-0 text-uppercase text-sm-semibold color-secondary-dark"
                >
                  Signed
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
              <div class="mb-4">
                <a class="a-link">See all versions</a>
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
        <a class="a-link" @click="loadMoreP2()">{{ showTextP2 }}</a>
      </div>
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
