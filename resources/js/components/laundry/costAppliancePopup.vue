<template>
    <div>
      <div
        class="modal fade broadcast-modal"
        :id="'costAppliancePopup'+id"
        tabindex="-1"
        aria-labelledby="broadcastModalLabel"
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
            <div class="modal-body text-start">
              <div class="row">
                <form @submit.prevent="submit" id="broadcastMessageForm" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                  <div class="col-lg-12 mx-auto">
                    <h3 class="h3 mb-13">Create Appliance Profile Template</h3>
  
                    <alert-error :form="form"></alert-error>
  
  
                    <!-- <div class="row pb-24 gy-3">
                        <div class="mb-24 col-xl-3 col-lg-4 col-md-6">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">Template Name</label>
                            <input id="title" v-model="form.template_name" type="text" class="form-control" />
                            <has-error :form="form" field="template_name"></has-error>
                        </div>
                    </div> -->

                    <div class="row pb-24 gy-3">
                        <p class="mb-8 text-medium-bold text-uppercase color-secondary">All Charges</p>

                        <div class="mb-24 col-xl-3 col-lg-4 col-md-6">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">Default Resident Charge</label>
                            <input id="title" v-model="form.default_resident_charge" type="number" class="form-control" placeholder="$1.25" step=".01" />
                            <has-error :form="form" field="default_resident_charge"></has-error>
                        </div>
                        <div class="mb-24 col-xl-3 col-lg-4 col-md-6">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">Reservation Charge</label>
                            <input id="title" v-model="form.reservation_charge" type="number" class="form-control" placeholder="$1.50" step=".01" />
                            <has-error :form="form" field="reservation_charge"></has-error>
                        </div>
                        <div class="mb-24 col-xl-3 col-lg-4 col-md-6">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">No Show Charge</label>
                            <input id="title" v-model="form.no_show_charge" type="number" class="form-control" placeholder="$2.0" step=".01" />
                            <has-error :form="form" field="no_show_charge"></has-error>
                        </div>
                        <div class="mb-24 col-xl-3 col-lg-4 col-md-6">
                            <label class="text-normal-bold-md mb-12 color-secondary" for="title">Retail Rate</label>
                            <input id="title" v-model="form.retail_rate" type="number" class="form-control" placeholder="$1.75" step=".01" />
                            <has-error :form="form" field="retail_rate"></has-error>
                        </div>
                    </div>

                    <div class="row pb-24 gy-3">
                        <p class="mb-8 text-medium-bold text-uppercase color-secondary">Weekly Planner</p>

                        <div class="mb-24 col-xl-12 col-lg-12 col-md-12">
                          <div class="control-group table_new_cost">
                            <div class="controls">
                              <div class="weekly-planner clearfix">
                                <div class="flex" id="weekly_planner_container_html" v-html="weeklyPlannerHtml"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="row pb-24 gy-3">
                        <p class="mb-8 text-medium-bold text-uppercase color-secondary">
                          Exceptional Discount / Free Service Offers
                          <i class="fa fa-add select-cursor a-link" @click="exceptionalDiscountPopup()"></i>
                        </p>

                        <div class="mb-24 col-xl-12 col-lg-12 col-md-12">
                          <div class="row" v-for="data in form.exceptionalDiscount">
                            <div class="col-xl-6">
                              <span class="color-primary">From Date & Time</span> 
                              <span class="color-secondary">: {{ data.start_date + ' to ' + data.end_date }}</span>
                            </div>
                            <div class="col-xl-3">
                              <span class="color-primary">Price</span> 
                              <span class="color-secondary">: {{ data.price }}</span>
                            </div>
                            <div class="col-xl-3">
                              <i class="fa fa-edit a-link select-cursor" @click="editDiscount(data)"></i>
                              <i class="fa fa-trash a-link select-cursor" @click="deleteDiscount(data)"></i>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button class="btn btn-dark" type="button" data-bs-dismiss="modal" style="margin-right:2px">Cancel</button>
                      <button type="submit" class="btn btn-dark">Save</button>
                    </div>
  
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <costProfileTemplate ref="costProfilePopup" :id="id"/>
      <exceptionalDiscountPopup ref="exceptionalDiscountPopup" :id="id"/>
    </div>
  </template>
  
  <script>
  import costProfileTemplate from "./costAppliance/costProfileTemplatePopup.vue";
  import exceptionalDiscountPopup from "./costAppliance/exceptionalDiscountPopup.vue";
  import Moment from 'moment-timezone';
  import {config} from "../../config";
  export default {
    components : {
      costProfileTemplate,
      exceptionalDiscountPopup
    },
    props : ["id"],
    data() {
      return {
        form : new Form({
          id : '',
          appliance_id : '',
          default_resident_charge : '',
          reservation_charge : '',
          no_show_charge : '',
          retail_rate : '',
          exceptionalDiscount : []
        }),
        weeklyPlannerHtml : '',
        applianceProfileData : [],
        template_id : '',

        selectedRoom : '',
        selectedAppliance : '',
      };
    },
    mounted(){
      
    },
    created(){
    },  
    methods : {
      
      editDiscount(data){
        this.$refs.exceptionalDiscountPopup.form.reset();
        let template = data.originalData;
        this.$refs.exceptionalDiscountPopup.form.template_id = this.template_id;
        this.$refs.exceptionalDiscountPopup.form.end_time = Moment(template.end_date.split(" ")[1], "HH:mm:ss").format("HH:mm");
        this.$refs.exceptionalDiscountPopup.form.start_time = Moment(template.start_date.split(" ")[1], "HH:mm:ss").format("HH:mm");
        this.$refs.exceptionalDiscountPopup.form.start_date = template.start_date.split(" ")[0];
        this.$refs.exceptionalDiscountPopup.form.end_date = template.end_date.split(" ")[0];
        this.$refs.exceptionalDiscountPopup.form.free_service = template.free_service;
        this.$refs.exceptionalDiscountPopup.form.discount_cost = template.discount_cost;
        if(template.discount_cost && template.discount_cost > 0) this.$refs.exceptionalDiscountPopup.form.discount_type = "cost";
        this.$refs.exceptionalDiscountPopup.form.id = template.id;
        $('#exceptionalDiscountPopup'+this.id).modal('show');
      },
      deleteDiscount(item){
        Swal.fire({
          title: config.confirmBoxTitle,
          text: "Are you sure you want to delete this offer?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: config.confirmButtonColor,
          cancelButtonColor: config.cancelButtonColor,
          confirmButtonText: config.confirmButtonText,
        }).then((result) => {
          if (result.value) {
            this.showLoader();
            this.$http
              .get("/deleteLaundryDiscount/"+item.id)
              .then((response) => {
                this.form.exceptionalDiscount = this.form.exceptionalDiscount.filter(obj => obj.id !== item.id);
                Toast.fire({
                    icon: 'success',
                    title: 'Offer deleted succesfully',
                });
                this.removeLoader();
              })
              .catch((error) => {
                console.error(error);
              });
          }
        });
      },
      loadData(selectedRoom, item){
        // this.template_id = id;
        // this.showLoader();
        // this.$http.get("/loadApplianceProfileTemplateData/"+id)
        //     .then((response) => {
        //     let self = this;
        //     this.applianceProfileData = response.data;
        //     this.weeklyPlannerHtml = this.applianceProfileData.weekly_planner_html;

        //     this.$nextTick(() => {
        //       $('[data-toggle="tooltip"]').tooltip({html: true})

        //       window.openCostConfigPopup =self.openCostConfigPopup;
        //     });
        //     this.removeLoader();
        //     })
        //     .catch((error) => {
        //       console.error(error);
        //       this.removeLoader();
        // });
        this.showLoader();
        this.selectedAppliance = item;
        this.selectedRoom = selectedRoom;
        this.form.appliance_id = item.id;
        this.$http.get("/laundry_washer_dryer_new_cost/" + selectedRoom.id + "/" + item.id)
            .then((response) => {
                let self = this;
                let res = response.data;
                let charges_data = res.charges_data;
                // let ref =  this.$refs.costAppliancePopup;
                // ref.form.reset();

                this.form.default_resident_charge = parseFloat(charges_data.default_resident_charge).toFixed(1);
                this.form.no_show_charge = parseFloat(charges_data.no_show_charge).toFixed(1);
                this.form.reservation_charge = parseFloat(charges_data.reservation_charge).toFixed(1);
                this.form.retail_rate = parseFloat(charges_data.retail_rate).toFixed(1);

                this.weeklyPlannerHtml = res.weeklyPlannerHtml;
                this.$nextTick(() => {
                    $('[data-toggle="tooltip"]').tooltip({html: true})

                    window.openCostConfigPopup =self.openCostConfigPopup;
                });

                this.form.exceptionalDiscount = [];
                if(res.discount_data && res.discount_data.length > 0){
                    let self = this;
                    res.discount_data.forEach(function(item){
                        let obj = {};
                        obj.id = item.id;
                        obj.originalData = item;
                        obj.start_date = self.formatDateTime(item.start_date);
                        obj.end_date = self.formatDateTime(item.end_date);
                        if(item.free_service == 1){
                            obj.price = "Free";
                        }else{
                            obj.price = item.discount_cost;
                        }
                        self.form.exceptionalDiscount.push(obj);
                    })
                }

                $('#costAppliancePopup'+this.id).modal('show');
                this.removeLoader();
            })
            .catch((error) => {
                console.error(error);
                this.removeLoader();
            });
      },
      submit(){
        this.showLoader();
        this.form
          .post("/api/laundry_save_all_charges")
          .then(({ data }) => {
            // this.$parent.loadData();
            Toast.fire({
              icon: "success",
              title: "Charges saved successfully.",
            });
            $("#addApplianceProfileTemplatePopup"+this.id).modal("hide");
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
      openCostConfigPopup(tdBlockId,weekDayName,costStartTime,costEndTime,costPrice,costId){
        this.$refs.costProfilePopup.form.reset();
        const explodedString = tdBlockId.split('-');
        let startTime = this.convertToHHmm(explodedString[1]);
        let endTime = this.getEndTimeFromStartTime(startTime);
        this.$refs.costProfilePopup.form.cost_start_time = startTime;
        this.$refs.costProfilePopup.form.cost_end_time = endTime;
        // this.$refs.costProfilePopup.form.template_id = this.template_id;
        this.$refs.costProfilePopup.form.week_day = explodedString[0];
        this.$refs.costProfilePopup.form.laundry_id = this.selectedRoom.id;
        this.$refs.costProfilePopup.form.appliance_id = this.selectedAppliance.id;

        if(costId != '') {
          this.$refs.costProfilePopup.form.id = costId;
          let endTime = this.convertToHHmm(costEndTime);
          this.$refs.costProfilePopup.form.cost_end_time = endTime;
          if(costPrice && costPrice != '0'){
            this.$refs.costProfilePopup.form.cost_amount = costPrice;
            this.$refs.costProfilePopup.form.cost_amount_radio = 1;
          }else{
            this.$refs.costProfilePopup.form.cost_free_service = 1;
          }
        }

        this.$http.post("/laundry_validate_cost_other_weekdays_cp",{
          cost_start_time : startTime,
          cost_end_time : endTime,
          current_week_day : weekDayName,
          template_id : this.template_id
        })
        .then((data) => {
          this.$refs.costProfilePopup.disabledWeekDays = data.data.data;
          this.$refs.costProfilePopup.currentWeekDay = weekDayName;
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });

        this.$refs.costProfilePopup.title = weekDayName;
        $('#costProfileTemplate'+this.id).modal('show');
      },
      exceptionalDiscountPopup(){
        this.$refs.exceptionalDiscountPopup.form.reset();
        this.$refs.exceptionalDiscountPopup.form.template_id = this.template_id;
        this.$refs.exceptionalDiscountPopup.form.laundry_id = this.selectedRoom.id;
        this.$refs.exceptionalDiscountPopup.form.appliance_id = this.selectedAppliance.id;
        $('#exceptionalDiscountPopup'+this.id).modal('show');
      },
      convertTimeRangeTo24HourFormat(inputString) {
        const explodedString = inputString.split('-');
        let startTime = this.convertToHHmm(explodedString[1]);
        let endTime = this.getEndTimeFromStartTime(startTime);
        console.log(startTime); 
        console.log(endTime);
      },
      convertToHHmm(time) {
        const date = new Date();
        const [hour, ampm] = time.match(/\d+|AM|PM/g);
        
        if (ampm === 'PM' && hour !== '12') {
          date.setHours(parseInt(hour, 10) + 12);
        } else if (ampm === 'AM' && hour === '12') {
          date.setHours(0);
        } else {
          date.setHours(parseInt(hour, 10));
        }

        const hours = String(date.getHours()).padStart(2, '0');
        
        return `${hours}:00`;
      },
      getEndTimeFromStartTime(inputTime, flag = true){

        const [hours, minutes] = inputTime.split(':').map(Number);

        const date = new Date();
        date.setHours(hours);
        date.setMinutes(minutes);

        if (flag) date.setHours(date.getHours() + 1);

        const updatedHours = date.getHours();
        const updatedMinutes = date.getMinutes();

        const updatedTime = `${String(updatedHours).padStart(2, '0')}:${String(updatedMinutes).padStart(2, '0')}`;

        return updatedTime;

      }
  
    }
  }
  </script>
  <style>
    .flex table {border: none !important;padding: 0;text-align: center;table-layout:fixed;width:100%;}
    .flex p {margin:2px 0 0;}
    .flex th {font-weight:normal;text-align: center;padding-left:0 !important;border:none !important;}
    .flex tbody tr:first-child td::before {border-left: 1px dotted rgba(0, 0, 0, 0.10);bottom: 0;content: "";padding: 0;top: 0;}
    .flex td {height:60px;}
    #draggable {background-color: #47c5fe;border-radius: 5px;color: #fff;font-size: 14px;font-weight: bold;padding: 1px 20px;position: absolute;cursor: grab;}
    #draggable p{width:100%;}
    #draggable.leftspace{margin-left:1.9% !important;}
    .flex ul {float: left;list-style: outside none none;margin: 0;padding: 27px 20px 0 0;display: inline-block;width:85px;}
    .flex ul li {margin:18px 0;display:inline-block;width:100%}
    .flex ul li p{width:100%;}
    .flex {display:flex;padding:10px 0;}
    /* .btn{min-width:70px !important;border-radius:3px !important;}
    .btn-large{padding:5px 12px} */
    .floatingBox table th, .floatingBox table td{text-align:center !important;}

    /*tooltip*/
    .weekly-planner .flex td{padding:0;height: 60px;}
    .weekly-planner #draggable {cursor: inherit;height: 51px;overflow: hidden;padding: 0;margin: 4px 0 0;}
    .weekly-planner .flex #draggable p{margin:7px 0 0;}
    .weekly-planner #draggable p span{display:block;}
    .weekly-planner .flex tbody tr:first-child td #draggable{margin-top:-15px;}
    .weekly-planner .flex ul li{margin: 30px 0 0;}
  </style>
  