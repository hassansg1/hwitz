<template>
    <div class="a-admin-components">
      <div
        class="modal fade"
        id="outOfOrderLaundry"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <h3 class="h3 mb-13">
                {{title}}
              </h3>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body text-start">
                <div class="row">
                    <div class="col-md-6 mx-auto multiselect-border multiselect-bold">
                        <div class="mb-27">
                            <div id="text-area">
                                <p class="text-medium color-secondary mb-12">Comments (Optional)</p> 
                                <textarea rows="8" class="w-100" v-model="comment">

                                </textarea>
                            </div>
                            <div class="d-flex">
                                <div>
                                    <input type="checkbox" id="flexCheckDefault1" class="form-check-input me-12" v-model="workOrder">
                                </div> 
                                <div class="align-self-center">
                                    <label for="flexCheckDefault" class="form-check-label text-normal">
                                        Generate Work Order
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="action-btn" class="d-flex justify-content-end">
                            <button type="button" class="btn btn-dark me-36" data-bs-dismiss="modal" aria-label="Close" >Cancel</button>
                            <button type="submit" class="btn bkg-success" @click="submit()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  export default {
    created() {},
    data() {
      return {
        data: [],
        title : '',
        comment : '',
        workOrder : false,
        machine : ''
      };
    },
    expose: [],
    methods: {
        loadData(){
            
        },
        submit(){
            this.showLoader();
            let params ={
                comment : this.comment,
                workOrder : this.workOrder
            }
            this.$http.post("/laundry_change_status/"+this.machine.id , params)
                .then((response) => {
                    this.$parent.loadData();
                    $('#outOfOrderLaundry').modal('hide');
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
                });
        }
    },
  };
  </script>
  