<template>
  <div class="f-accounts a-admin">
    <div class="row">
      <div class="col-md-4">
        <EntriesPerPage ref="EntriesPerPage"/>
      </div>
      <p class="text-normal-bold-md mb-12 color-secondary">Wallet Type</p>
      <div class="d-flex flex-row flex-wrap mb-28">
            <span
                class="text-normal-bold-md building-badge select-cursor"
                :class="{ active: type === 0 }"
                @click="selectWalletType(0)"
            >Payable</span
            >
        <span
            class="text-normal-bold-md building-badge select-cursor"
            :class="{ active: type === 1 }"
            @click="selectWalletType(1)"
        >Receivable</span
        >
      </div>
    </div>
    <div class="d-flex flex-column align-items-start flex-lg-row mb-32">
      <h3 class="h3 mb-12 me-100 align-self-lg-center">All Accounts</h3>
      <div class="align-self-lg-center mb-12">
        <button
            class="btn bkg-success"
            @click="addNewAccount"
        >
          Add new payable account
        </button>
      </div>
    </div>

    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>Account Data
            </th>
            <th>Type
              <Sorting :sortColumn="'payment_type'"/>
            </th>
            <th>Nick Name
              <Sorting :sortColumn="'nick_name'"/>
            </th>
            <th>Expiration Date
              <Sorting :sortColumn="'card_expiry'"/>
            </th>
            <th>Actions</th>
          </tr>

          <tr class="border_bottom" v-for="wallet in this.dataArray.data">
            <td>
              <div class="d-flex">
                <div class="me-9">
                  <img src="/images/visa.png" class="me-8" alt="card"
                       v-if="wallet.payment_type == 1 && wallet.card_type == 4"/>
                  <img src="/images/master.png" class="me-8" alt="card" v-else-if="wallet.payment_type == 1"/>
                </div>
                <div class="align-self-center text-medium color-secondary">
                  {{ wallet.bnk_routing_no ? wallet.bnk_routing_no : wallet.card_number }}
                </div>
              </div>
            </td>

            <td class="text-medium color-secondary">{{ wallet.payment_type == 1 ? 'Credit Card' : 'Cheque' }}</td>
            <td class="text-medium color-secondary">{{ wallet.nick_name ? wallet.nick_name : '-' }}</td>
            <td>
              <span class="text-medium color-secondary">{{ wallet.card_expiry ? wallet.card_expiry : '-' }}</span>
            </td>
            <td class="d-flex">
              <span class="">
                <img src="/images/icons/edit.png" class="select-cursor" alt="edit" @click="editWallet(wallet)"/>
                <EditAccountModal :ref="'editAccount'+wallet.id" :id="wallet.id"/>
              </span>
              <span class="">
                <img src="/images/icons/trash.png" class="select-cursor" @click="deleteWallet(wallet.id)" alt="trash"/>
              </span>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>

    <EditAccountModal ref="accountModal" :id="0"/>
  </div>
</template>

<script>
import EditAccountModal from "./edit-account-modal.vue";
import EntriesPerPage from "../../entries-per-page.vue";
import {config} from "../../../config";

export default {
  components: {
    EditAccountModal,
    EntriesPerPage,
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      type: null,
    };
  },
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.totalItems = this.$refs.EntriesPerPage.entriesPerPageSelected;
      params.type = this.type;
      params.userId = this.$gate.user.id;

      this.$http
          .post("/getUserWallets", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
    addNewAccount() {
      this.$refs.accountModal.title = 'Add Account';
      this.editMode = false;
      $('#editAccount0').modal('show');
    },
    deleteWallet(id) {
      Swal.fire({
        title: config.confirmBoxTitle,
        text: "Are you sure you want to delete this wallet?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
      }).then((result) => {
        if (result.value) {
          this.showLoader();

          this.$http
              .get("/wallet/" + id + "/delete")
              .then((response) => {
                Toast.fire({
                  icon: response.data.status,
                  title: response.data.message,
                });
                this.loadData();
                this.removeLoader();
              })
              .catch((error) => {
                console.error(error);
              });
        }
      });
    },
    editWallet(wallet) {
      let ref = this.$refs['editAccount' + wallet.id][0];
      ref.editMode = true;
      ref.wallet = wallet;
      ref.form.payment_type = ref.paymentTypeOptions.find(obj => obj.id === wallet.payment_type);
      ref.form.nick_name = wallet.nick_name;
      ref.form.name_on_card = wallet.name_on_card;
      if (wallet.payment_type == 2) {
        ref.form.bnk_routing_no = wallet.bnk_routing_no;
        ref.form.bnk_acc_no = wallet.bnk_acc_no;
        ref.form.check_holder_type = ref.checkHolderTypes.find(obj => obj.id === wallet.check_holder_type);
        ref.form.check_type = ref.accountTypes.find(obj => obj.id === wallet.check_type);
      } else {
        ref.form.card_number = wallet.card_number;
        ref.form.card_cvv = wallet.card_cvv;
        this.setExpiry(wallet, ref);
      }
      $('#editAccount' + wallet.id).modal('show');
    },
    setExpiry(wallet, ref) {
      let expiry = wallet.card_expiry;

      let month = Array.from({length: 12}, (_, index) => index + 1);
      month.unshift('--Select Month--');

      let year = Array.from({length: 21}, (_, index) => {
        const yearValue = new Date().getFullYear() + index;
        return {id: index + 1, name: yearValue.toString()};
      });
      year.unshift({id: '--Select Year--', name: '--Select Year--'});

      let cardExpiry = expiry.split("-");
      cardExpiry[2] = year.findIndex(obj => obj.name == "20" + cardExpiry[1]);
      ref.years = year;

      ref.form.month = ref.months.find(obj => obj.id == cardExpiry[0]);

      ref.form.year = ref.years.find(obj => obj.id == cardExpiry[2]);
    },
    selectWalletType(type) {
      this.type = type;
      this.loadData();
    }
  }
};
</script>
