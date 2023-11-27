<template>
  <div style="display: inline" class="sorting">
    <i
      title="Ascending Order"
      :class="{ active_sort: sortOrder === 'asc' }"
      class="fa-solid fa-arrow-up sort_icons"
      v-on:click="loadData(sortColumn, 'asc')"
    ></i>

    <i
      title="Descending Order"
      :class="{ active_sort: sortOrder === 'desc' }"
      class="fa-solid fa-arrow-down sort_icons"
      v-on:click="loadData(sortColumn, 'desc')"
    ></i>
  </div>
</template>

<script>
export default {
  props: ["sortColumn"],
  data() {
    return {
      sortOrder: null,
    };
  },
  methods: {
    loadData(sortColumn, sortOrder) {
      $(".sort_icons").removeClass("active_sort");
      this.sortOrder = sortOrder;
      this.$parent.sortColumn = sortColumn;
      this.$parent.sortOrder = sortOrder;
      console.log(sortColumn, sortOrder);
      this.$parent.loadData();
    },
  },
};
</script>
