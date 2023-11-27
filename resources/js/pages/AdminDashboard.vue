<template>
  <div>
    <div class="row gy-3 mb-28">
      <div class="col-lg-3">
        <input
            type="text"
            class="form-control"
            placeholder="Search by keyword"
            v-model="searchKeyword"
            @change="loadData()"
        />
      </div>
    </div>
    <div class="row mb-28">
      <div class="col-8">
        <h3 class="h3 mb-0">List of Owners</h3>
      </div>
    </div>
    <div class="mb-66">
      <div class="table-responsive mb-28">
        <table>
          <tr>
            <th>
              Name
              <Sorting :sortColumn="'name'"/>
            </th>
            <th>Mobile
              <Sorting :sortColumn="'mobile'"/>
            </th>
            <th>Mobile Verified
              <Sorting :sortColumn="'mobile_verification'"/>
            </th>
            <th>Email
              <Sorting :sortColumn="'email'"/>
            </th>
            <th>Email Verified
              <Sorting :sortColumn="'email_verified'"/>
            </th>
            <th>Status
              <Sorting :sortColumn="'status'"/>
            </th>
            <th>Action
            </th>
          </tr>
          <tr v-for="data in dataArray.data" class="border_bottom">
            <td>
              <span class="text-medium">{{ data.name ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium">{{ data.mobile ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.mobile_verification === 'No' ? 'red' : '']">
                <span v-if="data.mobile_verification === 'No'">Not Verified</span>
                <span v-else>Verified</span>
              </span>
            </td>
            <td>
              <span class="text-medium">{{ data.email ?? '' }}</span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.email_verified === '0' ? 'red' : '']">
                <span v-if="data.email_verified === '0'">Not Verified</span>
                <span v-else>Verified</span>
              </span>
            </td>
            <td>
              <span class="text-medium green" :class="[data.status === '0' ? 'red' : '']">
                <span v-if="data.status === '0'">Inactive</span>
                <span v-else>Active</span>
              </span>
            </td>
            <td>
              <a :href="'login_as/'+data.id" class="btn btn-primary">Login as</a>
            </td>
          </tr>
        </table>
      </div>
      <span>
            <pagination :pagination=" dataArray"></pagination>
      </span>
    </div>
  </div>
</template>

<script>

import Vue from "vue";
import Sorting from "../components/sorting.vue";

export default {
  components: {
    Sorting,
  },
  created() {
    this.loadData();
  },
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      searchKeyword: '',
    };
  },
  expose: ['loadData'],
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = {};
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;
      params.searchKeyword = this.searchKeyword;


      this.$http
          .post("/owners", params)
          .then((response) => {
            this.dataArray = response.data;
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
  },

};
</script>

