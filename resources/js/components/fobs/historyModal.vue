<template>
    <div>
      <div
        class="modal fade"
        :id="'fobHistoryModal'"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <p class="mb-8 text-medium-bold text-uppercase color-secondary mt-16">{{title}}</p>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start ">
                <div class="table-responsive mb-20">
                    <table>
                    <tr>
                        <th>#</th>
                        <th>Building <Sorting :sortColumn="'name'" /></th>
                        <th>Unit No. <Sorting :sortColumn="'unit_no'" /></th>
                        <th>Resident <Sorting :sortColumn="'Resident_firstname'" /></th>
                        <th>Action <Sorting :sortColumn="'action'" /></th>
                        <th>Action Date <Sorting :sortColumn="'action_date'" /></th>
                        <th>Performed By <Sorting :sortColumn="'timestamp'" /></th>
                    </tr>
                    <tr class="border_bottom text-medium color-secondary" v-for="(log,index) in data" :key="index">
                        <td class="h4">{{index+1}}</td>
                        <td>{{log.name}}</td>
                        <td>{{log.unit_no}}</td>
                        <td>{{log.Resident_firstname ? log.Resident_firstname + ' ' + log.Resident_lastname : '-'}}</td>
                        <td>{{actionName(log)}}</td>
                        <td>{{formatDateTime(action_date)}}</td>
                        <td>{{log.PerformedBy_firstname ? log.PerformedBy_firstname + ' ' + log.PerformedBy_lastname : '-'}}</td>
                    </tr>
                    </table>
                </div>
                <span v-if="data.length > 0"><pagination :pagination="pagination"></pagination></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    components: {
        
    },
    props: [],
    data() {
      return {
        title : '',
        id : '',
        data : '',
        building_id : '',
        // pagination start
        pagination : '',
        sortColumn: null,
        sortOrder: null,
        pageNumber: 0,
        entriesPerPageSelected: null,
        params : {},

        //pagination end
      };
    },
    methods: {
        loadData(pageNumber = null){


            this.showLoader();

            if (pageNumber === null) pageNumber = 0;
            this.pageNumber = pageNumber;

            this.params = {};
            this.params.building_id = this.building_id;
            this.params.totalItems = this.entriesPerPageSelected;
            this.params.current_page = this.pageNumber;
            this.params.sortOrder = this.sortOrder;
            this.params.sortBy = this.sortColumn;

            this.$http
            .post("/get_fob_history/"+this.id , this.params)
            .then((response) => {
                this.data = response.data.data;
                this.pagination = response.data;
                this.removeLoader();
            })
            .catch((error) => {
                console.error(error);
            });
            
        },
        actionName(history){
          const cardId = history.card_id;
          const action = history.action;

          const replacedAction = action.replace(new RegExp(`${cardId}|mFOB|FOB`, 'g'), '');

          return replacedAction;

        }
    },
  };
  </script>
  
  <style scoped>
  textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #cccccc;
    border-radius: 10px;
    resize: none;
  }
  
  /* textarea:focus {
        outline: none !important;
        border: 1px solid #47c5fe !important;
      } */
  
  .comments {
    border-radius: 13px;
    border: 1px solid rgba(0, 0, 0, 0.2);
    padding: 10px 16px;
  }
  
  textarea {
    width: 100% !important;
    resize: none;
  }
  
  textarea:focus-visible {
    border: 1px solid #47c5fe;
    outline: none;
  }
  
  .btn-attachment {
    height: 69px;
    width: 76px;
    border: 1px dashed #47c5fe;
    border-radius: 13px;
  }
  
  .btn-attachment-icon {
    height: 26px;
    width: 26px;
    border-radius: 50%;
    background: #47c5fe;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .form-control {
    padding: 2px 10px;
    height: 48px;
  }
  
  .img-name {
    border-radius: 13px;
    padding: 8px 16px;
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 18px;
  }
  
  .date-sec {
    border-radius: 13px;
    padding: 8px 16px;
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 20px;
    /* or 133% */
    border: 1px solid #cccccc;
    color: #262626;
  }
  
  .dropdown-item {
    display: inline-block;
    width: 120px !important;
    padding: 8px 12px;
    margin-right: 18px;
    color: #ffffff;
    background-color: #999999;
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 15px;
    text-align: center;
    border-radius: 14px;
  }
  
  .drop-option {
    width: 120px;
    padding: 8px 12px;
    color: #ffffff;
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 15px;
    text-align: center;
    border-radius: 14px;
  }
  
  .dropdown-menu {
    border: 0px;
    background: #ffffff;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 8px;
  }
  
  .attachment-section {
    margin-top: -48px;
    padding-left: 24px;
    z-index: 10;
    background-color: white;
    width: 98%;
    border-radius: 13px;
    left: 2px;
    padding-bottom: 16px;
  }
  
  input[type="file"] {
    display: none;
  }
  .pdf-view {
    height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
  }
  
  .btn-primary,
  .btn-dark {
    padding: 4px !important;
    width: 124px !important;
    margin-bottom: 12px !important;
  }
  .person-wrapper {
    padding: 8px 20px;
    min-height: 59px;
    border-radius: 13px;
    border: 1px solid rgba(0, 0, 0, 0.2);
  }
  
  .color-secondary-dark {
    color: rgba(78, 78, 78, 0.4) !important;
  }
  
  .text-wrapper {
    padding: 15px 22px;
    border-radius: 13px;
    border: 1px solid rgba(0, 0, 0, 0.2);
  }
  </style>
  