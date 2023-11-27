<template>
  <div class="building-date-range">
    <div class="row">
      <div class="col-lg-12">
        <p class="text-normal-bold-md mb-12 color-secondary">Date Range</p>
        <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                v-for="(title, index) in date_ranges_list"
                :class="{ active: dateFilterRadio === index }"
                v-on:click="dateFilter(index)"
            >{{ title }}</span
            >
        </div>
      </div>
      <div class="col-lg-6">
        <div class="calender-custom-styles" v-if="dateRange">
          <DatePicker
              is-range
              title-position="left"
              v-model="range"
              :model-config="{
                type: 'string',
                mask: 'MM/DD/YYYY HH:mm',
              }"
              :masks="{ L: 'MM/DD/YYYY HH:mm' }"
              :select-attribute="selectDragAttribute"
              :drag-attribute="selectDragAttribute"
              @drag="dragValue = $event"
          >
            <template #day-popover="{ format }">
              <div class="text-sm">
                {{
                  format(dragValue ? dragValue.start : range.start, "MMM D")
                }}
                -
                {{ format(dragValue ? dragValue.end : range.end, "MMM D") }}
              </div>
            </template>
          </DatePicker>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import {ref} from "vue";

export default {
  name: "Admin",
  components: {
    DatePicker,
  },

  data() {
    const range = ref({});
    const dragValue = ref(null);
    return {
      range,
      dragValue,
      dateRange: false,
      dateFilterRadio: "last_seven",
      date_filter_value: "",
      date_ranges_list: {
        "last_seven": "Last 7 days",
        "last_thirty": "Last 30 days",
        "last_quarter": "Last quarter",
        "last_six": "Last 6 months",
        "last_year": "Last calender year",
        "year_to_date": "Year to date",
        "date_range": "Date range",
      }
    };
  },
  created() {
  },
  mounted() {
  },
  methods: {

    dateFilter(filter) {
      this.dateRange = false;
      this.dateFilterRadio = filter;
      if (filter === "date_range") {
        this.dateRange = true;
      } else {
        this.$parent.loadData();
      }
    },
  },
  computed: {
    selectDragAttribute() {
      if (
          typeof this.range.start !== "undefined" &&
          typeof this.range.end !== "undefined"
      ) {
        this.date_filter_value = this.range.start;
        this.date_filter_value += " - ";
        this.date_filter_value += this.range.end;
        this.$parent.loadData();
      }

      return {
        popover: {
          visibility: "hover",
          isInteractive: false,
        },
      };
    },
  },
};
</script>
