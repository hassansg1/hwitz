<template>
  <div class="rounded-lg pages-style">
    <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
<!--      <li class="nav-item me-56 mb-12" role="presentation">-->
<!--        <div-->
<!--            id="summary-tab"-->
<!--            data-bs-toggle="pill"-->
<!--            data-bs-target="#summary"-->
<!--            type="button"-->
<!--            role="tab"-->
<!--            aria-controls="summary"-->
<!--            aria-selected="true"-->
<!--            @click="selectAnalyticsTab('admin')"-->
<!--        >-->
<!--          Summary-->
<!--        </div>-->
<!--      </li>-->
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
            class="active"
            id="admin-analytics-tab"
            data-bs-toggle="pill"
            data-bs-target="#admin-analytics"
            type="button"
            role="tab"
            aria-controls="admin-analytics"
            aria-selected="false"
            @click="selectAnalyticsTab('admin')"
        >
          Admin Analytics
        </div>
      </li>
      <li class="nav-item mb-12" role="presentation">
        <div
            id="admin-analytics-tab"
            data-bs-toggle="pill"
            data-bs-target="#admin-analytics"
            type="button"
            role="tab"
            aria-controls="admin-analytics"
            aria-selected="false"
            @click="selectAnalyticsTab('user')"
        >
          User Analytics
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
          class="tab-pane fade"
          id="summary"
          role="tabpanel"
          aria-labelledby="summary-tab"
      >
<!--        <AnalyticsSummary/>-->
      </div>
      <div
          class="tab-pane fade show active"
          id="admin-analytics"
          role="tabpanel"
          aria-labelledby="admin-analytics-tab"
      >
        <AdminAnalytics ref="AdminAnalytics"/>
      </div>
    </div>
  </div>
</template>

<script>
import AnalyticsSummary from "../components/analytics/summary.vue";
import AdminAnalytics from "../components/analytics/admin-analytics.vue";
import adminAnalytics from "../components/analytics/admin-analytics.vue";

export default {
  components: {
    AnalyticsSummary,
    AdminAnalytics,
  },
  data() {
    return {
      selectedAnalyticsTab: "admin",
    };
  },
  methods: {
    selectAnalyticsTab(tab) {
      this.$refs.AdminAnalytics.infoIndex = "";
      this.selectedAnalyticsTab = tab;
    },
  },
};
</script>
