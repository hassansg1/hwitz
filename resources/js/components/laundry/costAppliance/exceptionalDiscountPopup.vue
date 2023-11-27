<template>
    <div
    class="modal fade broadcast-modal"
    :id="'exceptionalDiscountPopup' +id"
    tabindex="-1"
    aria-labelledby="broadcastModalLabel"
    aria-hidden="true"
    >
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header border-0">
            <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            ></button>
        </div>
        <div class="modal-body text-start">
            <div class="row">
            <form @submit.prevent="submit" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                <div class="col-lg-12 mx-auto">
                <h3 class="h3 mb-13">{{title}}</h3>

                <alert-error :form="form"></alert-error>


                <div class="row pb-24 gy-3">
                    <div class="mb-24 col-xl-6">
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Date From</label>
                        <input id="title" v-model="form.start_date" type="date" class="form-control" placeholder="Date from" />
                        <has-error :form="form" field="start_date"></has-error>
                    </div>
                    <div class="mb-24 col-xl-6">
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Time From</label>
                        <input id="title" v-model="form.start_time" type="time" class="form-control" placeholder="Time from" />
                        <has-error :form="form" field="end_time"></has-error>
                    </div>
                </div>
                <div class="row pb-24 gy-3">
                    <div class="mb-24 col-xl-6">
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Date To</label>
                        <input id="title" v-model="form.end_date" type="date" class="form-control" placeholder="Date to" />
                        <has-error :form="form" field="end_date"></has-error>
                    </div>
                    <div class="mb-24 col-xl-6">
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Time To</label>
                        <input id="title" v-model="form.end_time" type="time" class="form-control" placeholder="Time to" />
                        <has-error :form="form" field="start_time"></has-error>
                    </div>
                </div>

                <div class="row pb-24 gy-3">
                    <div class="mb-24 col-xl-4">
                        <div class="d-flex">
                            <div>
                                <input type="radio" id="flexCheckDefault1" name="type"  class="form-check-input me-12" value=1 v-model="form.free_service">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">
                                    Free Service
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-8">
                        <div class="d-flex">
                            <div>
                                <input type="radio" id="flexCheckDefault1" name="type" class="form-check-input me-12" value="cost" v-model="form.discount_type">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal d-flex">
                                    <span class="py-8" style="padding-right: 6px;">Cost</span>
                                    <input id="title" v-model="form.discount_cost" type="text" class="form-control" style="height: 34px;" placeholder="$1.0"/>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-dark" type="button" data-bs-dismiss="modal" style="margin-right: 2px;">Cancel</button>
                    <button type="submit" class="btn btn-dark">Save</button>
                </div>

                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    </div>
  </template>
  
  <script>
  
  
  export default {
    components : {
        
    },
    props : ["id"],
    data() {
      return {
        form : new Form({
          end_time : '',
          start_time : '',
          start_date : '',
          end_date : '',
          free_service : 0,
          discount_cost : '',
          discount_type : '',
          template_id : '',
          id : 0,
          laundry_id : '',
          appliance_id : ''
        }),
        weeklyPlannerHtml : '',
        title : 'Add / Edit Discount / Free Service Offers'
      };
    },
    mounted(){
      
    },
    created(){
        
    },  
    methods : {
        submit(){
            this.showLoader();
            this.form
                .post("/api/laundry_set_discount_data")
                .then(({ data }) => {
                    if(data.status == 0){
                        Toast.fire({ icon: "error", title: data.message });
                    }else{
                        if(data.data && data.data.length > 0){
                            let self = this;
                            let tempArray = [];
                            data.data.forEach(function(item){
                                let obj = {};
                                obj.id=item.id;
                                obj.originalData = item;
                                obj.start_date = self.formatDateTime(item.start_date);
                                obj.end_date = self.formatDateTime(item.end_date);
                                if(item.free_service == 1){
                                    obj.price = "Free";
                                }else{
                                    obj.price = item.discount_cost;
                                }
                                tempArray.push(obj);
                            })
                            self.$parent.form.exceptionalDiscount = tempArray;
                            Toast.fire({icon : "success", title : "Discount added succesfully."})
                        }
                        $('#exceptionalDiscountPopup'+this.id).modal('hide');
                    }
                    this.removeLoader();
                })
                .catch((error) => {
                    this.removeLoader();
                });
        }
  
    }
  }
  </script>
  