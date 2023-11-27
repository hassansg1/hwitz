<template>
  <div class="a-admin">
    <div style="margin-top: 30px">
      <div class="row">
        <div class="col-lg-6">
          <p class="text-normal-bold-md mb-12 color-secondary">Buildings</p>
          <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="(building, index) in buildings"
                :class="{ active: buildingIndex === building.id }"
                @click="selectBuilding(building.id)"
            >{{ building.name }}</span
            >
          </div>
        </div>
      </div>
      <div v-if="this.$parent.selectedAnalyticsTab === 'admin'" class="row">
        <div class="col-md-12">
          <p class="text-normal-bold-md mb-12 color-secondary">Info</p>
          <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="(info, index) in information"
                :class="{ active: infoIndex === index }"
                @click="selectInfo(index)"
            >{{ info }}</span
            >
          </div>
        </div>
      </div>
      <div v-if="this.$parent.selectedAnalyticsTab === 'user'" class="row">
        <div class="col-md-12">
          <p class="text-normal-bold-md mb-12 color-secondary">Info</p>
          <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="(info, index) in userInformation"
                :class="{ active: infoIndex === index }"
                @click="selectInfo(index)"
            >{{ info }}</span
            >
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-28">
      <div style="display: none" :class="{ tab_shown: infoIndex === 'unit' }">
        <UnitAnalytics ref="unitAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'fob' }">
        <FobAnalytics ref="fobAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'user' }">
        <UserAnalytics ref="userAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'entry_controller' }"
      >
        <EntryControllerAnalytics ref="entryControllerAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'wallet' }">
        <WalletAnalytics ref="walletAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'appliance_state' }"
      >
        <ApplianceStateAnalytics ref="applianceStateAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'device' }">
        <DeviceAnalytics ref="deviceAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'task' }">
        <TaskAnalytics ref="taskAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'package' }"
      >
        <PackageAnalytics ref="packageAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'unique_devices' }"
      >
        <UniqueDevicesAnalytics ref="UniqueDevicesAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'unit_vacancy' }"
      >
        <UnitVacancyAnalytics ref="UnitVacancyAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'income' }">
        <IncomeAnalytics ref="IncomeAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'fob_hid' }"
      >
        <FobHidAnalytics ref="FobHidAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'fob_n' }">
        <FobNAnalytics ref="FobNAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'laundry' }"
      >
        <LaundryAnalytics ref="LaundryAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'tilt_impact' }"
      >
        <TiltImpactAnalytics ref="TiltImpactAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'intercom' }"
      >
        <IntercomAnalytics ref="IntercomAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'phone' }">
        <PhoneAnalytics ref="PhoneAnalytics"/>
      </div>

      <div style="display: none" :class="{ tab_shown: infoIndex === 'data' }">
        <DataAnalytics ref="DataAnalytics"/>
      </div>

      <div
          style="display: none"
          :class="{ tab_shown: infoIndex === 'sms_log' }"
      >
        <SmsLogAnalytics ref="SmsLogAnalytics"/>
      </div>
    </div>
  </div>
</template>

<script>
import {ref} from "vue";
import UnitAnalytics from "../../components/analytics/admin/unit-analytics.vue";
import UserAnalytics from "../../components/analytics/admin/user-analytics.vue";
import EntryControllerAnalytics from "../../components/analytics/admin/entry-controller-analytics.vue";
import WalletAnalytics from "../../components/analytics/admin/wallet-analytics.vue";
import ApplianceStateAnalytics from "../../components/analytics/admin/appliance-state-analytics.vue";
import DeviceAnalytics from "../../components/analytics/admin/device-analytics.vue";
import TaskAnalytics from "../../components/analytics/admin/task-analytics.vue";
import FobAnalytics from "../../components/analytics/admin/fob-analytics.vue";
import PackageAnalytics from "../../components/analytics/user/package-analytics.vue";
import UniqueDevicesAnalytics from "./user/unique-devices-analytics.vue";
import UnitVacancyAnalytics from "./user/unit-vacancy-analytics.vue";
import IncomeAnalytics from "./user/income-analytics.vue";
import FobHidAnalytics from "./user/fob-hid-analytics.vue";
import FobNAnalytics from "./user/fob-n-analytics.vue";
import LaundryAnalytics from "./user/laundry-analytics.vue";
import TiltImpactAnalytics from "./user/tilt-impact-analytics.vue";
import IntercomAnalytics from "./user/intercom-analytics.vue";
import PhoneAnalytics from "./user/phone-analytics.vue";
import DataAnalytics from "./user/data-analytics.vue";
import SmsLogAnalytics from "./user/sms-log-analytics.vue";

const unitAnalytics = ref(0);
export default {
  name: "Admin",
  components: {
    SmsLogAnalytics,
    DataAnalytics,
    PhoneAnalytics,
    IntercomAnalytics,
    TiltImpactAnalytics,
    LaundryAnalytics,
    FobNAnalytics,
    FobHidAnalytics,
    IncomeAnalytics,
    UnitVacancyAnalytics,
    UniqueDevicesAnalytics,
    UnitAnalytics,
    UserAnalytics,
    EntryControllerAnalytics,
    WalletAnalytics,
    ApplianceStateAnalytics,
    DeviceAnalytics,
    TaskAnalytics,
    FobAnalytics,
    PackageAnalytics,
  },

  data() {
    return {
      params: {},
      buildingIndex: '',
      infoIndex: "",
      buildings: {},
      currentBuilding: "",
      information: {
        unit: "Unit",
        fob: "Fobs",
        user: "User",
        entry_controller: "Entry Cont.",
        wallet: "Wallet",
        appliance_state: "Appliance State",
        device: "Device",
        task: "Tasks",
      },
      userInformation: {
        package: "Packages",
        unique_devices: "No Unique Devices",
        unit_vacancy: "Unit Vacancy",
        income: "Income",
        fob_hid: "Fobs HID",
        fob_n: "Fobs 2N",
        laundry: "Laundry",
        tilt_impact: "Tilt / Impact Alerts",
        intercom: "Intercom",
        phone: "Phones",
        data: "Data",
        sms_log: "SMS Log",
      },
      clearFilters: false,
    };
  },
  created() {
    this.getBuildingsForUser();
  },
  mounted() {
  },
  methods: {
    selectBuilding(index) {
      this.buildingIndex = index;
      this.loadBuildingDetails();
      this.$refs.deviceAnalytics.loadFiltersForDevices();
      this.clearFilters = true;
      this.selectInfo(this.infoIndex);
      this.clearFilters = false;
    },
    selectInfo(index) {
      if(!this.buildingIndex){
        Toast.fire({ icon: 'warning', title: 'No building selected. Please select building first and try again.' });
        return;
      }
      if (index !== this.infoIndex) {
        this.clearFilters = true;
      }
      this.infoIndex = index;
      if (index === "unit") {
        this.$refs.unitAnalytics.loadData();
      }
      this.infoIndex = index;
      if (index === "fob") {
      }
      if (index === "user") {
        this.$refs.userAnalytics.loadData();
      }
      if (index === "entry_controller") {
        this.$refs.entryControllerAnalytics.loadData();
      }
      if (index === "wallet") {
        this.$refs.walletAnalytics.loadData();
      }
      if (index === "appliance_state") {
        this.$refs.applianceStateAnalytics.loadData();
      }
      if (index === "device") {
        this.$refs.deviceAnalytics.loadData();
      }
      if (index === "task") {
        this.$refs.taskAnalytics.loadData();
      }
      if (index === "package") {
        this.$refs.packageAnalytics.loadData();
      }
      if (index === "unique_devices") {
        this.$refs.UniqueDevicesAnalytics.loadData();
      }
      if (index === "unit_vacancy") {
        this.$refs.UnitVacancyAnalytics.loadData();
      }
      if (index === "income") {
        this.$refs.IncomeAnalytics.loadData();
      }
      if (index === "fob_hid") {
        this.$refs.FobHidAnalytics.loadData();
      }
      if (index === "fob_n") {
        this.$refs.FobNAnalytics.loadData();
      }
      if (index === "laundry") {
        this.$refs.LaundryAnalytics.loadData();
      }
      if (index === "tilt_impact") {
        this.$refs.TiltImpactAnalytics.loadData();
      }
      if (index === "intercom") {
        this.$refs.IntercomAnalytics.loadData();
      }
      if (index === "phone") {
        this.$refs.PhoneAnalytics.loadData();
      }
      if (index === "data") {
        this.$refs.DataAnalytics.loadData();
      }
      if (index === "sms_log") {
        this.$refs.SmsLogAnalytics.loadData();
      }
      this.clearFilters = false;
    },
    selectEntries(index) {
      this.entriesPerPageSelected = index;
      this.selectInfo(this.infoIndex);
    },
    getBuildingsForUser() {
      this.showLoader();
      this.$http
          .get("/getBuildingsForUser")
          .then((response) => {
            this.buildings = response.data.buildings;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadBuildingDetails() {
      let buildingId = this.buildingIndex;
      this.$http
          .get("/loadBuildingDetails" + "/" + buildingId)
          .then((response) => {
            this.currentBuilding = response.data.building;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    getEntriesPerPage() {
      this.showLoader();
      this.$http
          .get("/getEntriesPerPage")
          .then((response) => {
            this.entriesPerPageSelected = response.data.enrtiesPerPage;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
