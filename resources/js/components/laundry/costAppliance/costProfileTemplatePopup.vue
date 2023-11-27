<template>
    <div
    class="modal fade broadcast-modal"
    :id="'costProfileTemplate'+id"
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
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Time From</label>
                        <input id="cost_start_time" v-model="form.cost_start_time" type="time" class="form-control" />
                    </div>
                    <div class="mb-24 col-xl-6">
                        <label class="text-normal-bold-md mb-12 color-secondary" for="title">Time To</label>
                        <input id="cost_end_time" v-model="form.cost_end_time" type="time" class="form-control" />
                    </div>
                </div>

                <div class="row pb-24 gy-3">
                    <div class="mb-24 col-xl-4">
                        <div class="d-flex">
                            <div>
                                <input type="radio" name="cost_radio" class="form-check-input me-12" value=1 v-model="form.cost_free_service">
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
                                <input type="radio" name="cost_radio" class="form-check-input me-12" value=1 v-model="form.cost_amount_radio">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal d-flex">
                                    <span class="py-8" style="padding-right: 6px;">Cost</span>
                                    <input id="title" v-model="form.cost_amount" type="number" placeholder="$1.5" class="form-control" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pb-24 gy-3">
                    <p class="mb-8 text-medium-bold text-uppercase color-secondary">Also, Apply to</p>

                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Monday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(1) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '1', name: 'Monday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(1)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Monday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6"  v-if="currentWeekDay != 'Tuesday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(2) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '2', name: 'Tuesday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(2)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Tuesday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Wednesday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(3) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '3', name: 'Wednesday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(3)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Wednesday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Thursday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(4) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '4', name: 'Thursday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(4)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Thursday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Friday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(5) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '5', name: 'Friday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(5)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Friday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Saturday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(6) ? 'cursor-not-allowed' : ''" >
                            <div>
                                <input type="checkbox" :value="{index: '6', name: 'Saturday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(6)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Saturday</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-24 col-xl-3 col-lg-4 col-md-6" v-if="currentWeekDay != 'Sunday'">
                        <div class="d-flex" :class="disabledWeekDays.includes(7) ? 'cursor-not-allowed' : ''">
                            <div>
                                <input type="checkbox" :value="{index: '7', name: 'Sunday'}" class="form-check-input me-12" v-model="form.weekday_selection" :disabled="disabledWeekDays.includes(7)">
                            </div> 
                            <div class="align-self-center">
                                <label for="flexCheckDefault" class="form-check-label text-normal">Sunday</label>
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
    data() {
      return {
        form : new Form({
            cost_start_time : '',
            cost_end_time : '',
            cost_amount_radio : 0,
            cost_amount: 0,
            cost_free_service: 0,
            id: 0,
            week_day: "1",
            weekday_selection: [],
            laundry_id : '',
            appliance_id : ''
        }),
        weeklyPlannerHtml : '',
        title : '',
        disabledWeekDays : [],
        currentWeekDay : ''
      };
    },
    mounted(){
      
    },
    created(){
    //   window.openCostConfigPopup =this.openCostConfigPopup;
    },  
    props : [ "id"],
    methods : {
        submit(){
            this.showLoader();
            this.form
                .post("/api/laundry_set_cost_data")
                .then(({ data }) => {
                    console.log(data);
                    if(data.status == 0){
                        this.removeLoader();
                        Toast.fire({icon : 'warning', title : data.message});
                        return;
                    }
                    this.$parent.weeklyPlannerHtml = data.data;
                    this.$nextTick(() => {
                        $('[data-toggle="tooltip"]').tooltip({html: true})

                        // window.openCostConfigPopup =self.$parent.openCostConfigPopup;
                    });
                    $('#costProfileTemplate'+this.id).modal('hide');
                    this.removeLoader();
                })
                .catch((error) => {
                    this.removeLoader();
                });
      },
      createWeeklyPlannerUi(){
        this.$http.get("/create_weekly_planner_html")
        .then((response) => {
          this.weeklyPlannerHtml = response.data;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
      },
  
    }
  }
  </script>
  