<template>
  <div class="multiselect">
    <div class="card border-standered">
      <div class="card-body">
        <div>
          <div
            class="text-normal-bold-md color-secondary-light"
            data-bs-toggle="collapse"
            :data-bs-target="'#' + cardId"
            aria-expanded="false"
            :aria-controls="cardId"
          >
            {{ title }}
          </div>
          <div class="text-normal-bold-lg" v-if="selectedArray.length === 0">
            No option selected
          </div>
          <div v-else>
            <span
              v-for="(building, index) in selectedArray"
              :key="building"
              @click="removeFromArray(building)"
              class="select-cursor text-normal-bold-lg color-dark me-2"
              >{{ building }}

              <span v-if="index !== selectedArray.length - 1">,</span>
            </span>
          </div>
        </div>

        <div :id="cardId">
          <hr class="color-secondary-light" />
          <p
            class="select-cursor mb-2 px-3 py-1 text-sm-bold color-secondary"
            :class="{
              'bg-gray-light color-dark border-sm':
                selectedArray.includes(option),
            }"
            v-for="option in optionsArray"
            :key="option"
            @click="selectBuilding(option)"
          >
            {{ option }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["optionsArray", "title", "cardId"],
  data() {
    return {
      selectedArray: [],
    };
  },
  methods: {
    selectBuilding(option) {
      if (!this.selectedArray.includes(option)) {
        this.selectedArray.push(option);
      }
    },
    removeFromArray(item) {
      const index = this.selectedArray.indexOf(item);
      if (index > -1) {
        this.selectedArray.splice(index, 1);
      }
    },
  },
};
</script>
