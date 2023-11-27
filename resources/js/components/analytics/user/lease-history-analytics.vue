<template>
  <div>
    <div class="row">
      <div class="mb-66">
        <div id="lease_history_div" class="table-responsive mb-28">
          <table>
            <thead>
            <tr>
              <th>Primary User Name
                <Sorting :sortColumn="'user_id'"/>
              </th>
              <th>Move in Date
                <Sorting :sortColumn="'move_time'"/>
              </th>
              <th>Move out Date
                <Sorting :sortColumn="'move_out_time'"/>
              </th>
              <th>Status
                <Sorting :sortColumn="'status'"/>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="data in dataArray.data" class="border_bottom">
              <td class="text-medium">
                <span v-if="data.user">{{ data.user.name }}</span>
              </td>
              <td class="text-medium">
                {{ data.move_time ? formatDateTime(data.move_time) : '-' }}
              </td>
              <td class="text-medium">
                {{ data.move_out_time ? formatDateTime(data.move_out_time) : '-' }}
              </td>
              <td class="text-medium">
                {{ data.status === 1 ? 'Active' : 'Inactive' }}
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <span>
          <pagination :pagination="dataArray"></pagination>
        </span>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  created() {
    if(this.unitId)
      this.loadData();
  },
  props: ["unitId"],
  data() {
    return {
      dataArray: {},
      sortColumn: null,
      sortOrder: null,
      pageNumber: 0,
      entriesPerPageSelected: 0,
    };
  },
  expose: ["loadData"],
  methods: {
    loadData(pageNumber = null) {
      if (pageNumber === null) pageNumber = 0;
      this.pageNumber = pageNumber;

      this.showLoader();
      let params = this.$parent.params ?? {};
      params.unitId = this.unitId;
      params.current_page = pageNumber;
      params.sortOrder = this.sortOrder;
      params.sortBy = this.sortColumn;

      this.$http
          .post("/leaseHistory", params)
          .then((response) => {
            this.dataArray = response.data;
            console.log(response.data);
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
