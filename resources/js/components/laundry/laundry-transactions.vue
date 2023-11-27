<template>
  <div class="laundry-section">
    <div class="d-flex flex-row flex-wrap justify-content-lg-end mb-28">
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href="">Select</a>
      <a class="align-self-center a-link me-20 mb-16 mb-lg-0" href=""
        >Select all</a
      >
      <a class="align-self-center a-link mb-16 mb-lg-0" href="">Delete all</a>
    </div>

    <div class="mb-28">
      <div class="row gy-3">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="dropdown-wrapper text-start">
            <label class="dropdown-label text-capitalize">Room</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="roomValue"
              tag-placeholder="Add this as new tag"
              placeholder=""
              label="name"
              track-by="code"
              :options="roomOptions"
              :multiple="false"
            ></multiselect>
          </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="dropdown-wrapper text-start">
            <label class="dropdown-label text-capitalize">Applience</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="applienceValue"
              tag-placeholder="Add this as new tag"
              placeholder=""
              label="name"
              track-by="code"
              :options="applianceOptions"
              :multiple="false"
            ></multiselect>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="dropdown-wrapper text-start">
            <label class="dropdown-label text-capitalize">Unit</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="unitValue"
              tag-placeholder="Add this as new tag"
              placeholder=""
              label="name"
              track-by="code"
              :options="unitoptions"
              :multiple="false"
            ></multiselect>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="dropdown-wrapper text-start">
            <label class="dropdown-label text-capitalize">User</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="userValue"
              tag-placeholder="Add this as new tag"
              placeholder=""
              label="name"
              track-by="code"
              :options="useroptions"
              :multiple="false"
            ></multiselect>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-50">
      <div class="row gy-3 mb-28">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <p class="text-normal-bold-md color-secondary mb-20">Time</p>
          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Current month to date
                </p>
              </div>
            </div>
          </div>

          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Last 3 months
                </p>
              </div>
            </div>
          </div>

          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  v-model="dateRange"
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Date range
                </p>
              </div>

              <div class="calender-custom-styles" v-if="dateRange">
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
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
          <p class="text-normal-bold-md color-secondary mb-20">Select</p>
          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Prepayments to laundry carts
                </p>
              </div>
            </div>
          </div>

          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Purchase history
                </p>
              </div>
            </div>
          </div>

          <div class="row mb-11">
            <div class="col-12">
              <div class="d-flex">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />

                <p
                  class="text-normal color-secondary align-self-center mt-9 mb-0"
                >
                  Both
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-6 col-lg-8">
          <div class="d-flex justify-content-end">
            <button class="btn btn-dark me-12">Cancel</button>
            <button class="btn btn-primary">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex flex-row flex-wrap mb-28">
      <div
        class="align-self-center text-normal-bold-md color-secondary me-12 mb-12"
      >
        Entries
      </div>
      <div
        class="badge-gray me-12 mb-12"
        v-for="index in 4"
        @click="selectIt(index)"
        :class="{ activeBadge: index == selectedItem }"
      >
        Last {{ index + 5 }} days
      </div>
    </div>
    <div class="table-responsive mb-28">
      <table>
        <tr>
          <th>Date</th>
          <th>Laundry Room Appliance</th>
          <th>Go to:</th>
          <th>Transaction Type</th>
          <th>Charge</th>
          <th>Running Balance</th>
          <th>Proof</th>
        </tr>
        <tr class="border_bottom" v-for="index in selectedItem">
          <td>
            <span class="text-medium color-secondary-dark">April 13, 2023</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark"
              >238 N Pine Washer 1</span
            >
          </td>

          <td>
            <span class="text-medium-bold color-primary">User profile</span>
          </td>

          <td>
            <span class="text-medium color-secondary-dark"
              >Appliance Start</span
            >
          </td>

          <td>
            <span class="text-medium color-danger">$10</span>
          </td>

          <td>
            <span class="text-medium color-primary">$10</span>
          </td>

          <td>
            <div class="camera-pic-container-sm position-relative pt-7">
              <img src="/images/camera-pic.png" class="avatar-img" alt="" />
              <img
                src="/images/icons/expand.png"
                class="position-absolute select-cursor expand-icon"
                alt=""
              />
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import { ref } from "vue";
export default {
  components: { DatePicker },
  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
    const dragValue = ref(null);
    return {
      selectedItem: 1,
      dateRange: false,
      range,
      dragValue,

      applienceValue: [{ name: "applience 1", code: "1" }],
      applianceOptions: [
        { name: "applience 1", code: "1" },
        { name: "applience 2", code: "2" },
        { name: "applience 3", code: "3" },
        { name: "applience 4", code: "4" },
      ],

      roomValue: [{ name: "room 1", code: "1" }],
      roomOptions: [
        { name: "room 1", code: "1" },
        { name: "room 2", code: "2" },
        { name: "room 3", code: "3" },
        { name: "room 4", code: "4" },
      ],

      unitValue: [{ name: "unit 1", code: "1" }],
      unitoptions: [
        { name: "unit 1", code: "1" },
        { name: "unit 2", code: "2" },
        { name: "unit 3", code: "3" },
        { name: "unit 4", code: "4" },
      ],

      userValue: [{ name: "user 1", code: "1" }],
      useroptions: [
        { name: "user 1", code: "1" },
        { name: "user 2", code: "2" },
        { name: "user 3", code: "3" },
        { name: "user 4", code: "4" },
      ],
    };
  },
  methods: {
    selectIt(index) {
      console.log("old", index);
      this.selectedItem = index;
      console.log("new", this.selectedItem);
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
