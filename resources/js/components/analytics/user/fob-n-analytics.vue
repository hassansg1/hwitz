<template>
  <div>
    <div class="row mb-28">
      <BuildingDateRange ref="BuildingDateRange"/>
      <EntriesPerPage ref="EntriesPerPage"/>
      <div class="col-8">
        <h3 class="h3 mb-0">Fobs 2N</h3>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <select v-model="asset_id"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Door (all)</option>
          <option v-for="optionItem in this.$parent.currentBuilding.doors" :value="optionItem.id">
            {{ optionItem.name }}
          </option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="event_name"
                class="form-select" aria-label="Default select example"
                @change="loadData()">
          <option value="" selected>Select Event (all)</option>
          <option v-for="optionItem in this.doors_event_types" :value="optionItem.event_type">
            {{ optionItem.event_type }}
          </option>
          <option v-for="optionItem in this.doors_events_names" :value="optionItem.event_description">
            {{ optionItem.event_description }}
          </option>
        </select>
      </div>

    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Date
              <Sorting :sortColumn="'timestamp'"/>
            <th>Door
              <Sorting :sortColumn="'door_id'"/>
            <th>Event
              <Sorting :sortColumn="'event_name'"/>
            <th>Image</th>
            <th>Video</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ formatDateTime(data.timestamp) }}</span>
            </td>
            <td>
              <span v-if="data.door" class="text-medium">{{ data.door.name }}</span>
            </td>
            <td>
              <span v-if="data.show_name" class="text-medium">{{ data.show_name }}</span>
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
    <FiveImagesModal :type="'fobsVerso'" ref="FiveImagesModal"></FiveImagesModal>
    <EntryVideoModal :type="'fobsVerso'" ref="EntryVideoModal"></EntryVideoModal></div>
</template>

<script>
import Sorting from "../../sorting.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import BuildingDateRange from "../../building-date-range.vue";
import FiveImagesModal from "../five-images-modal.vue";
import EntryVideoModal from "../entry-video-modal.vue";
import ImageRow from "../image-row.vue";
import VideoRow from "../video-row.vue";

export default {
  components: {Sorting, EntriesPerPage, BuildingDateRange, FiveImagesModal, EntryVideoModal, ImageRow, VideoRow},
  created() {
    this.loadEventsDataForVersoReport();
  },
  data() {
    return {
      doors_events_names: {},
      doors_event_types: {},
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      asset_id: '',
      event_name: '',
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
      return events;
    },
    loadEventsDataForVersoReport(){
      this.$http
          .get("/loadEventsDataForVersoReport")
          .then((response) => {
            this.doors_event_types = response.data.doors_event_types;
            this.doors_events_names = response.data.doors_events_names;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.event_name = "";
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
      params.event_name = this.event_name;
      params.asset_id = this.asset_id;

      this.$http
          .post("/fobsVersoAnalytics", params)
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

