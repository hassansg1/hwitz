<template>
  <div class="pages-style">
    <ul class="nav nav-pills mb-64" id="pills-tab" role="tablist">
      <li
          v-if="$gate.permissions.includes('summary')"
          class="nav-item me-49 mb-12" role="presentation">
        <div
            id="pills-summary-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-summary"
            type="button"
            role="tab"
            aria-controls="pills-summary"
            aria-selected="true"
            @click="loadSummarySection"
            class="acl_pill"
        >
          Summary
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('accounts')" class="nav-item me-49 mb-12" role="presentation">
        <div
            v-if="$gate.permissions.includes('accounts')"
            id="pills-accounts-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-accounts"
            type="button"
            role="tab"
            aria-controls="pills-accounts"
            aria-selected="false"
            @click="loadAccountsSection"
            class="acl_pill"
        >
          Accounts
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('wallet_assignment')" class="nav-item me-49 mb-12" role="presentation">
        <div
            id="pills-wallet-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-wallet"
            type="button"
            role="tab"
            aria-controls="pills-wallet"
            aria-selected="false"
            @click="loadWalletAssignmentSection"
            class="acl_pill"
        >
          Wallet Assignment
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('ach_status')" class="nav-item me-49 mb-12" role="presentation">
        <div
            id="pills-ach-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-ach"
            type="button"
            role="tab"
            aria-controls="pills-ach"
            aria-selected="false"
            @click="loadAchData()"
            class="acl_pill"
        >
          ACH Status
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('receivables')" class="nav-item me-49 mb-12" role="presentation">
        <div

            id="pills-receiveables-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-receiveables"
            type="button"
            role="tab"
            aria-controls="pills-receiveables"
            aria-selected="false"
            @click="loadReceivablesData()"
            class="acl_pill"
        >
          Receivables
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('payable')" class="nav-item me-49 mb-12" role="presentation">
        <div
            id="pills-payables-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-payables"
            type="button"
            role="tab"
            aria-controls="pills-payables"
            aria-selected="false"
            @click="loadPayables()"
            class="acl_pill"
        >
          Payables
        </div>
      </li>
      <li
          v-if="$gate.permissions.includes('owner_order_summary')" class="nav-item mb-12" role="presentation">
        <div
            id="pills-ownerorder-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-ownerorder"
            type="button"
            role="tab"
            aria-controls="pills-ownerorder"
            aria-selected="false"
            @click="loadOwnerOrderSummary()"
            class="acl_pill"
        >
          Owner Order Summary
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
          v-if="$gate.permissions.includes('summary')" class="tab-pane fade"
          id="pills-summary"
          role="tabpanel"
          aria-labelledby="pills-summary-tab"
      >
        <Summary ref="summary"/>
      </div>
      <div
          v-if="$gate.permissions.includes('accounts')" class="tab-pane fade"
          id="pills-accounts"
          role="tabpanel"
          aria-labelledby="pills-accounts-tab"
      >
        <Account ref="accounts"/>
      </div>
      <div
          v-if="$gate.permissions.includes('wallet_assignment')" class="tab-pane fade"
          id="pills-wallet"
          role="tabpanel"
          aria-labelledby="pills-wallet-tab"
      >
        <WalletAssignment ref="walletAssignment"/>
      </div>
      <div
          v-if="$gate.permissions.includes('ach_status')" class="tab-pane fade"
          id="pills-ach"
          role="tabpanel"
          aria-labelledby="pills-ach-tab"
      >
        <ACH ref="ach"/>
      </div>
      <div
          v-if="$gate.permissions.includes('receivables')" class="tab-pane fade"
          id="pills-receiveables"
          role="tabpanel"
          aria-labelledby="pills-receiveables-tab"
      >
        <Receivable ref="receivables"/>
      </div>
      <div
          v-if="$gate.permissions.includes('payable')" class="tab-pane fade"
          id="pills-payables"
          role="tabpanel"
          aria-labelledby="pills-payables-tab"
      >
        <Payables ref="payables"/>
      </div>
      <div
          v-if="$gate.permissions.includes('owner_order_summary')" class="tab-pane fade"
          id="pills-ownerorder"
          role="tabpanel"
          aria-labelledby="pills-ownerorder-tab"
      >
        <OwnerOrderSummary ref="OwnerOrderSummary"/>
      </div>
    </div>
  </div>
</template>

<script>
import Summary from "../components/financial/summary/summary.vue";
import Account from "../components/financial/accounts/account.vue";
import Receivable from "../components/financial/receivables/receivable.vue";
import History from "../components/financial/history/history.vue";
import Payables from "../components/financial/payables/payable.vue";
import ACH from "../components/financial/ach/ach-status.vue";
import WalletAssignment from "../components/financial/wallet/wallet-assignment.vue";
import OwnerOrderSummary from "../components/financial/ownerOrderSummary/summary.vue";

export default {
  components: {
    Summary,
    Account,
    WalletAssignment,
    ACH,
    Receivable,
    History,
    Payables,
    OwnerOrderSummary,
  },
  mounted() {
    $('.acl_pill').each(function (index) {
      if (index === 0)
        console.log(index + ": " + $(this).text());
    });
  },
  data() {
    return {
      historyTab: false
    };
  },
  methods: {
    loadWalletAssignmentSection() {
      this.$refs.walletAssignment.getPayableWallets();
      this.$refs.walletAssignment.getReceivableWallets();
    },
    loadSummarySection() {
      this.$refs.summary.loadData();
    },
    loadOwnerOrderSummary() {
      this.$refs.OwnerOrderSummary.getBuildings();
    },
    loadAccountsSection() {
      this.$refs.accounts.loadData();
    },
    loadPayables() {
      this.$refs.payables.getBuildings();
    },
    loadAchData() {
      this.$refs.ach.getBuildings();
    },
    loadReceivablesData() {
      this.$refs.receivables.getBuildings();
    },
    loadHistoryTab() {
      this.historyTab = true;
    }
  }
};
</script>
