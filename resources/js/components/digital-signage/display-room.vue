<template>
  <div class="digital-section">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
        >Select all</a
      >
      <a class="align-self-center a-link mb-16 mb-lg-0" href="">View all</a>
    </div>
    <div class="signage-room-card mb-37">
      <div class="mb-50">
        <div class="d-flex">
          <div class="me-28 align-self-center">
            <h3 class="h3 mb-0">Gym</h3>
          </div>
          <div class="align-self-center">
            <button
              class="btn bkg-gray text-white"
              data-bs-toggle="modal"
              data-bs-target="#editDisplayModal"
            >
              Edit dispalys
            </button>
          </div>
        </div>
      </div>
      <div class="row gy-3 mb-28">
        <div class="col-xl-3 col-lg-4 col-md-6" v-for="gym in visibleGym">
          <div class="card-advertisement">
            <div class="position-relative">
              <div class="top-image">
                <img src="/images/test-bg.jpg" class="camera-img" alt="" />
              </div>
            </div>

            <div class="card-body">
              <div
                class="d-flex justify-content-between flex-row flex-wrap mb-24"
              >
                <div>
                  <h3 class="h3">Promotion 1</h3>
                </div>
                <div class="d-flex flex-row flex-wrap">
                  <div
                    class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                  >
                    {{ gym.name }}
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <div>
                  <button
                    class="btn btn-dark"
                    data-bs-toggle="modal"
                    data-bs-target="#selectDisplayModal"
                  >
                    Edit
                  </button>
                </div>

                <div class="align-self-end">
                  <div class="form-check form-switch custom-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" checked role="switch" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <p class="text-end text-normal color-secondary mb-0">
                    Active
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <a class="a-link" @click="loadMoreGym">{{
          !showAllGym ? "View More" : "View less"
        }}</a>
      </div>
    </div>

    <div class="signage-room-card mb-37">
      <div class="mb-50">
        <div class="d-flex">
          <div class="me-28 align-self-center">
            <h3 class="h3 mb-0">Lobby</h3>
          </div>
          <div class="align-self-center">
            <button class="btn bkg-gray text-white">Edit dispalys</button>
          </div>
        </div>
      </div>
      <div class="row gy-3 mb-28">
        <div class="col-xl-3 col-lg-4 col-md-6" v-for="lobby in visibleLobby">
          <div class="card-advertisement">
            <div class="position-relative">
              <div class="top-image">
                <img src="/images/test-bg.jpg" class="camera-img" alt="" />
              </div>
            </div>

            <div class="card-body">
              <div
                class="d-flex justify-content-between flex-row flex-wrap mb-24"
              >
                <div>
                  <h3 class="h3">Promotion 1</h3>
                </div>
                <div class="d-flex flex-row flex-wrap">
                  <div
                    class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                  >
                    {{ lobby.name }}
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <div>
                  <button class="btn btn-dark">Edit</button>
                </div>

                <div class="align-self-end">
                  <div class="form-check form-switch custom-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" checked role="switch" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <p class="text-end text-normal color-secondary mb-0">
                    active
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <a class="a-link" @click="loadMoreLobby">{{
          !showAllLobby ? "View More" : "View less"
        }}</a>
      </div>
    </div>

    <div class="signage-room-card mb-37">
      <div class="mb-50">
        <div class="d-flex">
          <div class="me-28 align-self-center">
            <h3 class="h3 mb-0">Hallway</h3>
          </div>
          <div class="align-self-center">
            <button class="btn bkg-gray text-white">Edit dispalys</button>
          </div>
        </div>
      </div>
      <div class="row gy-3 mb-28">
        <div
          class="col-xl-3 col-lg-4 col-md-6"
          v-for="hallway in visibleHallway"
        >
          <div class="card-advertisement">
            <div class="position-relative">
              <div class="top-image">
                <img src="/images/test-bg.jpg" class="camera-img" alt="" />
              </div>
            </div>

            <div class="card-body">
              <div
                class="d-flex justify-content-between flex-row flex-wrap mb-24"
              >
                <div>
                  <h3 class="h3">Promotion 1</h3>
                </div>
                <div class="d-flex flex-row flex-wrap">
                  <div
                    class="badge-room text-normal text-normal-bold-md mb-12 me-12"
                  >
                    {{ hallway.name }}
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <div>
                  <button class="btn btn-dark">Edit</button>
                </div>

                <div class="align-self-end">
                  <div class="form-check form-switch custom-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" checked role="switch" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <p class="text-end text-normal color-secondary mb-0">
                    active
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <a class="a-link" @click="loadMoreHallway">{{
          !showAllHallway ? "View More" : "View less"
        }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      gym: [],
      visibleGym: [],
      showAllGym: false,

      lobby: [],
      visibleLobby: [],
      showAllLobby: false,

      hallway: [],
      visibleHallway: [],
      showAllHallway: false,
    };
  },
  methods: {
    loadMoreGym() {
      if (!this.showAllGym) {
        this.visibleGym = this.gym; // Load all elements
        this.showAllGym = true;
      } else {
        const visibleGymCount = Math.ceil(this.gym.length / 2);
        this.visibleGym = this.gym.slice(0, visibleGymCount);
        this.showAllGym = false;
      }
    },

    loadMoreLobby() {
      if (!this.showAllLobby) {
        this.visibleLobby = this.lobby; // Load all elements
        this.showAllLobby = true;
      } else {
        const visibleLobbyCount = Math.ceil(this.lobby.length / 2);
        this.visibleLobby = this.lobby.slice(0, visibleLobbyCount);
        this.showAllLobby = false;
      }
    },

    loadMoreHallway() {
      if (!this.showAllHallway) {
        this.visibleHallway = this.hallway; // Load all elements
        this.showAllHallway = true;
      } else {
        const visibleHallwayCount = Math.ceil(this.hallway.length / 2);
        this.visibleHallway = this.hallway.slice(0, visibleHallwayCount);
        this.showAllHallway = false;
      }
    },
  },
  beforeMount() {
    this.gym = [
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
      { name: "gym" },
    ];

    this.hallway = [
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
      { name: "hallway" },
    ];

    this.lobby = [
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
      { name: "lobby" },
    ];

    // Calculate the number of visible items (half of the array length)
    const visibleGymCount = Math.ceil(this.gym.length / 2);

    // Slice the array to get the initial visible elements
    this.visibleGym = this.gym.slice(0, visibleGymCount);

    const visibleHallwayCount = Math.ceil(this.hallway.length / 2);
    this.visibleHallway = this.hallway.slice(0, visibleHallwayCount);

    const visibleLobbyCount = Math.ceil(this.lobby.length / 2);
    this.visibleLobby = this.lobby.slice(0, visibleLobbyCount);
  },
};
</script>
