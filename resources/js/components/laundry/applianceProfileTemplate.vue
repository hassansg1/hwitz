<template>
    <div class="laundry-section laundry-section-dashbord">
        <div class="d-flex flex-row flex-wrap justify-content-between mb-70">
            <div>
                <span class="h3">Templates</span>
            </div>
            <div>
                <button class="btn bkg-success mb-12" @click="createLaundryProfileTemplate">
                + Create an appliance profile template
                </button>
            </div>
        </div>
        <div class="mb-28">
            <div class="text-start">
                <div class="table-responsive">
                    <table>
                        <tr>
                        <th>#</th>
                        <th>Template Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                        </tr>

                        <tr class="border_bottom" v-for="(template,index) in applianceProfileData">
                        <td>{{ index+1 }}</td>
                        <td>
                            <span class="text-medium-bold">{{template.template_name}}</span>
                        </td>
                        <td>
                            <span class="text-medium-bold color-primary">{{template.status == 1 ? 'Active' : 'Inactive'}}</span>
                        </td>
                        <td>
                            <a class="a-link">
                                <i class="fa fa-pencil" @click="editTemplate(template)"></i>
                            </a>
                        </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <addApplianceProfileTemplate ref="addProfileApplianceTemplate" :id="'applianceTab'" />
        <applianceProfileNameTemplatePopup ref="applianceProfileNameTemplatePopup" :id="'applianceTab'" />
    </div>
  </template>
  
  <script>
  
  import addApplianceProfileTemplate from "./addApplianceProfileTemplate.vue";
  import applianceProfileNameTemplatePopup from "./applianceProfileNameTemplatePopup.vue";
  export default {
    components: { addApplianceProfileTemplate, applianceProfileNameTemplatePopup},
  
    data() {
      return {
        applianceProfileData : []
      };
    },
    created (){
      this.loadData();
    },  
    computed: {
      building_id() {
        return this.$store.getters.getBuildingId;
      },
    },
    watch: {
      building_id: function (newBuildingId, oldBuildingId) {
        this.loadData();
      }
    },
    methods: {
        loadData(){
            if(this.building_id == 0) return;
            this.showLoader();
            this.$http.get("/loadApplianceProfileTemplateData")
                .then((response) => {
                this.applianceProfileData = response.data;
                this.removeLoader();
                })
                .catch((error) => {
                    console.error(error);
                    this.removeLoader();
            });
        },
        createLaundryProfileTemplate(){
          this.$refs.applianceProfileNameTemplatePopup.form.reset();
          $('#addApplianceProfileNameTemplatePopupapplianceTab').modal('show');
        },
        editTemplate(template){
            // this.$refs.addProfileApplianceTemplate.createWeeklyPlannerUi();
            this.$refs.addProfileApplianceTemplate.form.exceptionalDiscount = [];
            this.$refs.addProfileApplianceTemplate.loadData(template.id);
            this.$refs.addProfileApplianceTemplate.form.template_name = template.template_name;
            this.$refs.addProfileApplianceTemplate.form.default_resident_charge = parseFloat(template.default_resident_charge).toFixed(1);
            this.$refs.addProfileApplianceTemplate.form.no_show_charge = parseFloat(template.no_show_charge).toFixed(1);
            this.$refs.addProfileApplianceTemplate.form.reservation_charge = parseFloat(template.reservation_charge).toFixed(1);
            this.$refs.addProfileApplianceTemplate.form.retail_rate = parseFloat(template.retail_rate).toFixed(1);
            this.$refs.addProfileApplianceTemplate.form.id = template.id;

            if(template.exceptions && template.exceptions.length > 0){
                let self = this;
                template.exceptions.forEach(function(item){
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
                    self.$refs.addProfileApplianceTemplate.form.exceptionalDiscount.push(obj);
                })
            }
            $('#addApplianceProfileTemplatePopupapplianceTab').modal('show');
        }
    },
  };
  </script>
  <style scoped>
    .tooltip-text{
      left : 200%;
      width: 120px;
    }
  </style>
  