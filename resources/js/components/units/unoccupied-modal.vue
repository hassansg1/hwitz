<template>
  <div>
    <div
      class="modal fade unit-modal"
      id="unitUnoccupiedModal"
      tabindex="-1"
      aria-labelledby="unitUnoccupiedModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg-md">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>

          <div class="modal-body">
            <h1 class="h1 mb-70">Generate Vacancy report</h1>

            <div class="d-flex justify-content-center mb-36">
              <div
                class="circle me-20"
                :class="{ active: index === selectedIndex }"
                v-for="index in 2"
                @click="wizeredSelect(index)"
              >
                {{ index }}
              </div>
              <!-- <div class="circle">2</div> -->
            </div>

            <div v-if="selectedIndex === 1">
              <div class="">
                <p class="text-medium color-dark">Select date range</p>
              </div>
              <div class="mb-28">
                <DatePicker
                  is-range
                  title-position="left"
                  v-model="range"
                  :masks="{ weekdays: 'WWW' }"
                  :select-attribute="selectDragAttribute"
                  :drag-attribute="selectDragAttribute"
                  @drag="dragValue = $event"
                >
                  <template #day-popover="{ format }">
                    <div class="text-sm">
                      {{
                        format(
                          dragValue ? dragValue.start : range.start,
                          "MMM D"
                        )
                      }}
                      -
                      {{
                        format(dragValue ? dragValue.end : range.end, "MMM D")
                      }}
                    </div>
                  </template>
                </DatePicker>
              </div>

              <div class="d-flex justify-content-end">
                <button class="btn btn-primary">Generate report</button>
              </div>
            </div>

            <div v-if="selectedIndex === 2">
              <div class="card">
                <div class="card-header text-start">PDF view</div>
                <div class="card-body">
                  <vue-pdf-embed
                    class="border"
                    :source="'/test.pdf'"
                    disableAnnotationLayer="false"
                    disableTextLayer="false"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import VuePdfEmbed from "vue-pdf-embed/dist/vue2-pdf-embed";
import { ref } from "vue";

export default {
  components: {
    DatePicker,
    VuePdfEmbed,
  },
  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
    const dragValue = ref(null);
    return {
      range,
      dragValue,
      selectedIndex: 1,
    };
  },

  methods: {
    wizeredSelect(index) {
      this.selectedIndex = index;
    },
  },
  computed: {
    selectDragAttribute() {
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
