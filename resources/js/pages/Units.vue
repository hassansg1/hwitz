<template>
  <div class="rounded-lg pages-style">
    <ul class="nav nav-pills bg-white" id="pills-tab" role="tablist">
      <li class="nav-item me-56 mb-12" role="presentation">
        <div
          class="active"
          id="unit-occupied-tab"
          data-bs-toggle="pill"
          data-bs-target="#unit-occupied"
          type="button"
          role="tab"
          aria-controls="unit-occupied"
          aria-selected="true"
        >
          Occupied
        </div>
      </li>
      <li class="nav-item mb-12" role="presentation">
        <div
          id="unit-unoccupied-tab"
          data-bs-toggle="pill"
          data-bs-target="#unit-unoccupied"
          type="button"
          role="tab"
          aria-controls="unit-unoccupied"
          aria-selected="false"
        >
          Unoccupied
        </div>
      </li>
    </ul>
    <div class="tab-content border-0 bg-white" id="pills-tabContent">
      <div
        class="tab-pane fade show active"
        id="unit-occupied"
        role="tabpanel"
        aria-labelledby="unit-occupied-tab"
      >
        <unitOccupied />
      </div>
      <div
        class="tab-pane fade"
        id="unit-unoccupied"
        role="tabpanel"
        aria-labelledby="unit-unoccupied-tab"
      >
        <unitUnoccupied />
      </div>

      <div
        class="tab-pane fade"
        id="unit"
        role="tabpanel"
        aria-labelledby="unit-tab"
      >
      </div>
    </div>
  </div>
</template>

<script>
import unitOccupied from "../components/units/occupied.vue";
import unitUnoccupied from "../components/units/unoccupied.vue";
import unit from "../components/units/unit.vue";

export default {
  components: {
    unitOccupied,
    unitUnoccupied,
    unit,
  },
  data() {
    return {};
  },
  methods:{
    openUnitTab(unit){
      // this.$refs.unitTab.unit = unit;
      // $('#unit-tab').click();
    }
  }
};
</script>
