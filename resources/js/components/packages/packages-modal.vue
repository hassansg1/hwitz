<template>
  <div class="packages-section">
    <div
      class="modal fade"
      id="packagesModal"
      tabindex="-1"
      aria-labelledby="packagesModal"
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
          <div class="modal-body">
            <div>
              <h1 class="h1 mb-12">Alert notice</h1>
              <p class="text-normal color-secondary mb-72 w-50 mx-auto">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Eligendi, alias sapiente. Ab laboriosam nihil, laudantium unde
                cupiditate illum officia rem?
              </p>

              <div class="text-start">
                <div class="row">
                  <div
                    class="col-xl-4 col-lg-6 col-md-8 offset-xl-4 offset-lg-3 offset-md-2"
                  >
                    <div class="d-flex justify-content-between mb-8">
                      <div class="align-self-center">
                        <h3 class="h3 mb-0">Parking 1</h3>
                        <p class="text-normal color-primary">
                          Olivia Johnson - <span>777 - 818 -0582</span>
                        </p>
                      </div>
                      <div class="align-self-center">
                        <div class="position-relative text-center">
                          <div class="avatar-sm">
                            <img
                              :src="'/images/test-avatar.png'"
                              class="avatar-img"
                            />
                          </div>
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

                    <div class="mb-32">
                      <div class="multiselect-border">
                        <div class="dropdown-wrapper">
                          <label class="dropdown-label text-uppercase"
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
                                  d="M11.1038 4.43821C10.6214 3.61867 9.37921 3.61867 8.89682 4.4382L3.03794 14.3918C2.58872 15.155 3.13436 16.1746 4.14143 16.1746H15.8592C16.8663 16.1746 17.4119 15.155 16.9627 14.3918L11.1038 4.43821ZM7.66867 3.71529C8.70217 1.95948 11.2985 1.95949 12.332 3.7153L18.1908 13.6689C19.2416 15.454 17.8842 17.5997 15.8592 17.5997H4.14143C2.11645 17.5997 0.759053 15.454 1.80979 13.6689L7.66867 3.71529ZM10.0003 6.19874C10.3938 6.19874 10.7129 6.51777 10.7129 6.9113L10.7129 10.2366C10.7129 10.6301 10.3938 10.9491 10.0003 10.9491C9.60678 10.9491 9.28775 10.6301 9.28775 10.2366L9.28776 6.9113C9.28776 6.51777 9.60678 6.19874 10.0003 6.19874ZM10.0003 11.8992C10.3938 11.8992 10.7129 12.2182 10.7129 12.6118V14.0369C10.7129 14.4304 10.3938 14.7494 10.0003 14.7494C9.60678 14.7494 9.28775 14.4304 9.28775 14.0369V12.6118C9.28775 12.2182 9.60678 11.8992 10.0003 11.8992Z"
                                  fill="#999999"
                                />
                              </svg>
                            </span>
                            Type of alert</label
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

                    <div class="d-flex justify-content-center mb-70">
                      <button class="btn btn-dark me-20">Cancel</button>
                      <button class="btn btn-primary">Send alert</button>
                    </div>
                  </div>
                </div>
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
  data() {
    return {
      users: [],
      visibleItems: [],
      showAll: false,
      value1: [{ name: "payment 1", code: "1" }],
      options1: [
        { name: "payment 1", code: "1" },
        { name: "payment 2", code: "2" },
        { name: "payment 3", code: "3" },
        { name: "payment 4", code: "4" },
      ],
    };
  },
  methods: {
    loadMore() {
      if (!this.showAll) {
        this.visibleItems = this.users; // Load all elements
        this.showAll = true;
      } else {
        const visibleItemCount = Math.ceil(this.users.length / 2);
        this.visibleItems = this.users.slice(0, visibleItemCount);
        this.showAll = false;
      }
    },
  },
  beforeMount() {
    this.users = [
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
      { name: "John Doe", phone: 12345678 },
    ];

    // Calculate the number of visible items (half of the array length)
    const visibleItemCount = Math.ceil(this.users.length / 2);

    // Slice the array to get the initial visible elements
    this.visibleItems = this.users.slice(0, visibleItemCount);
  },
};
</script>
