<template>
  <div class="a-summary">
    <div class="mt-65">
      <div class="row mb-28">
        <div class="col-lg-6">
          <div class="input-group search-input">
            <input
              class="form-control"
              type="search"
              placeholder="Search..."
              v-model="searchData"
              @input="search()"
            />
            <button
              class="btn btn-outline-secondary bg-white border-0 ms-n5"
              type="button"
            >
              <svg
                width="18"
                height="18"
                viewBox="0 0 18 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M7.5 1.5C4.18629 1.5 1.5 4.18629 1.5 7.5C1.5 10.8137 4.18629 13.5 7.5 13.5C10.8137 13.5 13.5 10.8137 13.5 7.5C13.5 4.18629 10.8137 1.5 7.5 1.5ZM0 7.5C0 3.35786 3.35786 0 7.5 0C11.6421 0 15 3.35786 15 7.5C15 9.30095 14.3652 10.9536 13.3072 12.2465L17.7803 16.7197C18.0732 17.0126 18.0732 17.4874 17.7803 17.7803C17.4874 18.0732 17.0126 18.0732 16.7197 17.7803L12.2465 13.3072C10.9536 14.3652 9.30095 15 7.5 15C3.35786 15 0 11.6421 0 7.5Z"
                  fill="#CCCCCC"
                />
              </svg>
            </button>
          </div>
        </div>
        <div class="col-lg-2">
          <select
            class="form-select d-inline"
            aria-label="Default select example"
          >
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>
      <h3 class="h3 mb-28">Charts</h3>
      <div class="row g-4 mb-64">
        <div class="col-lg-4">
          <div class="card">
            <div>
              <p class="mb-11 text-normal-bold-md color-secondary">
                Portal Chart
              </p>
            </div>
            <div>
              <h3 class="h3 mb-24">
                Residents accessing portal
                <span class="color-primary">1254</span>
              </h3>
            </div>
            <Bar
              id="my-chart-id"
              :ref="chartRef"
              :options="options"
              :data="chartData"
              :plugins="[backgroundBar]"
            />
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <div>
              <p class="mb-11 text-normal-bold-md color-secondary">
                Portal Chart
              </p>
            </div>
            <div>
              <h3 class="h3 mb-24">
                Visits
                <span class="color-primary">1254</span>
              </h3>
            </div>
            <Bar
              id="new2-chart-id"
              :ref="chartRef"
              :options="options"
              :data="chartData"
              :plugins="[backgroundBar]"
            />
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <div>
              <p class="mb-11 text-normal-bold-md color-secondary">
                Portal Chart
              </p>
            </div>
            <div>
              <h3 class="h3 mb-24">
                Work orders
                <span class="color-primary">1254</span>
              </h3>
            </div>
            <Bar
              id="new1-chart"
              :ref="chartRef"
              :options="options"
              :data="chartData"
              :plugins="[backgroundBar]"
            />
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <div>
              <p class="mb-11 text-normal-bold-md color-secondary">
                Portal Chart
              </p>
            </div>
            <div>
              <h3 class="h3 mb-24">
                Average time using the app
                <span class="color-primary">34m</span>
              </h3>
            </div>
            <areachart />
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-28">
      <div class="col-lg-6">
        <p class="text-normal-bold-md mb-12 color-secondary">Building</p>
        <div class="d-flex flex-row flex-wrap mb-28">
          <span
            class="text-normal-bold-md building-badge select-cursor"
            v-for="(building, index) in buildings"
            :class="{ active: buildingIndex === index }"
            @click="selectBuilding(index)"
            >{{ building }}
          </span>
        </div>

        <div class="row">
          <div class="col-lg-9">
            <div>
              <div class="card">
                <p class="text-normal-bold-md color-secondary mb-8">
                  Fob reception details
                </p>
                <h3 class="h3 mb-8">320 packages</h3>
                <p class="text-normal color-secondary">Used from 02.04.2020</p>
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: 25%"
                    aria-valuenow="25"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <p class="text-normal-bold-md color-secondary mb-12">Time</p>

        <div class="row">
          <div class="col-6 mb-12">
            <div class="d-flex">
              <input
                class="form-check-input mt-0 me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
              <label
                class="text-normal align-self-center color-secondary form-check-label"
                for="flexCheckDefault"
              >
                Last 7 days
              </label>
            </div>
          </div>
          <div class="col-6 mb-12">
            <div class="d-flex">
              <input
                class="form-check-input mt-0 me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
              <label
                class="text-normal align-self-center color-secondary form-check-label"
                for="flexCheckDefault"
              >
                Last 7 days
              </label>
            </div>
          </div>
          <div class="col-6 mb-12">
            <div class="d-flex">
              <input
                class="form-check-input mt-0 me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
              <label
                class="text-normal align-self-center color-secondary form-check-label"
                for="flexCheckDefault"
              >
                Last 7 days
              </label>
            </div>
          </div>
          <div class="col-6 mb-12">
            <div class="d-flex">
              <input
                class="form-check-input mt-0 me-12"
                type="checkbox"
                value=""
                v-model="dateRange"
                id="dateRange"
              />
              <label
                class="text-normal align-self-center color-secondary form-check-label"
                for="dateRange"
              >
                Date Range
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
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
                {{ format(dragValue ? dragValue.start : range.start, "MMM D") }}
                -
                {{ format(dragValue ? dragValue.end : range.end, "MMM D") }}
              </div>
            </template>
          </DatePicker>
        </div>
      </div>
    </div>

    <div class="row mb-28">
      <div class="col-8">
        <h3 class="h3 mb-0">FOBs</h3>
      </div>
      <div class="col-4">
        <div class="d-flex justify-content-between">
          <span class="text-medium color-primary select-cursor links"
            >Export to .xls</span
          >
          <span class="text-medium color-primary select-cursor links"
            >Select</span
          >
          <span class="text-medium color-primary select-cursor links"
            >Select all</span
          >
          <span class="text-medium color-primary select-cursor links"
            >Delete all</span
          >
        </div>
      </div>
    </div>

    <div class="mb-66">
      <div class="table-responsive">
        <table>
          <tr>
            <th>id</th>
            <th>Last Assigned to</th>
            <th>Date discarted</th>
            <th>Action</th>
          </tr>
          <tr class="border_bottom">
            <td class="h4">0001</td>
            <td>John Doe</td>
            <td class="text-medium color-secondary">12.02.2022</td>

            <td>
              <div class="d-flex">
                <button class="btn btn-primary me-12">Remove</button>
                <button class="btn btn-secondary">Permanently Discarted</button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="row mb-66">
      <div class="col-lg-6">
        <div class="row">
          <div class="col-lg-9">
            <div class="card">
              <p class="text-normal-bold-md color-secondary mb-8">
                Fob reception details
              </p>
              <h3 class="h3 mb-8">320 packages</h3>
              <p class="text-normal color-secondary">Used from 02.04.2020</p>
              <div class="progress">
                <div
                  class="progress-bar"
                  role="progressbar"
                  style="width: 25%"
                  aria-valuenow="25"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-66">
      <div class="table-responsive">
        <table>
          <tr>
            <th>id</th>
            <th>Last Assigned to</th>
            <th>Date discarted</th>
            <th>Action</th>
          </tr>
          <tr class="border_bottom">
            <td class="h4">0001</td>
            <td>John Doe</td>
            <td class="text-medium color-secondary">12.02.2022</td>

            <td>
              <div class="d-flex">
                <button class="btn btn-primary me-12">Remove</button>
                <button class="btn btn-secondary">Permanently Discarted</button>
              </div>
            </td>
          </tr>
          <tr class="border_bottom">
            <td class="h4">0001</td>
            <td>John Doe</td>
            <td class="text-medium color-secondary">12.02.2022</td>

            <td>
              <div class="d-flex">
                <button class="btn btn-primary me-12">Remove</button>
                <button class="btn btn-secondary">Permanently Discarted</button>
              </div>
            </td>
          </tr>
          <tr class="border_bottom">
            <td class="h4">0001</td>
            <td>John Doe</td>
            <td class="text-medium color-secondary">12.02.2022</td>

            <td>
              <div class="d-flex">
                <button class="btn btn-primary me-12">Remove</button>
                <button class="btn btn-secondary">Permanently Discarted</button>
              </div>
            </td>
          </tr>
          <tr class="border_bottom">
            <td class="h4">0001</td>
            <td>John Doe</td>
            <td class="text-medium color-secondary">12.02.2022</td>
            <td>
              <div class="d-flex">
                <button class="btn btn-primary me-12">Remove</button>
                <button class="btn btn-secondary">Permanently Discarted</button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div>
      <div class="row mb-24">
        <div class="col-lg-4 col-md-6">
          <div class="border">
            <div class="d-flex header border-bottom">
              <p
                class="text-normal-bold-md color-secondary align-self-center ps-100 mb-0"
              >
                Name
              </p>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="border">
            <div class="d-flex header border-bottom">
              <p
                class="text-normal-bold-md color-secondary align-self-center ps-100 mb-0"
              >
                Name
              </p>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="border">
            <div class="d-flex header border-bottom">
              <p
                class="text-normal-bold-md color-secondary align-self-center ps-100 mb-0"
              >
                Name
              </p>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex border-bottom py-7">
              <div class="align-self-center px-24">
                <input
                  class="form-check-input me-12"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
              </div>
              <div>
                <div class="d-flex">
                  <div class="avatar-sm me-8 align-self-center">
                    <img
                      src="/images/worker.png"
                      class="avatar-img"
                      alt="worker"
                    />
                  </div>
                  <div class="align-self-center">
                    <p class="text-medium-bold mb-0">John Doe</p>
                    <p class="text-normal mb-0 color-primary">123 456 7890</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-center">
        <span class="pagination-btn">
          <img src="/images/icons/left-arrow.png" />
        </span>
        <span class="pagination-btn active">1</span>
        <span class="pagination-btn">2</span>
        <span class="pagination-btn">3</span>
        <span class="pagination-btn">
          <img src="/images/icons/right-arrow.png" />
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import areachart from "../utils/area-chart.vue";

import { ref } from "vue";
import { Bar } from "vue-chartjs";

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

export default {
  name: "Summary",
  components: { Bar, DatePicker, areachart },

  data() {
    const range = ref({
      start: new Date(2023, 0, 6),
      end: new Date(2023, 0, 10),
    });
    const chartRef = ref();
    const backgroundBar = {
      id: "backgroundBar",
      beforeDatasetsDraw(chartRef, args, pluginOptions) {
        const {
          data,
          ctx,
          chartArea: { top, bottom, left, right, width, height },
          scales: { x, y },
        } = chartRef;
        ctx.save();
        const segment = width / data.datasets[0].data.length;
        const barWidth =
          segment *
          data.datasets[0].barPercentage *
          data.datasets[0].categoryPercentage;
        const borderRadius = 8;
        ctx.fillStyle = "#F2F5F9";
        data.labels.forEach((dataset, index) => {
          ctx.beginPath();
          ctx.moveTo(
            x.getPixelForValue(index) - barWidth / 2 + borderRadius,
            top
          );
          ctx.lineTo(
            x.getPixelForValue(index) + barWidth / 2 - borderRadius,
            top
          );
          ctx.quadraticCurveTo(
            x.getPixelForValue(index) + barWidth / 2,
            top,
            x.getPixelForValue(index) + barWidth / 2,
            top + borderRadius
          );
          ctx.lineTo(
            x.getPixelForValue(index) + barWidth / 2,
            bottom - borderRadius
          );
          ctx.quadraticCurveTo(
            x.getPixelForValue(index) + barWidth / 2,
            bottom,
            x.getPixelForValue(index) + barWidth / 2 - borderRadius,
            bottom
          );
          ctx.lineTo(
            x.getPixelForValue(index) - barWidth / 2 + borderRadius,
            bottom
          );
          ctx.quadraticCurveTo(
            x.getPixelForValue(index) - barWidth / 2,
            bottom,
            x.getPixelForValue(index) - barWidth / 2,
            bottom - borderRadius
          );
          ctx.lineTo(
            x.getPixelForValue(index) - barWidth / 2,
            top + borderRadius
          );
          ctx.quadraticCurveTo(
            x.getPixelForValue(index) - barWidth / 2,
            top,
            x.getPixelForValue(index) - barWidth / 2 + borderRadius,
            top
          );
          ctx.closePath();
          ctx.fill();
        });
      },
    };
    const dragValue = ref(null);
    return {
      chartRef,
      backgroundBar,
      dateRange: false,
      buildingIndex: 0,
      chartData: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Aug"],
        datasets: [
          {
            data: [41, 20, 12, 62, 71, 86, 76],
            label: "none",
            barPercentage: 0.8,
            barThickness: 37,
            categoryPercentage: 0.8,
            minBarLength: 2,
            backgroundColor: "#47c5fe",
            borderRadius: 8,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            display: true,
            grid: {
              display: false,
            },
          },
          y: {
            display: false,
            beginAtZero: true,
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
      range,
      dragValue,
      buildings: [
        "Building 1",
        "Building 2",
        "Building 3",
        "Building 4",
        "Building 5",
        "Building 6",
        "Building 7",
        "Building 8",
      ],
    };
  },
  methods: {
    selectBuilding(index) {
      this.buildingIndex = index;
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
