<template>
  <div class="rounded-lg pages-style">
    <ul
        class="nav nav-pills d-flex mb-64 bg-white"
        id="pills-tab"
        role="tablist"
    >
      <li
          class="nav-item me-49 mb-12"
          role="presentation"
          v-for="(tab, index) of tabs"
          :key="tab.id"
      >
        <div
            :class="{ active: index === 0 }"
            :id="tab.tabId + '-tab'"
            data-bs-toggle="pill"
            :data-bs-target="'#' + tab.tabId"
            type="button"
            role="tab"
            :aria-controls="tab.tabId"
            aria-selected="true"
            @click="toggleBtn(tab.tabId)"
        >
          {{ tab.tab }}
        </div>
      </li>
      <!-- <li
          class="align-self-center"
          style="margin-top: -18px"
          v-if="newWorkorder"
      >
        <div>
          <button
              class="btn bkg-success px-32 py-6"
              @click="openSendWorkOrderPopup"
          >
            New work order
          </button>
        </div>
      </li> -->
    </ul>
    <div class="tab-content bg-white" id="pills-tabContent">
      <div v-if="$gate.permissions.includes('direct_messages')"
          class="tab-pane fade"
          id="direct-messages"
          role="tabpanel"
          aria-labelledby="direct-messages-tab"
      >
        <DirectMessages/>
      </div>
      <div v-if="$gate.permissions.includes('work_orders')"
          class="tab-pane fade"
          id="work-orders"
          role="tabpanel"
          aria-labelledby="work-orders-tab"
      >
        <WorkOrder ref="workOrder" v-if="newWorkorder"/>
      </div>
      <div v-if="$gate.permissions.includes('broadcasts')"
          class="tab-pane fade"
          id="broadcasts"
          role="tabpanel"
          aria-labelledby="broadcasts-tab"
      >
        <Broadcast ref="broadcast"/>
      </div>

      <div v-if="$gate.permissions.includes('verifications')"
          class="tab-pane fade"
          id="varifications"
          role="tabpanel"
          aria-labelledby="varifications-tab"
      >
        <Verifications ref="verifications"/>
      </div>

      <div v-if="$gate.permissions.includes('work_order_archive')"
          class="tab-pane fade"
          id="work-order-archive"
          role="tabpanel"
          aria-labelledby="work-order-archive-tab"
      >
        <WorkOrderArchive ref="workOrderArchive" v-if="archivedWorkOrderTab"/>
      </div>
    </div>

    <SendWorkOrderModal ref="sendWorkOrderModal"/>
  </div>
</template>

<script>
import DirectMessages from "../components/messages/message/direct-messages.vue";
import WorkOrder from "../components/workorder/work-order.vue";
import WorkOrderArchive from "../components/workorder/work-order-archive.vue";
import Broadcast from "../components/messages/broadcast/broadcast.vue";
import Vrifications from "../components/messages/verification/verifications.vue";
import Verifications from "../components/messages/verification/verifications.vue";
import SendWorkOrderModal from "../components/workorder/send-work-order-modal.vue";

export default {
  components: {
    DirectMessages,
    WorkOrder,
    WorkOrderArchive,
    Broadcast,
    Vrifications,
    Verifications,
    SendWorkOrderModal,
  },
  data() {
    return {
      tabs: [],
      newWorkorder: false,
      archivedWorkOrderTab: false,
    };
  },
  created() {
    if (this.$gate.permissions.includes('direct_messages'))
      this.tabs.push({
        id: 1,
        tab: "Direct Messages",
        tabId: "direct-messages",
      });
    // if (this.$gate.permissions.includes('work_orders')) this.tabs.push({
    //   id: 2,
    //   tab: "Work Orders",
    //   tabId: "work-orders",
    // });
    if (this.$gate.permissions.includes('broadcasts')) this.tabs.push({
      id: 3,
      tab: "Broadcasts",
      tabId: "broadcasts",
    });
    if (this.$gate.permissions.includes('verifications')) this.tabs.push({
      id: 4,
      tab: "Verifications",
      tabId: "varifications",
    });
    // if (this.$gate.permissions.includes('work_order_archive')) this.tabs.push({
    //   id: 5,
    //   tab: "Work Order Archive",
    //   tabId: "work-order-archive",
    // });
  },
  mounted() {
    let firstTab = this.tabs[0] ?? null;
    if (firstTab) {
      // $('#' + firstTab.tabId).click("show");
      $('#' + firstTab.tabId).addClass("active");
      $('#' + firstTab.tabId).addClass("show");
      this.toggleBtn(firstTab.tabId);
    }
  },
  methods: {
    toggleBtn(index) {
      this.newWorkorder = false;
      this.archivedWorkOrderTab = false;
      if (index === "work-orders") {
        this.newWorkorder = true;
        this.$nextTick(() => {
          this.$refs.workOrder.getBuildings();
          this.$refs.workOrder.loadData();
        });
      } else if (index === "broadcasts") { //broadcast
        this.$refs.broadcast.getBuildings();
        this.$refs.broadcast.loadData();
      } else if (index === "varifications") { //verifications
        this.$refs.verifications.getBuildings();
      } else if (index === "work-order-archive") { //archived work orders
        this.newWorkorder = true;
        this.archivedWorkOrderTab = true;
        this.$nextTick(() => {
          this.$refs.workOrderArchive.loadData();
        });
      } else {
        this.newWorkorder = false;
      }
    },
    openSendWorkOrderPopup() {
      this.$refs.sendWorkOrderModal.getBuildings();
      // this.$refs.sendWorkOrderModal.getWatcherAndMaintainerList();
      this.$refs.sendWorkOrderModal.getIssueTypes();
      this.$refs.sendWorkOrderModal.fetchWorkTypes();
      this.$refs.sendWorkOrderModal.form.reset();
      $("#sendWorkOrder").modal("show");
    },
  },
};
</script>
