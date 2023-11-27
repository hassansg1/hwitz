<template>
  <div class="rounded-lg pages-style">
    <ul
        class="nav nav-pills d-flex mb-64 bg-white"
        id="pills-tab"
        role="tablist"
    >
      <li
          class="nav-item mb-12 me-49"
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
            @click="toggleBtn(index)"
        >
          {{ tab.tab }}
        </div>
      </li>
      <li
          class="align-self-center mt-m10"
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
      </li>
    </ul>

    <div class="tab-content bg-white" id="pills-tabContent">
      <div
          class="tab-pane fade show active"
          id="all"
          role="tabpanel"
          aria-labelledby="all-tab"
      >
        <Task ref="tasks"/>
      </div>
      <div
          class="tab-pane fade"
          id="work-orders"
          role="tabpanel"
          aria-labelledby="work-orders-tab"
      >
        <WorkOrder ref="workOrder"/>
      </div>
      <div
          class="tab-pane fade"
          id="work-order-archive"
          role="tabpanel"
          aria-labelledby="work-order-archive-tab"
      >
        <WorkOrderArchive ref="workOrderArchive"/>
      </div>
    </div>

    <TaskModal/>
    <SendWorkOrderModal ref="sendWorkOrderModal"/>
  </div>
</template>

<script>
import Task from "../components/Tasks/task.vue";
import WorkOrder from "../components/workorder/work-order.vue";
import TaskModal from "../components/task.modal.vue";
import WorkOrderArchive from "../components/workorder/work-order-archive.vue";
import SendWorkOrderModal from "../components/workorder/send-work-order-modal.vue";

export default {
  components: {
    Task,
    WorkOrder,
    TaskModal,
    WorkOrderArchive,
    SendWorkOrderModal
  },
  created() {
    this.fetchBuildings();
    if (this.$gate.permissions.includes('tasks'))
      this.tabs.push({
        id: 1,
        tab: "Tasks",
        tabId: "all",
      });
    if (this.$gate.permissions.includes('work_orders')) this.tabs.push({
      id: 2,
      tab: "Work Orders",
      tabId: "work-orders",
    });
    if (this.$gate.permissions.includes('work_order_archive')) this.tabs.push({
      id: 3,
      tab: "Work Order Archive",
      tabId: "work-order-archive",
    });
  },
  data() {
    return {
      tabs: [],
      newWorkorder: false,
      workOrders: [],
      archivedWorkOrderTab: false,
      buildings: []
    };
  },
  methods: {
    openSendWorkOrderPopup() {
      // this.$refs.sendWorkOrderModal.getBuildings();
      // this.$refs.sendWorkOrderModal.getWatcherAndMaintainerList();
      this.$refs.sendWorkOrderModal.getIssueTypes();
      this.$refs.sendWorkOrderModal.fetchWorkTypes();
      this.$refs.sendWorkOrderModal.form.reset();
      $("#sendWorkOrder").modal("show");
    },
    toggleBtn(index) {
      if (index === 1) {
        this.newWorkorder = true;
      } else if (index === 2) {
        this.newWorkorder = true;
        this.archivedWorkOrderTab = true;
        this.$nextTick(() => {
          this.$refs.workOrderArchive.loadData();
        });
      } else {
        this.newWorkorder = false;
      }
    },
    fetchBuildings() {
      this.showLoader();
      this.$http
          .get("/buildings")
          .then((response) => {
            response = response.data.data;
            this.buildings = response;
            this.$refs.tasks.buildings = response;
            this.$refs.workOrderArchive.buildings = this.buildings;
            this.$refs.workOrder.buildings = this.buildings;
            this.$refs.sendWorkOrderModal.buildings = this.buildings;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
