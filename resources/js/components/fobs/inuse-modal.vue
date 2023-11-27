<template>
  <div class="fobs-section">
    <div
      class="modal fade"
      id="fobsModal"
      tabindex="-1"
      aria-labelledby="fobsModal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
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
              <div class="h3 mb-13">
                Assign user to FOB
                <span>10-216</span>
              </div>
              <p class="text-normal color-secondary mb-72">
                If you selected a mobile FOB the user will receive an invitation
                email.
              </p>

              <div class="w-75 mx-auto mb-72">
                <div class="has-search align-self-center mb-24">
                  <span class="form-control-feedback">
                    <img src="/images/icons/search.png" alt="" />
                  </span>
                  <input
                    type="text"
                    class="search form-control"
                    placeholder="Search"
                  />
                </div>
              </div>

              <div class="row gy-3 mb-20">
                <div class="col-lg-4 col-md-6" v-for="user in visibleItems">
                  <div class="d-flex">
                    <div class="form-check me-24">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        checked
                      />
                    </div>
                    <div class="d-flex">
                      <div class="avatar-sm me-8">
                        <img
                          src="/images/test-avatar.png"
                          class="avatar-img"
                          alt="user"
                        />
                      </div>
                      <div class="align-self-center">
                        <p class="text-medium-bold mb-6">{{ user.name }}</p>
                        <p class="text-normal color-primary">
                          {{ user.phone }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-37">
                <a class="a-link" @click="loadMore">{{
                  !showAll ? "View More" : "View less"
                }}</a>
              </div>

              <div class="d-flex justify-content-end">
                <button class="btn btn-primary">Assign FOB</button>
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
