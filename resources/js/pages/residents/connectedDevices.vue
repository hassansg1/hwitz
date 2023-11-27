<template>
  <div class="mb-64">
    <h3 class="h3 mb-28">Connected Devices</h3>
    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>Id</th>
            <th>Type</th>
            <th>Device</th>
            <th>Location</th>
          </tr>

          <tr class="border_bottom" v-for="mac in macs">
            <td>
              <div class="text-medium-bold color-primary">{{ mac.mac_address ? mac.mac_address : '-' }}</div>
            </td>
            <td>
              <div class="d-flex">
                <div class="align-self-center text-medium color-secondary mt-6 me-12">
                  {{ mac.connection_type ? mac.connection_type : '-' }}
                </div>
                <div class="align-self-center">
                  <img src="/images/icons/yellow_warning.png" v-if="mac.is_radius == 0" title="Disabled" class="me-12"
                       alt=""/>
                  <img src="/images/icons/right_small.png" v-else-if="mac.is_radius == 1" title="Enabled" class="me-12"
                       alt=""/>
                  <img src="/images/icons/warning.png" v-else class="me-12" title="Missing Entry" alt=""/>
                </div>
              </div>
            </td>
            <td>
              <span class="text-medium color-secondary">{{ mac.device_type ? mac.device_type : '-' }}
              <span v-if="mac.device_type === 'Other'"> - {{ mac.description }}</span>
              </span>
            </td>
            <td>
              <span class="text-medium color-secondary">{{ mac.location ? mac.location : '-' }}</span>
            </td>
          </tr>
          <tr v-if="!macs || macs.length == 0">
            <td class="text-center" colspan="4">No Data found.</td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="d-flex justify-content-end">
                <button class="btn btn-table bkg-primary text-white" @click="addNewMacmodal">
                  + Assign new Devices
                </button>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <addNewMacModal ref="addNewMac"/>
  </div>
</template>

<script>


import assignNewFobPopup from "./assignNewFobModal.vue";
import addNewMacModal from "./addNewMacModal.vue";

export default {
  components: {
    assignNewFobPopup,
    addNewMacModal,
  },
  props: ["macs", "user", "building_id", "unit_id"],
  watch: {
    // building_id: function (newBuildingId, oldBuildingId) {
    //     this.loadData();
    // }
  },
  data() {
    return {};
  },
  computed: {},
  methods: {
    addNewMacmodal() {
      this.$refs.addNewMac.device_type = '';
      this.$refs.addNewMac.location = '';
      this.$refs.addNewMac.unit = '';
      this.$refs.addNewMac.connection_type = '';
      if (this.user) {
        this.$refs.addNewMac.units = this.user.units;
        this.$refs.addNewMac.form.user_id = this.user.id;
      } else {
        this.$refs.addNewMac.units = [];
        this.$refs.addNewMac.unit_id = this.unit_id;
        this.$refs.addNewMac.form.user_id = null;
      }
      this.$refs.addNewMac.form.building_id = this.building_id;
      $('#addNewMacmodal').modal('show');
    },
    reloadData() {
      this.$parent.reloadData();
    },

  },
};
</script>
