<template>
  <div>
    <div class="row mb-50">
      <div class="col-lg-3 col-md-6">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize">Building</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="building"
              label="name"
              track-by="id"
              :options="buildings"
              :multiple="false"
              placeholder=""
              @input="loadData()"
          ></multiselect>
        </div>
      </div>
    </div>
    <BuildingDateRange ref="BuildingDateRange"></BuildingDateRange>
    <EntriesPerPage ref="EntriesPerPage"></EntriesPerPage>

    <div class="row mb-28">
      <div class="col-lg-8">
        <div class="d-flex flex-column align-items-start flex-lg-row">
          <div class="me-44 align-self-lg-center mb-12">
            <h3 class="h3 mb-0">Scheduled Receivables</h3>
          </div>
          <div class="d-flex mb-12">
            <button
                class="btn bkg-success me-12"
                @click="openOneTimeInvoicePopup"
            >
              Create one time invoice
            </button>

            <!-- <div class="align-self-center">
              <img src="/images/icons/settings.png" alt="icon" />
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>Date</th>
              <th>Total Amount</th>
              <th># of items</th>
            </tr>
            <template v-for="(data, index) in this.dataArray.data">
              <tr class="border_bottom">
                <td><span class="text-medium">{{ formatDate(data.timestamp) }}</span></td>
                <td>
                <span class="text-medium-bold color-primary">
                  {{ formatMoneyWithCommas(data.itemsTotalAmount) }}
                </span>
                </td>
                <td>
                  <div class="d-flex justify-content-between">
                    <div class="text-medium color-secondary">{{ data.items.length }}</div>

                    <div @click="toggleRow(index)">
                      <img :class="{ rotateIcon: showRow && showTable === index }" src="/images/icons/down-arrow.png"
                           alt=""/>
                    </div>
                  </div>
                </td>
              </tr>
              <tr v-if="showRow && showTable === index">
                <td colspan="4">
                  <table class="table table_styled">
                    <tr>
                      <th>Building</th>
                      <th>Date</th>
                      <th>Receiver</th>
                      <th>Amount</th>
                      <th>ACH Status</th>
                      <th>Reconciled By</th>
                    </tr>
                    <tr v-for="log in data.items">
                      <td class="text-medium-bold">{{ log.building ? log.building.name : '-' }}</td>
                      <td class="text-medium color-secondary">{{ formatDate(log.created_at) }}</td>
                      <td class="text-medium color-secondary">{{ log.receiver ? log.receiver.name : '-' }}</td>
                      <td class="text-medium-bold color-primary">{{ log.amount ? '$' + log.amount : '-' }}</td>
                      <td class="text-medium " v-if="log.is_cleared">
                        <span class="reconciled">
                          Reconciled
                        </span>
                      </td>
                      <td class="text-medium " v-else>
                        <span class="pending">Pending</span>
                      </td>
                      <td v-if="log.receiver_id">
                        <span v-if="log.is_cleared && log.reconciled_person">{{
                            log.reconciled_person.name
                          }}</span>
                        <span style="font-size: 12px">{{ formatDateTime(log.reconciled_timestamp) }}</span>

                      </td>
                      <td v-else>-</td>

                    </tr>
                  </table>
                </td>
              </tr>
            </template>
          </table>
        </div>
      </div>
    </div>
    <span>
          <pagination :pagination=" dataArray"></pagination>
    </span>

    <OneTimeInvoice ref="oneTimeInvoice"/>
  </div>
</template>

<script>
import OneTimeInvoice from "./one-time-invoice.vue";
import BuildingDateRange from "../../building-date-range.vue";
import EntriesPerPage from "../../entries-per-page.vue";

export default {
  components: {
    OneTimeInvoice,
    BuildingDateRange,
    EntriesPerPage,
  },

  data() {
    return {
      showRow: false,
      showTable: 0,
      isShowDropdown: false,
      selectedBuilding: "",
      selectedDoc: 0,
      financialDocuments: [
        "Carts",
        "Transactions",
        "Invoices",
        "Charges",
        "Payments",
      ],
      buildings: [],
      building: '',
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
    };
  },
  methods: {
    toggleRow(index) {
      if (this.showTable === index)
        this.showRow = !this.showRow;
      this.showTable = index;
    },
    getBuildings() {
      this.loadData();
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            this.buildings.unshift({"id": 0, "name": "Portfolio Wide"});
            if (this.building == '') {
              this.building = this.buildings.find(obj => obj.id === this.$gate.building_id);
            }
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadData(pageNumber = null) {
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
      params.buildingId = this.building ? this.building.id : 0;
      params.applyGroupByDate = 1;
      this.$http
          .post("/ownerReceivables", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    toggleDropdown() {
      this.isShowDropdown = !this.isShowDropdown;
    },
    selectBuilding(index) {
      this.selectedBuilding = this.allBuildings[index];
    },
    openOneTimeInvoicePopup() {
      let buildingId = this.building ? this.building.id : 0;
      if (buildingId == 0) {
        Toast.fire({
          icon: 'warning',
          title: 'Please select a building.',
        });
        return;
      }
      this.$refs.oneTimeInvoice.form.reset();
      this.$refs.oneTimeInvoice.getReceivableAndPayableUserTypes();
      this.$refs.oneTimeInvoice.form.building_id = this.building ? this.building.id : 0;
      $("#oneTiemInvoice").modal('show');
    }
  },
};
</script>
