<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Fobs HID</h3>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-lg-12">
        <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="(title, index) in top_filters"
                :class="{ active: topFilterRadio === index }"
                v-on:click="topFilterChange(index)"
            >{{ title }}</span
            >
        </div>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <select v-model="unit_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Unit (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.units" :value="optionItem.id">
            {{ optionItem.unit_no }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="asset_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="all" selected>Select Entrance (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.assets" :value="optionItem.id">
            {{ optionItem.name }}
          </option>
          <option value="-1">---------------------------</option>
          <option value="grant">Intercom manual grants</option>
          <option value="alarms">Only show alarms</option>
          <option value="uk-user-fobs">Show unknown residents/FOBS</option>
        </select>
      </div>

      <div class="col-md-3">
        <div class="text-medium color-secondary me-12">Show Results With Images</div>
        <div class="form-check form-switch p-0">
          <label class="switch mb-0">
            <input type="checkbox" id="images_only" v-model="images_only" role="switch"
                   @change="loadData()"/>
            <span class="slider round"></span>
          </label>
        </div>
      </div>

      <div class="col-md-3">
        <div class="text-medium color-secondary me-12">Remove ALARM Records</div>
        <div class="form-check form-switch p-0">
          <label class="switch mb-0">
            <input type="checkbox" id="no_alarm" v-model="no_alarm" role="switch"
                   @change="loadData()"/>
            <span class="slider round"></span>
          </label>
        </div>
      </div>
    </div>
    <div class="mb-66 fobs_images" v-if="topFilterRadio === 'image_entrance'">
      <div class="table-responsive mb-28">
        <table>
          <tr class="border_bottom">
            <td>
              <ul>
                <li v-for="data in imagesArray.data">
                  <ImageTag :data="data"></ImageTag>
                  <br>
                  <em>2023-09-25 00:18:46</em>
                </li>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination="imagesArray"></pagination>
      </span>
    </div>
    <div class="mb-66" v-else>
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Date
              <Sorting :sortColumn="'timestamp'"/>
            <th>User
              <Sorting :sortColumn="'user_id'"/>
            <th>FOB
              <Sorting :sortColumn="'token_id'"/>
            <th>Unit
              <Sorting :sortColumn="'unit_id'"/>
            <th>Entrance
              <Sorting :sortColumn="'asset_id'"/>
            <th>Type
              <Sorting :sortColumn="'event_type'"/>
            <th>Image</th>
            <th>Video</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ formatDateTime(data.timestamp) }}</span>
            </td>
            <td>
              <span v-if="data.user" class="text-medium">{{ data.user.name }}</span>
            </td>
            <td>
              <span v-if="data.token" class="text-medium">{{ data.token.card_id }}</span>
            </td>
            <td>
              <span v-if="data.unit" class="text-medium">{{ data.unit.unit_no }}</span>
            </td>
            <td>
              <span v-if="data.asset" class="text-medium">{{ data.asset.name }}</span>
            </td>
            <td>
              <span v-if="data.event_type" class="text-medium">{{ getAssetEventTypes(data.event_type) }}</span>
            </td>
            <ImageRow :data="data"></ImageRow>
            <VideoRow :data="data"></VideoRow>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
    <FiveImagesModal :type="'fobsHid'" ref="FiveImagesModal"></FiveImagesModal>
    <EntryVideoModal :type="'fobsHid'" ref="EntryVideoModal"></EntryVideoModal>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";

import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import FiveImagesModal from "../five-images-modal.vue";
import EntryVideoModal from "../entry-video-modal.vue";
import ImageRow from "../image-row.vue";
import ImageTag from "../image-tag.vue";
import VideoRow from "../video-row.vue";

export default {
  components: {
    Sorting,
    EntriesPerPage,
    BuildingDateRange,
    FiveImagesModal,
    EntryVideoModal,
    ImageRow,
    VideoRow,
    ImageTag
  },
  created() {
  },
  data() {
    return {
      top_filters: {
        "unit_entrance": "Unit / Entrance",
        "fobs_entrance": "FOBs / Entrance",
        "image_entrance": "Images / Entrance",
        "mail_code_usage": "Mail Code Usage",
        "unreadable": "Unreadable",
        "manual_grant": "Manual Grant",
      },
      topFilterRadio: null,
      images_only: false,
      no_alarm: false,
      imagesArray: {},
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      asset_id: '',
      unit_id: '',
      entryFilter: 'all',
    };
  },
  expose: ['loadData'],
  methods: {
    getAssetEventTypes(num) {
      let events = {
        '1022': 'Invalid',
        '2020': 'FOB Grant',
        '2021': 'FOB Grant Extended',
        '2024': 'No Schedule',
        '4034': 'Alarm Ack',
        '4041': 'Door Forced',
        '4042': 'Door Held',
        '4043': 'Tamper',
        '4044': 'Power Fail',
        '4045': 'Unit Intercom',
        '2043': 'Unassigned',
        '12031': 'Manual Grant',
        '12032': 'Unlock',
        '12033': 'Lock'
      };

      event = events[num] ?? '';
      return event;
    },
    topFilterChange(filter) {
      this.topFilterRadio = filter;
      this.loadData();
    },
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.unit_id = "";
        this.asset_id = "";
      }
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.date_filter_radio = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.dateFilterRadio : null;
      params.date_filter_value = this.$refs.BuildingDateRange ? this.$refs.BuildingDateRange.date_filter_value : null;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.buildingId = this.$parent.buildingIndex;
      params.unit_id = this.unit_id;
      if (this.asset_id === '') this.asset_id = "all";
      params.entry_type = this.asset_id;
      if (this.topFilterRadio === 'manual_grant') {
        params.entry_type = 'grant';
      }
      params.asset_id = this.asset_id;
      params.images_only = this.images_only;
      params.no_alarm = this.no_alarm;
      params.mail_man = this.topFilterRadio === 'mail_code_usage';
      params.unreadable = this.topFilterRadio === 'unreadable';

      if (this.topFilterRadio === 'image_entrance')
        this.$http
            .post("/fobsHidImageAnalytics", params)
            .then((response) => {
              this.imagesArray = response.data;
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      else
        this.$http
            .post("/fobsHidAnalytics", params)
            .then((response) => {
              this.dataArray = response.data;
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
    },
  },

};
</script>

