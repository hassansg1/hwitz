<template>
  <div class="entries-per-page">
    <div class="row gy-3 mb-28">
      <div class="col-lg-3">
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="searchKeyword"
            @input="debouncedSearch()"
            @change="loadData()"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Admin",
  components: {},

  data() {
    return {
      searchKeyword: '',
      searchKeywordPrev: '',
    };
  },
  created() {
  },
  mounted() {
  },
  methods: {
    debouncedSearch() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout((e) => this.loadData(), 2000);
    },
    loadData() {
      if (this.searchKeyword !== this.searchKeywordPrev)
        this.$parent.loadData();
      this.searchKeywordPrev = this.searchKeyword;
    },
  },
};
</script>
