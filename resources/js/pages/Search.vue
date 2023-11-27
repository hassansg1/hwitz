<template>
  <div class="rounded-lg pages-style">
    <ul
        class="nav nav-pills d-flex mb-35 bg-white"
        id="pills-tab"
        role="tablist"
    >
      <li
          class="nav-item me-44"
          role="presentation"
          v-for="(tab, index) of tabs"
          :key="tab.id"
      >
        <div
            :class="{ active: index === 0 }"
            :id="tab.tabId + '-tab'"
            data-bs-toggle="pill"
            :data-bs-target="'#all'"
            type="button"
            role="tab"
            :aria-controls="tab.tabId"
            aria-selected="true"
            @click="toggleBtn(tab.tabId)"
        >
          {{ tab.tab }}
        </div>
      </li>
    </ul>
    <div class="tab-content bg-white" id="pills-tabContent">
      <div
          class="tab-pane fade show active"
          id="all"
          role="tabpanel"
          aria-labelledby="all-tab"
      >
        <All
            ref="allComponent"
            :category="this.category"
            :usertypes="this.usertypes"
            :data="this.allData"
            :keyword="keyword"
        />
      </div>
    </div>
    <div class="" v-if="this.category != 'all'">
      <pagination :pagination="pagination"></pagination>
    </div>
    <!-- <div class="d-flex justify-content-center" v-else>
      <p @click="this.viewAll" v-if="totalItems === 10" style="cursor: pointer" class="text-medium-md color-primary view-all">View all results</p>
      <p @click="this.viewLess" v-else style="cursor: pointer" class="text-medium-md color-primary view-all">View less results</p>
    </div> -->
  </div>
</template>

<script>
import All from "../components/search/all.vue";

export default {
  components: {
    All,
  },
  created() {
    this.getSearchData();
    this.getUserTypes();
  },
  beforeRouteUpdate(to, from, next) {
    this.getSearchData(to.query.q);
    next();
  },
  data() {
    return {
      allData: [],
      keyword: "",
      category: "all",
      usertypes: {},
      usertypeid: "",
      totalItems: 10,
      tabs: [
        {
          id: 1,
          tab: "All",
          tabId: "all",
        },
        {
          id: 2,
          tab: "Users",
          tabId: "users",
        },
        {
          id: 4,
          tab: "Documents",
          tabId: "documents",
        },
        {
          id: 5,
          tab: "Chats",
          tabId: "chats",
        },
      ],

      previousData: '',

      // pagination start
      pagination: '',
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: null,
      search: '',
      params: {},

      //pagination end
    };
  },
  methods: {
    loadData(pageNumber = null) {
      this.refreshData(pageNumber);
    },
    toggleBtn(category) {
      this.category = category;
      this.refreshData();
    },
    viewAll() {
      this.totalItems = 100000;
      this.refreshData();
    },
    viewLess() {
      this.totalItems = 10;
      this.refreshData();
    },
    getSearchData(searchKeyword = null) {
      if (searchKeyword) this.keyword = searchKeyword;
      else this.keyword = this.$route.query.q;
      this.refreshData();
    },
    refreshData(pageNumber = null, type = null) {
      if (this.keyword !== "") {
        this.showLoader();
        // let params = {
        //   searchKeyword: this.keyword,
        //   totalItems: this.totalItems,
        //   category: this.category,
        //   usertypeid: this.usertypeid,
        // };

        if (pageNumber === null) pageNumber = 0;
        this.pageNumber = pageNumber;

        this.params = {};
        this.params.searchKeyword = this.keyword;
        this.params.totalItems = this.entriesPerPageSelected;
        this.params.currentPage = this.pageNumber;
        this.params.sortOrder = this.sortOrder;
        this.params.sortBy = this.sortColumn;
        if (this.$refs.allComponent && this.$refs.allComponent.buildingIndex) {
          this.params.buildingId = this.$refs.allComponent.buildingIndex.id;
        }
        if (this.$refs.allComponent && this.$refs.allComponent.unitIndex) {
          this.params.unitId = this.$refs.allComponent.unitIndex.id;
        }
        if (this.$refs.allComponent && this.$refs.allComponent.usertypeIndex) {
          this.params.usertypeid = this.$refs.allComponent.usertypeIndex.id;
        }
        if (this.$refs.allComponent && this.$refs.allComponent.userIndex) {
          this.params.userId = this.$refs.allComponent.userIndex.id;
        }
        if (type) {
          this.params.category = type;
          this.previousData = this.allData;
        } else {
          this.params.category = this.category;
        }

        this.$http
            .post("/search", this.params)
            .then((response) => {


              this.allData = response.data;
              if (this.category == 'users') {
                this.pagination = response.data.users;
              } else if (this.category == 'documents') {
                this.pagination = response.data.documents;
              } else if (this.category == 'chats') {
                this.pagination = response.data.chats;
              } else {
                this.$refs.allComponent.usersPagination = response.data.users;
                this.$refs.allComponent.chatsPagination = response.data.chats;
                this.$refs.allComponent.documentsPagination = response.data.documents;
              }
              if (type) {

                if (type == 'users') this.previousData.users = this.allData.users;
                else if (type == 'chats') this.previousData.chats = this.allData.chats;
                else if (type == 'documents') this.previousData.documents = this.allData.documents;

                this.$refs.allComponent.refreshData(this.previousData);

              } else {
                this.$refs.allComponent.refreshData(this.allData);
              }
              this.$nextTick(() => {
                this.removeLoader();
              });
            })
            .catch((error) => {
              console.error(error);
            });
      }
    },
    getUserTypes() {
      this.showLoader();
      this.$http
          .get("/getBuildingsUserTypes")
          .then((response) => {
            this.usertypes = response.data.usertypes;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
  },
  beforeDestroy() {
    this.$parent.searchData = '';
  }
};
</script>
