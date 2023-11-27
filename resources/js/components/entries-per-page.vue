<template>
  <div class="entries-per-page">
    <div class="row">
      <div class="col-md-12">
        <p class="text-normal-bold-md mb-12 color-secondary">
          Number of entries
        </p>
        <div class="d-flex flex-row flex-wrap mb-28">
          <span
            class="text-normal-bold-md building-badge select-cursor"
            v-for="(info, index) in entriesPerPage"
            :class="{ active: entriesPerPageSelected === index }"
            @click="selectEntries(index)"
            >{{ info }}</span
          >
        </div>
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
      entriesPerPage: {
        10: "10",
        20: "20",
        50: "50",
        100: "100",
        100000000000000000: "All",
      },
      entriesPerPageSelected: "20",
    };
  },
  created() {
    // this.getEntriesPerPage();
  },
  mounted() {},
  methods: {
    selectEntries(index) {
      this.entriesPerPageSelected = index;
      this.$parent.entriesPerPageSelected=index;
      this.$parent.loadData();
    },
    getEntriesPerPage() {
      this.showLoader();
      this.$http
        .get("/getEntriesPerPage")
        .then((response) => {
          this.entriesPerPageSelected = response.data.enrtiesPerPage;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
