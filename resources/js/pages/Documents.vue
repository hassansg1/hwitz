<template>
  <div class="rounded-lg">
    <ul
      class="nav nav-pills d-flex mb-36 bg-white"
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
          @click="toggleBtn(index)"
        >
          {{ tab.tab }}
        </div>
      </li>
      <li class="li-btn" v-if="activeTab === 0">
        <div>
          <button class="btn bkg-success px-32 py-6">+ Add New Packet</button>
        </div>
      </li>
      <li class="li-btn" v-if="activeTab === 1">
        <div>
          <button class="btn bkg-success px-32 py-6">+ Add New Document</button>
        </div>
      </li>
    </ul>

    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
        class="tab-pane fade show active"
        id="packets"
        role="tabpanel"
        aria-labelledby="packets-tab"
      >
        <packets />
      </div>
      <div
        class="tab-pane fade"
        id="templates"
        role="tabpanel"
        aria-labelledby="templates-tab"
      >
        <templates />
      </div>

      <div
        class="tab-pane fade"
        id="archives"
        role="tabpanel"
        aria-labelledby="archives-tab"
      >
        <archives />
      </div>
    </div>
    <sendPacketModal />
    <editPacketsModal />
    <editTemplateModalVue />
    <amendTemplateModalVue />
    <versionModalVue />
    <archiveModal />
  </div>
</template>

<script>
import packets from "../components/documents/packets/packets.vue";
import archives from "../components/documents/archives/archives.vue";
import templates from "../components/documents/templates/templates.vue";
import sendPacketModal from "../components/documents/packets/send-packet-modal.vue";
import editPacketsModal from "../components/documents/packets/edit-packets-modal.vue";
import editTemplateModalVue from "../components/documents/templates/edit-template-modal.vue";
import amendTemplateModalVue from "../components/documents/templates/amend-template-modal.vue";
import versionModalVue from "../components/documents/templates/version-modal.vue";
import archiveModal from "../components/documents/archives/archive-modal.vue";

export default {
  components: {
    packets,
    archives,
    templates,
    sendPacketModal,
    editPacketsModal,
    editTemplateModalVue,
    amendTemplateModalVue,
    versionModalVue,
    archiveModal,
  },
  data() {
    return {
      tabs: [
        {
          id: 1,
          tab: "Packets",
          tabId: "packets",
        },

        {
          id: 2,
          tab: "Templates",
          tabId: "templates",
        },

        {
          id: 3,
          tab: "Archives",
          tabId: "archives",
        },
      ],
      activeTab: 0,
    };
  },
  methods: {
    toggleBtn(index) {
      this.activeTab = index;
    },
  },
};
</script>

<style scoped>
.nav-pills .active {
  padding-bottom: 5px;
  border-bottom: 5px solid #47c5fe !important;
  background-color: transparent;
  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  line-height: 20px;
  color: #262626;
}

.nav-pills {
  padding-bottom: 5px;
  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  line-height: 20px;
  color: rgba(38, 38, 38, 0.4) !important;
  margin-right: 49px !important;
}

.tab-content {
  padding: 0px;
}

.btn:hover {
  color: #262626;
}
</style>
