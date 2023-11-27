<template>
  <div class="d-flex text-normal color-secondary mb-12">
    <div class="align-self-center me-12">Entries per page:</div>
    <select name="" id="" class="mySelectArrow" v-model="value" @change="loadData">
      <option value="10">10</option>
      <option value="20">20</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </div>
</template>

<script>
export default {
  components: {},
  props: [],
  data() {
    return {
      value: ''
    };
  },
  methods: {
    loadData() {
      this.$parent.entriesPerPageSelected = this.value;
      this.$parent.loadData(this.$parent.pageNumber)
    }
  },
};
</script>
  