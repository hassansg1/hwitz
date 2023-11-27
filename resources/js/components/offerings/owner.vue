<template>
  <div class="offering-residents">
    <div class="">
      <div class="row gy-3 mt-10 mb-28">
        <em>* For changes call Tech Support.</em>
        <h3 class="h3">Offerings</h3>
        <h4 class="h4">Included in base package</h4>
        <template
            v-for="(offering, index) in offerings.ownerIncludedOfferings">
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
        <h4 class="h4">Paid</h4>
        <template
            v-for="(offering, index) in offerings.ownerPaidOfferings">
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
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedOffering: 0,
      offerings: [],
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
      if (newBuildingId !== oldBuildingId && oldBuildingId !== 0)
        this.loadResidentOfferings();
    }
  },
  methods: {
    loadResidentOfferings() {
      let buildingId = this.building_id;
      if (buildingId) {
        let params = {buildingId: buildingId, offering: "owner"};
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
    },
  },
};
</script>
