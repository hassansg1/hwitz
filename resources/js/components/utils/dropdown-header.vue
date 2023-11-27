<template>
  <div>
    <p class="header">{{ header }}</p>
    <div
      class="card"
      :class="{
        active: isShow,
      }"
      @click="toggledropdown()"
    >
      <div class="d-flex flex-row flex-wrap align-items-center">
        <div
          class="text-normal-bold-lg color-secondary"
          v-if="selectedData === ''"
        >
          No data selected
        </div>
        <div v-else class="d-flex align-self-center select-cursor">
          <p class="align-self-center text-normal-bold-lg mb-0 text-capitalize">
            {{ selectedData }}
          </p>
        </div>
      </div>
      <div v-if="isShow">
        <div
          class="d-flex list-recipents select-cursor"
          :class="{
            'bkg-secondary': selectedData === resp.value,
          }"
          v-for="resp in optionsArray"
          :key="resp.id"
          @click="selectData(resp)"
        >
          <div class="d-flex align-self-center">
            <p
              class="align-self-center text-normal-bold-lg mb-0 text-capitalize"
            >
              {{ resp.value }}
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
      selectedData: "",
      dataArray: [
        { id: 1, date: "john doe", image: "/images/worker.png" },
        { id: 2, date: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, date: "jane doe 2", image: "/images/Ellipse14.png" },
      ],
    };
  },
  methods: {
    selectData(option) {
      this.selectedData = option.value;
      // if (!this.selectedArray.includes(option)) {
      //   this.selectedArray.push(option);
      // }
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

<!-- <style scoped>
.text-medium {
  margin-bottom: 28px;
}

.text-normal-bold-lg {
  margin-right: 16px;
}

.text-normal-bold-md {
  margin-bottom: 12px;
}
#card .text-normal-bold-md {
  margin-top: 20px;
  margin-bottom: 8px;
}

.card-style {
  min-height: 48px;
}

.border-standered {
  border: 1px solid #cccccc;
  border-radius: 10px;
  padding: 2px 16px;
  margin-bottom: 12px;
}
.header {
  font-style: normal;
  font-weight: 600;
  font-size: 12px;
  line-height: 15px;
  color: rgba(78, 78, 78, 0.4);
  margin-bottom: 10px;
}
.active {
  border: 3px solid #47c5fe !important;
}

.card {
  min-height: 48px;
  display: flex;
  justify-content: center;
  padding: 9px 10px;
  border-radius: 13px;
  border: 1px solid #999999;
}

/* scroll bar width */
/* width */
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 13px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 13px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style> -->
