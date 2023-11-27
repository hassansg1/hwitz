<template>
  <div class="offering-residents">
    <div class="">
      <div class="row gy-3 mt-10 mb-28">
        <em>* For changes call Tech Support.</em>
        <h3 class="h3">Offerings</h3>
        <h4 class="h4">Paid By Owner</h4>
        <template
            v-for="(offering, index) in offerings.residentIncludedOfferings">
          <div class="col-lg-4 col-md-6">
            <div
                class="card"
                :class="{ active: offering.rid === selectedOffering }"
                @click="selectOption(offering.rid,offering)"
            >
              <h4 class="text-medium-bold mb-12" v-if="offering">{{ offering.name }}
                <span>({{ offering.price }})</span>
              </h4>
              <p class="mb-12 text-normal" v-if="offering">{{ offering.description }}</p>
              <div>
                <a href="#offeringsTable" class="d-inline" v-if="offering.addons_unit">{{ offering.addons_unit.length }}
                  users</a>
              </div>
            </div>
          </div>
        </template>
        <hr>
        <h4 class="h4">Paid by Resident(3rd Party Billing by Urban Sky)</h4>
        <template
            v-for="(offering, index) in offerings.residentPaidOfferings">
          <div class="col-lg-4 col-md-6">
            <div
                class="card"
                :class="{ active: offering.rid === selectedOffering }"
                @click="selectOption(offering.rid,offering)"
            >
              <h4 class="text-medium-bold mb-12" v-if="offering">{{ offering.name }}
                <span>({{ offering.price }})</span>
              </h4>
              <p class="mb-12 text-normal" v-if="offering">{{ offering.description }}</p>
              <div>
                <a href="#offeringsTable" class="d-inline" v-if="offering.addons_unit">{{ offering.addons_unit.length }}
                  users</a>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div class="row gy-3 mt-10 mb-28">
        <h3 class="h3">Resident Add-ons</h3>
        <template
            v-for="(offering, index) in offerings.buildingPackageGroups">
          <template v-if="addon.addonStatus === 'Resident Choice'"
                    v-for="addon in offering.addons">
            <div class="col-lg-4 col-md-6">
              <div
                  class="card"
                  :class="{ active: addon.rid === selectedOffering }"
                  @click="selectOption(addon.rid,addon)"
              >
                <h4 class="text-medium-bold mb-12" v-if="addon && addon.parent">{{ addon.parent.name }}
                  <span>({{ formatMoneyWithCommas(addon.resident_amount) }})</span>
                </h4>
                <p class="mb-12 text-normal" v-if="addon && addon.parent">{{ addon.parent.description }}</p>
                <div>
                  <a href="#offeringsTable" class="d-inline" v-if="addon.addons_unit">{{ addon.addons_unit.length }}
                    users</a>
                </div>
              </div>
            </div>
          </template>
        </template>
      </div>

      <div class="table-responsive" id="offeringsTable">
        <table>
          <tr>
            <th>Name</th>
            <th>Unit</th>
            <th>Date service started</th>
            <th>Month of service</th>
            <th>Cart Type</th>
            <th>Cost</th>
          </tr>

          <tr class="border_bottom" v-for="data in dataArray">
            <td>
              <div class="d-flex" v-if="data.unit.guarantor">
                <div class="me-9">
                  <div class="avatar-sm me-8">
                    <img v-if="data.unit && data.unit.guarantor"
                         :src="data.unit.guarantor && data.unit.guarantor.profile_picture != ''
                             ? data.unit.guarantor : '/images/default-user.jpg'"
                         class="avatar-img"
                    />
                  </div>
                </div>
                <div class="align-self-center">
                  <p class="text-medium-bold mb-0">{{ data.unit.guarantor.name }}</p>
                  <a href="" class="a-link">{{ data.unit.guarantor.mobile }}</a>
                </div>
              </div>
              <div v-else>-</div>
            </td>
            <td class="text-medium color-secondary" v-if="data.unit">{{ data.unit.unit_no }}</td>
            <td class="text-medium color-secondary">
              <span v-if="data.service_start_date">{{ formatDate(data.service_start_date) }}</span>
              <span v-else>-</span>
            </td>
            <td></td>
            <td class="text-medium color-secondary">{{ data.cart_label }}</td>
            <td class="text-medium color-secondary" v-if="data.amount">{{ formatMoneyWithCommas(data.amount) }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedOffering: 0,
      offerings: [],
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
    };
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      this.loadResidentOfferings();
      this.dataArray = {};
    }
  },
  methods: {
    loadResidentOfferings() {
      let buildingId = this.building_id;
      if (buildingId) {
        let params = {buildingId: buildingId, offering: "resident"};
        this.showLoader();
        this.$http
            .post("loadResidentOfferings", params)
            .then((response) => {
              response = response.data;
              if (response.status)
                this.offerings = response;
              else
                Toast.fire({icon: 'warning', title: response.message});
              this.removeLoader();
            })
            .catch((error) => {
              this.removeLoader();
              console.error(error);
            });
      }
    },
    selectOption(index, data) {
      console.log(index);
      this.selectedOffering = index;
      this.dataArray = data.addons_unit;
    },
  },
};
</script>
