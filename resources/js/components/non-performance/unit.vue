<template>
  <div class="non-performance-unit">
    <div class="row g-3 mb-5">

      <div class="col-lg-3">
        <h3 class="h3 mb-24">Courtesy notice</h3>
        <div v-for="data in this.dataArray.courtesy"
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0" v-if="data.unit">{{ data.unit.unit_no }} - <span
                        v-if="data.unit.building">{{ data.unit.building.name }}</span></h3>
                    <p class="text-normal color-primary mb-8" v-if="data.unit && data.unit.guarantor">
                      {{ data.unit.guarantor.name }}</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                      <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img v-if="data.unit && data.unit.guarantor"
                          :src="data.unit.guarantor && data.unit.guarantor.profile_picture != ''
                             ? data.unit.guarantor : '/images/default-user.jpg'"
                             class="avatar-img"
                             />
                             <span class="verifiyIcon"
                             v-if="data.unit && data.unit.guarantor && data.unit.guarantor.verified">
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

                <hr class="mt-0 mb-12 color-dark" />

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <h3 class="h3 mb-24">Default notice</h3>
        <div v-for="data in this.dataArray.default"
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0" v-if="data.unit">{{ data.unit.unit_no }} - <span
                        v-if="data.unit.building">{{ data.unit.building.name }}</span></h3>
                    <p class="text-normal  mb-8" v-if="data.unit && data.unit.guarantor">
                      {{ data.unit.guarantor.name }}</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                      <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img v-if="data.unit && data.unit.guarantor"
                               :src="data.unit.guarantor && data.unit.guarantor.profile_picture != ''
                               ? data.unit.guarantor : '/images/default-user.jpg'"
                               class="avatar-img"
                          />
                          <span class="verifiyIcon"
                                v-if="data.unit && data.unit.guarantor && data.unit.guarantor.verified">
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

                <hr class="mt-0 mb-12 color-dark" />

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <h3 class="h3 mb-24">Shutoff notice</h3>
        <div v-for="data in this.dataArray.shutoff"
            class="row"
            :class="{ cardCollapse: isCollapse, 'mb-2': !isCollapse }"
        >
          <div class="col-12">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="h3 mb-0" v-if="data.unit">{{ data.unit.unit_no }} - <span
                        v-if="data.unit.building">{{ data.unit.building.name }}</span></h3>
                    <p class="text-normal  mb-8" v-if="data.unit && data.unit.guarantor">
                      {{ data.unit.guarantor.name }}</p>
                  </div>
                  <div>
                    <div class="d-inline-block mx-auto mx-lg-0">
                      <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img v-if="data.unit && data.unit.guarantor"
                               :src="data.unit.guarantor && data.unit.guarantor.profile_picture != ''
                               ? data.unit.guarantor : '/images/default-user.jpg'"
                               class="avatar-img"
                          />
                          <span class="verifiyIcon"
                                v-if="data.unit && data.unit.guarantor && data.unit.guarantor.verified">
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

                <hr class="mt-0 mb-12 color-dark" />

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
  created() {
    this.loadData();
  },
  data() {
    return {
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      dataArray: {},
      isCollapse: false,
    };
  },

  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      this.$http
          .post("/nonPerformance", params)
          .then((response) => {
            response = response.data;
            if (response.status)
              this.dataArray = response;
            else
              Toast.fire({
                icon: "error",
                title: "Something went wrong.",
              });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    toggleCollapse() {
      this.isCollapse = !this.isCollapse;
    },
  },
};
</script>
