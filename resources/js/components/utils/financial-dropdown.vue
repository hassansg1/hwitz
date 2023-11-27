<template>
  <div>
    <div
      class="card dropdown-list position-relative"
      :class="{ optionActive: isShowDropdown }"
      @click="toggleDropdown()"
    >
      <p class="mb-0 text-normal-bold-md color-secondary-light">{{ header }}</p>
      <p class="mb-0 text-sm-bold">{{ selectedBuilding.name }}</p>

      <div v-if="isShowDropdown" class="building-dropdown">
        <hr class="mt-0" />
        <p
          class="mb-10 text-sm-bold select-cursor"
          v-for="(building, index) in optionsArray"
          @click="selectBuilding(index)"
        >
          {{ building.name }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["optionsArray", "header"],
  data() {
    return {
      isShowDropdown: false,
      selectedBuilding: "",
    };
  },
  methods: {
    toggleDropdown() {
      this.isShowDropdown = !this.isShowDropdown;
    },
    selectBuilding(index) {
      this.selectedBuilding = this.optionsArray[index];
      this.$emit("selectItem", this.selectedBuilding);
    },
  },
};
</script>
