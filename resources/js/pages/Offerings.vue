<template>
  <div class="rounded-lg pages-style">
    <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
            class="active"
            id="resident-tab"
            data-bs-toggle="pill"
            data-bs-target="#resident"
            type="button"
            role="tab"
            aria-controls="resident"
            aria-selected="true"
            @click="loadResidentOfferings()"
        >
          Resident Offerings
        </div>
      </li>
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
            id="owner-tab"
            data-bs-toggle="pill"
            data-bs-target="#owner"
            type="button"
            role="tab"
            aria-controls="owner"
            aria-selected="false"
            @click="loadOwnerOfferings()"
        >
          Owner Offerings
        </div>
      </li>
      <li class="nav-item mb-12" role="presentation">
        <div
            id="settings-tab"
            data-bs-toggle="pill"
            data-bs-target="#settings"
            type="button"
            role="tab"
            aria-controls="settings"
            aria-selected="false"
            @click="loadSummary()"
        >
          Settings and Data
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
          class="tab-pane fade show active"
          id="resident"
          role="tabpanel"
          aria-labelledby="resident-tab"
      >
        <residents ref="residents"/>
      </div>
      <div
          class="tab-pane fade"
          id="owner"
          role="tabpanel"
          aria-labelledby="owner-tab"
      >
        <owner ref="owner"/>
      </div>
      <div
          class="tab-pane fade"
          id="settings"
          role="tabpanel"
          aria-labelledby="settings-tab"
      >
        <settings ref="settings"/>
      </div>
    </div>
  </div>
</template>

<script>
import residents from "../components/offerings/residents.vue";
import settings from "../components/offerings/settings.vue";
import owner from "../components/offerings/owner.vue";

export default {
  components: {
    residents,
    settings,
    owner,
  },
  data() {
    return {};
  },
  mounted() {
    this.loadResidentOfferings();
  },
  methods: {
    loadResidentOfferings() {
      this.$refs.residents.loadResidentOfferings();
    },
    loadOwnerOfferings() {
      this.$refs.owner.loadResidentOfferings();
    },
    loadSummary() {
      this.$refs.settings.loadData();
    },
  }
};
</script>
