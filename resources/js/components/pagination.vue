<template>
  <div class="pagination_block pagination d-block d-sm-flex align-items-center" v-if="pagination.currentStart">
    <span class="me-8 text-medium">
      Showing
      {{ pagination.currentEnd > 0 ? pagination.currentStart : "0" }} to
      {{ pagination.currentEnd }} of {{ pagination.total }} entries</span
    >
    <nav
      aria-label="Page navigation example"
      class="mt-11 mt-sm-0 w-75 mx-auto ms-sm-0 me-sm-0"
    >
      <ul class="pagination justify-content-center mb-0">
        <li
          class="page-item"
          :class="{ disabled: pagination.currentPage == 0 }"
        >
          <span
            v-on:click.prevent="loadData(0, pagination.currentPage == 0)"
            class="pagination-btn"
          >
            <img src="/images/icons/left-arrow.png" />
            <img src="/images/icons/left-arrow.png" />
          </span>
        </li>
        <li
          class="page-item"
          :class="{ disabled: pagination.currentPage == 0 }"
        >
          <span
            v-on:click.prevent="
              loadData(pagination.currentPage - 1, pagination.currentPage == 0)
            "
            class="pagination-btn"
          >
            <img src="/images/icons/left-arrow.png" />
          </span>
        </li>
        <li class="page-item" v-if="pagination.currentPage - 1 > 0">
          <span
            v-on:click.prevent="loadData(pagination.currentPage - 2)"
            class="pagination-btn"
          >
            {{ pagination.currentPage - 1 }}
          </span>
        </li>
        <li class="page-item" v-if="pagination.currentPage > 0">
          <span
            v-on:click.prevent="loadData(pagination.currentPage - 1)"
            class="pagination-btn"
          >
            {{ pagination.currentPage }}
          </span>
        </li>
        <li class="page-item">
          <span class="pagination-btn active">
            {{ pagination.currentPage + 1 }}
          </span>
        </li>
        <li
          class="page-item"
          v-if="pagination.currentPage + 1 < pagination.totalPage"
        >
          <span
            v-on:click.prevent="loadData(pagination.currentPage + 1)"
            class="pagination-btn"
          >
            {{ pagination.currentPage + 2 }}
          </span>
        </li>
        <li
          class="page-item"
          v-if="pagination.currentPage + 2 < pagination.totalPage"
        >
          <span
            v-on:click.prevent="loadData(pagination.currentPage + 2)"
            class="pagination-btn"
          >
            {{ pagination.currentPage + 3 }}
          </span>
        </li>
        <li
          class="page-item"
          :class="{
            disabled: pagination.currentPage + 1 === pagination.totalPage,
          }"
        >
          <span
            v-on:click.prevent="
              loadData(
                pagination.currentPage + 1,
                pagination.currentPage + 1 === pagination.totalPage
              )
            "
            class="pagination-btn"
          >
            <img src="/images/icons/right-arrow.png" />
          </span>
        </li>
        <li
          class="page-item"
          :class="{
            disabled: pagination.currentPage + 1 === pagination.totalPage,
          }"
        >
          <span
            v-on:click.prevent="
              loadData(
                pagination.totalPage - 1,
                pagination.currentPage + 1 === pagination.totalPage
              )
            "
            class="pagination-btn"
          >
            <img src="/images/icons/right-arrow.png" />
            <img src="/images/icons/right-arrow.png" />
          </span>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  props: ["pagination","type"],
  methods: {
    loadData(url, disabled) {
      if (!disabled && this.type) this.$parent.loadData(url,this.type);
      else if (!disabled) this.$parent.loadData(url);
    },
  },
  created(){
    console.log(this.type ? 1 : 0,'NULLs')
  }
};
</script>
