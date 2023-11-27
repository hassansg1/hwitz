<template>
  <div class="mb-64">
    <h3 class="h3 mb-28">Telephone access</h3>
    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>Unit No</th>
            <th>DID No</th>
            <th>Usage</th>
            <th>Telephone Access</th>
          </tr>

          <tr class="border_bottom">
            <td>
              <div class="text-medium-bold color-primary" v-if="unit">{{ unit.unit_no }}</div>
            </td>
            <td>
              <div class="text-medium-bold color-primary" v-if="unit">{{ unit.did_number }}</div>
            </td>
            <td>
              <a href="javascript:void(0)" @click="openTelephoneLogsModal()">View</a>
            </td>
            <td>
              <div class="text-medium-bold color-primary" v-if="unit">
                <span v-if="unit.telephone" class="green">
                  On
                </span>
                <span v-else class="ref">
                  Off
                </span>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <telephoneLogsModal ref="telephoneLogsModal"></telephoneLogsModal>
  </div>
</template>

<script>
import telephoneLogsModal from "./telephoneLogsModal.vue";
export default {
  components: {telephoneLogsModal},
  props: ["unit"],
  watch: {},
  data() {
    return {};
  },
  computed: {},
  methods: {
    openTelephoneLogsModal(){
      this.$refs.telephoneLogsModal.unit = this.unit;
      this.$refs.telephoneLogsModal.loadBuildingDetails();
    }
  },
};
</script>
 