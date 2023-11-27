<template>
  <div class="pages-style">
    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
      <li class="nav-item me-28 mb-12" role="presentation">
        <div
          class="active"
          id="pills-home-tab"
          data-bs-toggle="pill"
          data-bs-target="#pills-home"
          type="button"
          role="tab"
          aria-controls="pills-home"
          aria-selected="true"
        >
          Unit
        </div>
      </li>
      <li class="nav-item mb-12" role="presentation">
        <div
          class=""
          id="pills-profile-tab"
          data-bs-toggle="pill"
          data-bs-target="#pills-profile"
          type="button"
          role="tab"
          aria-controls="pills-profile"
          aria-selected="false"
        >
          Settings
        </div>
      </li>
    </ul>
    <div class="tab-content bg-white p-0 border-bottom-0" id="pills-tabContent">
      <div
        class="tab-pane fade show active"
        id="pills-home"
        role="tabpanel"
        aria-labelledby="pills-home-tab"
      >
        <Unit />
      </div>
      <div
        class="tab-pane fade"
        id="pills-profile"
        role="tabpanel"
        aria-labelledby="pills-profile-tab"
      >
        <Settings />
      </div>
    </div>
  </div>
</template>

<script>
import Unit from "../components/non-performance/unit.vue";
import Settings from "../components/non-performance/settings.vue";

export default {
  components: {
    Unit,
    Settings,
  },
  data() {
    return {};
  },
};
</script>
