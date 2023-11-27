<template>
  <div class="f-payable">
    <div class="row gy-3 mb-28">
      <div class="col-lg-3 col-md-6">
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="searchKeyword"
            @change="loadData()"
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="dropdown-wrapper">
          <label class="dropdown-label text-capitalize">Status</label>
          <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="status"
              placeholder=""
              label="label"
              track-by="id"
              :options="statuses"
              :multiple="false"
              @input="loadData()"
          ></multiselect>
        </div>
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-lg-8">
        <div class="d-flex flex-column align-items-start flex-lg-row">
          <div class="me-44 align-self-lg-center mb-12">
            <h3 class="h3 mb-0">Payables <span>({{ data.length }})</span></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-8">
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>Invoice #
                <Sorting :sortColumn="'token'"/>
              </th>
              <th>Created At
                <Sorting :sortColumn="'created_at'"/>
              </th>
              <th>Proof</th>
              <th>Amount
                <Sorting :sortColumn="'amount'"/>
              </th>
              <th>Payment Status
                <Sorting :sortColumn="'status'"/>
              </th>
              <th>Action</th>
            </tr>

            <tr class="border_bottom" v-for="payable in data">
              <td>
                <span class="text-medium-bold">{{ payable.token }}</span>
              </td>
              <td>
                <span class="text-medium color-secondary">{{ formatDate(payable.created_at) }}</span>
              </td>

              <td>
                <a target="_blank" class="text-medium color-primary border-bottom"
                   :href="payable && payable.file_path ? payable.file_path : 'javascript:void(0)'">
                  View invoice</a>
                <br>
                <a v-if="payable.status === 'accepted'" target="_blank"
                   class="text-medium color-primary border-bottom"
                   :href="payable && payable.receipt_file_path ? payable.receipt_file_path : 'javascript:void(0)'">
                  View receipt</a>
              </td>
              <td>
                <span class="text-medium-bold color-danger" v-if="payable.amount">${{ payable.amount }}</span>
                <span class="text-medium-bold color-danger" v-else>-</span>
              </td>

              <td>
                <span class="pending" v-if="payable.status === 'pending'">Pending</span>
                <span class="reconciled" v-else-if="payable.status === 'accepted'">Paid</span>
                <span class="deleted" v-else-if="payable.status === 'deleted'">Deleted</span>
              </td>

              <td>
                <a v-if="payable.status === 'pending'" :href="payable.pay_now" target="_blank" class="reconcile"
                   style="text-decoration: none;">Pay now</a>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <span><pagination :pagination="pagination"></pagination></span>
    <PayableModal/>
  </div>
</template>

<script>
import PayableModal from "./payable-modal.vue";

export default {
  components: {
    PayableModal,
  },
  data() {
    return {
      status: null,
      statuses: [
        {"id": "pending", "label": "Pending"},
        {"id": "accepted", "label": "Paid"},
      ],
      data: [],
      // pagination start
      searchKeyword: '',
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      params: {},

      //pagination end

    };
  },
  methods: {
    loadData(pageNumber = null) {
      let building_id = this.building ? this.building.id : 0;

      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.params = {};
      this.params.building_id = building_id;
      this.params.totalItems = this.entriesPerPageSelected;
      this.params.current_page = this.pageNumber;
      this.params.sortOrder = this.sortOrder;
      this.params.sortBy = this.sortColumn;
      this.params.status = this.status;
      this.params.searchKeyword = this.searchKeyword;

      this.showLoader();
      this.$http
          .post("/loadPayables", this.params)
          .then((response) => {
            this.pagination = response.data;
            this.data = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            if (this.building == '') {
              this.building = this.buildings.find(obj => obj.id === this.$gate.building_id);
            }
            this.loadData();
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
};
</script>
