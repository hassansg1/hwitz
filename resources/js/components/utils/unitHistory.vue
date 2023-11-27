<template>
    <div class="storage-section">
      <div
        class="modal fade"
        :id="'unitHistoryModal'+id"
        tabindex="-1"
        aria-labelledby="storageModal"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
                <div class="abc" style="text-align:left !important">
                    <unitAnalytics ref="unitAnalytics" :fromParking=true />
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import unitAnalytics from "../analytics/admin/unit-analytics.vue"
  export default {
    
    components: {
        unitAnalytics,
    },
    props : ["id"],
    data() {
      return {
        buildingIndex : '',
      };
    },
    methods: {
        loadData(id){
            this.$refs.unitAnalytics.unit_id = id;
            this.$refs.unitAnalytics.loadData();
        }
    },
  };
  </script>
  