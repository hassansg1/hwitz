<template>
  <div>
    <div
      class="card"
      :class="{
        active: isShow,
      }"
      @click="toggledropdown()"
    >
      <p class="text-normal-bold-md color-secondary mb-12 text-uppercase">
        {{ header }}
      </p>

      <div class="d-flex flex-row flex-wrap align-items-center">
        <div
          class="text-normal-bold-lg color-secondary me-16"
          v-if="selectedArray.length === 0"
        >
          No {{ header }} selected
        </div>
        <div
          class="d-flex align-self-center select-cursor"
          v-for="selected in selectedArray"
          :key="selected.id"
          @click="removeSelected(selected)"
        >
          <p class="align-self-center text-normal-bold-lg mb-0 text-capitalize">
            {{ selected.name }}
          </p>
        </div>
      </div>

      <div v-if="isShow">
        <div
          class="d-flex list-recipents select-cursor mt-6"
          :class="{
            'bkg-secondary': selectedArray.includes(resp),
          }"
          v-for="resp in optionsArray"
          :key="resp.id"
          @click="selectData(resp)"
        >
          <div class="d-flex align-self-center">
            <p
              class="align-self-center text-normal-bold-lg mb-0 text-capitalize"
            >
              {{ resp.name }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["optionsArray", "header"],
  data() {
    return {
      isShow: false,
      selectedArray: [],
      dataArray: [
        { id: 1, name: "john doe", image: "/images/worker.png" },
        { id: 2, name: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, name: "jane doe 2", image: "/images/Ellipse14.png" },
      ],
    };
  },
  methods: {
    selectData(option) {
      if (!this.selectedArray.includes(option)) {
        this.selectedArray.push(option);
      }
    },
    removeSelected(item) {
      const index = this.selectedArray.indexOf(item);
      if (index > -1) {
        this.selectedArray.splice(index, 1);
      }
    },

    toggledropdown() {
      this.isShow = !this.isShow;
    },
  },
};
</script>
