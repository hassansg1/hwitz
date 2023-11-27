<template>
  <div class="rounded-lg pages-style">
    <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
          class="active"
          id="summary-tab"
          data-bs-toggle="pill"
          data-bs-target="#summary"
          type="button"
          role="tab"
          aria-controls="summary"
          aria-selected="true"
        >
          Summary
        </div>
      </li>
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
          id="admin-analytics-tab"
          data-bs-toggle="pill"
          data-bs-target="#admin-analytics"
          type="button"
          role="tab"
          aria-controls="admin-analytics"
          aria-selected="false"
        >
          User Notification Settings
        </div>
      </li>
      <li class="nav-item mb-12" role="presentation">
        <div
          id="user-analytics-tab"
          data-bs-toggle="pill"
          data-bs-target="#user-analytics"
          type="button"
          role="tab"
          aria-controls="user-analytics"
          aria-selected="false"
        >
          System Notification Settings
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
        class="tab-pane fade show active"
        id="summary"
        role="tabpanel"
        aria-labelledby="summary-tab"
      >
        <NotificationSummary />
      </div>
      <div
        class="tab-pane fade"
        id="admin-analytics"
        role="tabpanel"
        aria-labelledby="admin-analytics-tab"
      >
        <UserNotifications />
      </div>
      <div
        class="tab-pane fade"
        id="user-analytics"
        role="tabpanel"
        aria-labelledby="user-analytics-tab"
      >
        <SystemNotifications />
      </div>
    </div>

    <assignNotificationModal />
  </div>
</template>

<script>
import NotificationSummary from "../components/notifications/summary.vue";
import UserNotifications from "../components/notifications/user-notification.vue";
import SystemNotifications from "../components/notifications/system-notification.vue";
import assignNotificationModal from "../components/notifications/assign-notification-modal.vue";

export default {
  components: {
    NotificationSummary,
    UserNotifications,
    SystemNotifications,
    assignNotificationModal,
  },
  data() {
    return {};
  },
};
</script>
