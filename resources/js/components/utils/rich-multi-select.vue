<template>
  <div>
    <p class="text-normal-bold-md color-secondary">{{ header }}</p>
    <div
      class="d-flex border-standered flex-row flex-wrap align-items-center card-style"
      :class="{
        active: isShow,
      }"
      @click="toggledropdown()"
    >
      <div class="text-normal-bold-lg" v-if="selectedArray.length === 0">
        No recepent selected
      </div>
      <div
        class="d-flex align-self-center my-1 select-cursor"
        v-for="selected in selectedArray"
        :key="selected.id"
        @click="removeSelected(selected)"
      >
        <div class="align-self-center me-8">
          <img :src="selected.image" class="avatar-xsm" alt="" />
        </div>
        <p class="align-self-center text-normal-bold-lg mb-0 text-capitalize">
          {{ selected.name }}
        </p>
      </div>
    </div>

    <div id="card" class="card dropdown-card" v-if="isShow">
      <p class="text-normal-bold-md color-secondary-light">{{ header }}</p>

      <div
        class="d-flex list-recipents select-cursor"
        :class="{
          'bkg-secondary': selectedArray.includes(resp),
        }"
        v-for="resp in optionsArray"
        :key="resp.id"
        @click="selectData(resp)"
      >
        <div class="d-flex align-self-center">
          <div class="align-self-center me-8">
            <img :src="resp.image" class="avatar-xsm" alt="" />
          </div>
          <p class="align-self-center text-normal-bold-lg mb-0 text-capitalize">
            {{ resp.name }}
          </p>
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
