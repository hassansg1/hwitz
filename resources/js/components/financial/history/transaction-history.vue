<template>
  <div>
    <div class="row mb-28">
      <div class="col-8">
        <h3 class="h3 mb-0">Financial History</h3>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-md-3">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize"
            >Select type ()all</label
          >
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
            v-if="building.units"
            v-model="unitId"
            placeholder=""
            label="unit_no"
            track-by="id"
            :show-labels="true"
            :options="building.units"
            :multiple="false"
            @input="loadData()"
          ></multiselect>
        </div>
      </div>
      <div class="col-md-3">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize">Select</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
            v-if="building.units"
            v-model="unitId"
            placeholder=""
            label="unit_no"
            track-by="id"
            :show-labels="true"
            :options="building.units"
            :multiple="false"
            @input="loadData()"
          ></multiselect>
        </div>
        <!-- <select
          v-model="type"
          class="form-select"
          aria-label="Default select example"
          @change="loadData()"
        >
          <option value="" selected>Select Type (all)</option>
          <option value="payment">Payment</option>
          <option value="charge">Charge</option>
        </select> -->
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>Building</th>
            <th>Unit</th>
            <th>Cart Type</th>
            <th>Receiver</th>
            <th>Date</th>
            <th>Description</th>
            <th>Status</th>
            <th>Charge</th>
            <th>Payment</th>
            <th>Running Balance</th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td
              :class="{
                'details-control':
                  data.invoice_type === 'ongoing invoice payment' ||
                  data.invoice_type === 'ongoing invoice',
              }"
            >
              <span
                style="cursor: pointer; color: #337ab7"
                v-if="
                  data.invoice_type === 'ongoing invoice payment' ||
                  data.invoice_type === 'ongoing invoice'
                "
                :class="'glyphicon glyphicon-plus icon_' + data.id"
              ></span>
              <span v-if="data.building" class="text-medium">
                {{ data.building.name }}
              </span>
            </td>
            <td>
              <span class="text-medium" v-if="data.unit">{{
                data.unit.unit_no
              }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.cart_label">{{
                data.cart_label
              }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.receiver">{{
                data.receiver.name
              }}</span>
            </td>
            <td>
              <span class="text-medium" v-if="data.timestamp">{{
                formatDateTime(data.timestamp)
              }}</span>
            </td>
            <td>
              <span class="text-medium">
                <!--                <a href="" v-if="data.paymentLink">Here</a>-->
              </span>
            </td>
            <td>
              <span
                class="text-medium"
                style="text-align: right; color: red"
                v-if="data.amount < 0"
                >{{ chargeAmountFormatter(data.amount) }}</span
              >
              <span
                class="text-medium"
                style="text-align: right; color: green"
                v-if="data.amount > 0"
                >{{ formatMoneyWithCommas(data.amount) }}</span
              >
            </td>
            <td>
              <span
                class="text-medium"
                style="text-align: right; color: red"
                v-if="data.balance < 0"
                >{{ chargeAmountFormatter(data.balance) }}</span
              >
              <span
                class="text-medium"
                style="text-align: right; color: green"
                v-if="data.balance > 0"
                >{{ formatMoneyWithCommas(data.balance) }}</span
              >
            </td>
          </tr>
        </table>
      </div>
      <span>
        <pagination :pagination="dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>
import Sorting from "../../sorting.vue";

export default {
  props: ["building"],
  components: { Sorting },
  created() {},
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      unitId: "",
      type: "",
    };
  },
  expose: ["loadData"],
  methods: {
    loadData(pageNumber = null) {
      if (this.$parent.clearFilters) {
        this.unitId = "";
        this.type = "";
      }
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = this.$parent.params;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.type = this.type;
      params.unitId = this.unitId;

      this.$http
        .post("/transactionAnalytics", params)
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
