<template>
  <div class="a-admin">
    <div class="row">
      <div class="col-md-12">
        <p class="text-normal-bold-md mb-12 color-secondary">Info</p>
        <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="info in allLogs"
                :class="{ active: infoIndex === info.alias }"
                @click="selectInfo(info.alias)"
            >{{ info.title }}</span
            >
        </div>
      </div>
    </div>
    <div class="row mb-28">
      <div style="display: none" :class="{ tab_shown: infoIndex === 'acl_logs' }">
        <AclLogs ref="AclLogs"/>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'cron_logs' }">
        <CronLogs ref="CronLogs"></CronLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'extension_logs' }">
        <ExtensionLogs ref="ExtensionLogs"></ExtensionLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'error_logs' }">
        <ErrorLogs ref="ErrorLogs"></ErrorLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'laundrymachines_logs' }">
        <LaundryLogs ref="LaundryLogs"></LaundryLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'loggin_attempts' }">
        <LoginLogs ref="LoginLogs"></LoginLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'payment_logs' }">
        <PaymentLogs ref="PaymentLogs"></PaymentLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'sms_log' }">
        <SmsLogs ref="SmsLogs"></SmsLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'system_logs' }">
        <SystemLogs ref="SystemLogs"></SystemLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'unit_logs' }">
        <UnitLogs ref="UnitLogs"></UnitLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'wallet_history_logs' }">
        <WalletLogs ref="WalletLogs"></WalletLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'workorder_log' }">
        <WorkorderLogs ref="WorkorderLogs"></WorkorderLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'lockers_log' }">
        <LockerLogs ref="LockerLogs"></LockerLogs>
      </div>
      <div style="display: none" :class="{ tab_shown: infoIndex === 'activity_log' }">
        <ActivityLog ref="ActivityLog"></ActivityLog>
      </div>
    </div>
  </div>
</template>

<script>
import AclLogs from "../components/allLogs/acl-logs.vue";
import CronLogs from "../components/allLogs/cron-logs.vue";
import ExtensionLogs from "../components/allLogs/extension-logs.vue";
import LaundryLogs from "../components/allLogs/laundry-logs.vue";
import LoginLogs from "../components/allLogs/login-logs.vue";
import PaymentLogs from "../components/allLogs/payment-logs.vue";
import SmsLogs from "../components/allLogs/sms-logs.vue";
import SystemLogs from "../components/allLogs/system-logs.vue";
import UnitLogs from "../components/allLogs/unit-logs.vue";
import WalletLogs from "../components/allLogs/wallet-logs.vue";
import WorkorderLogs from "../components/allLogs/workorder-logs.vue";
import LockerLogs from "../components/allLogs/locker-logs.vue";
import ErrorLogs from "../components/allLogs/error-logs.vue";
import ActivityLog from "../components/allLogs/activity-logs.vue";

export default {
  name: "Admin",
  components: {
    ErrorLogs,
    LockerLogs,
    WorkorderLogs,
    WalletLogs,
    UnitLogs,
    SystemLogs,
    SmsLogs,
    PaymentLogs,
    LoginLogs,
    LaundryLogs,
    ExtensionLogs,
    CronLogs,
    AclLogs,
    ActivityLog,
  },

  data() {
    return {
      params: {},
      buildingIndex: '',
      infoIndex: "",
      buildings: {},
      currentBuilding: "",
      clearFilters: false,
      allLogs: {},
    };
  },
  created() {
    this.loadAllLogs();
  },
  mounted() {
  },
  methods: {
    loadAllLogs() {
      this.$http
          .post("/allLogs")
          .then((response) => {
            this.allLogs = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    selectInfo(index) {
      this.infoIndex = index;
      if (index === "acl_logs") {
        this.$refs.AclLogs.loadData();
      }
      if (index === "cron_logs") {
        this.$refs.CronLogs.loadData();
      }
      if (index === "extension_logs") {
        this.$refs.ExtensionLogs.loadData();
      }
      if (index === "laundrymachines_logs") {
        this.$refs.LaundryLogs.loadData();
      }
      if (index === "loggin_attempts") {
        this.$refs.LoginLogs.loadData();
      }
      if (index === "payment_logs") {
        this.$refs.PaymentLogs.loadData();
      }
      if (index === "sms_log") {
        this.$refs.SmsLogs.loadData();
      }
      if (index === "system_logs") {
        this.$refs.SystemLogs.loadData();
      }
      if (index === "unit_logs") {
        this.$refs.UnitLogs.loadData();
      }
      if (index === "wallet_history_logs") {
        this.$refs.WalletLogs.loadData();
      }
      if (index === "workorder_log") {
        this.$refs.WorkorderLogs.loadData();
      }
      if (index === "lockers_log") {
        this.$refs.LockerLogs.loadData();
      }
      if (index === "error_logs") {
        this.$refs.ErrorLogs.loadData();
      }
      if (index === "activity_log") {
        this.$refs.ActivityLog.loadData();
      }
    },
  },
};
</script>
