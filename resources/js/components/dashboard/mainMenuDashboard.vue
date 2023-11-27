<template>
    <div class="fobs-section">
        <div class="mb-28">
            <div class="text-start">
                <div class="row text-normal color-secondary mb-12">
                    <div class="col-lg-6">Last Updated : <span class="red"> {{ last_updated ? last_updated : 'Not Set' }}</span></div>
                    <div class="col-lg-6 text-right"><a href="/manageLockdown" target="_blank">Manage Lockdown</a></div>
                </div>
                <div class="table-responsive">
                    <table>
                        <tr>
                        <!-- <th>#</th> -->
                        <th>Name</th>
                        <th>Lockdown</th>
                        <!-- <th>ATA <Sorting :sortColumn="'lockdown'" /></th>
                        <th>Entry <Sorting :sortColumn="'ata'" /></th>
                        <th>Name Display</th>
                        <th>Door Icm</th>
                        <th>Router</th>
                        <th>Modems</th>
                        <th>Onsite</th>
                        <th>Cameras</th>
                        <th>Video Intercom</th>
                        <th>Waps</th>
                        <th>Switches</th>
                        <th>Laundry</th>
                        <th>Captive</th> -->
                        <th>Unverified</th>
                        </tr>

                        <tr class="border_bottom text-medium color-secondary" v-for="(building,index) in data" style="background-color: #262626; color: #CCCCCC;">
                            <!-- <td>{{ index+1 }}</td> -->
                            <!-- <td :class="{'bg-red' : building.lockdown == 1}" :title="building.lockdown == 1 ? 'Building is in lockdown' : ''"> -->
                            <td :title="building.lockdown == 1 ? 'Building is in lockdown' : ''">
                                <span :class="{'color-secondary' : building.lockdown == 0}">{{ building.name }}</span>
                                <!-- <i class="fa fa-pencil"></i> -->
                            </td>
                            <td>
                                <div>
                                    <div class="d-flex mt-11">
                                        <div class="text-medium color-secondary me-12">{{building.lockdown == 1 ? 'On' : 'Off'}}</div>
                                        <div class="form-check form-switch p-0">
                                            <label class="switch mb-0">
                                            <input type="checkbox" role="switch" v-model="building.lockdown" @change="activateOrDeactivateLockdown(building)"/>
                                            <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- <td v-html="building.ata_status"></td>
                            <td v-html="building.entry"></td>
                            <td v-html="building.name_display"></td>
                            <td v-html="building.door_status"></td>
                            <td v-html="building.router_status"></td>
                            <td v-html="building.modem_status"></td>
                            <td v-html="building.onsite_server"></td>
                            <td v-html="building.cameras"></td>
                            <td v-html="building.video_intercom"></td>
                            <td v-html="building.waps"></td>
                            <td v-html="building.poe"></td>
                            <td v-html="building.laundry"></td>
                            <td v-html="building.captive"></td> -->
                            <td v-html="building.unverified_resident"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <vitalSignsModal ref="modal" />
    </div>
  </template>
  <script>
  import vitalSignsModal from "./vitalSignsModal.vue"
  import { config } from "../../config";
  export default {
    props : ["loadedAsAComponentInDashboard"],
    components: {
        vitalSignsModal
    },
    data() {
      return {
        data : '',
        last_updated : ''
      };
    },
    created(){
      this.loadData();
    },
    methods : {
      loadData(){
  
  
        this.showLoader();
        let url = "/buildingVitalSigns?admin=true&is_admin=15&building=0&user_level=landlord_id&auth_id="+this.$gate.user.id;

        if(this.loadedAsAComponentInDashboard){
            url += '&building_id='+this.$gate.building_id;
        }
        this.$http
          .get(url)
          .then((response) => {
            this.data = response.data.lists;
            this.last_updated = response.data.last_updated;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
          
      },
      vitalsign_popup(html,device){
        this.$refs.modal.id = 0;
        this.$refs.modal.body = html;
        this.$refs.modal.title=device;
        $('#modal-0').modal('show');
      },
      activateOrDeactivateLockdown(building){
        console.log(config);
            let value = building.lockdown ? 1 : 0;
            let id = building.building_id;
            let text = 'deactivate';
            if(value == 1) text = 'activate';

            Swal.fire({
                    title: config.confirmBoxTitle,
                    text: "Are you sure you want to "+text+" the lockdown?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: config.confirmButtonColor,
                    cancelButtonColor: config.cancelButtonColor,
                    confirmButtonText: config.confirmButtonText,
                }).then((result) => {
                if (result.value) {
                    this.showLoader();
                    this.$http
                    .get("/activateOrDeactivateLockdown/"+id+"/"+value)
                    .then((response) => {
                        this.loadData();
                        Toast.fire({
                            icon: 'success',
                            title: 'Lockdown '+text+'d succesfully',
                        });
                        this.removeLoader();
                    })
                    .catch((error) => {
                        console.error(error);
                    });
                }else{
                    const obj = this.data.find(obj => obj.id == building.id);
                    obj.lockdown = !building.lockdown;
                }
            });

        },
        showFobPopup(id) {
            this.$http
                    .get("/fob_asset_status/"+id)
                    .then((response) => {
                        if (data) {
                            $('#fob_entry_popup').html(data.html);
                            // $('#preloader-wrapper,#loading_text,#circ').hide();
                            $('#fob_entry_popup').modal('show');
                        }
                        this.removeLoader();
                    })
            $.ajax({
                //async: false,
                url: '/fob_asset_status/' + id,
                success: function (data) {
                    console.log(data.html,'ADs')
                    if (data) {
                        $('#fob_entry_popup').html(data.html);
                        // $('#preloader-wrapper,#loading_text,#circ').hide();
                        $('#fob_entry_popup').modal('show');
                    } else {
                        // $('#preloader-wrapper,#loading_text,#circ').hide();
                        alert('Unable to get data');
                        return false;
                    }
                },
            });
        }
    },
    mounted (){
        window.vitalsign_popup = this.vitalsign_popup
    }
  };
  </script>
  <style scoped>
    tr:hover {
        background: rgb(38, 38, 38) !important;
    }
    .fobs-section .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #c2ff69;
        /* background-color: #ff6969; */
        -webkit-transition: 0.4s;
        transition: 0.4s;
    }
    input:checked + .slider {
    /* background-color: #c2ff69; */
        background-color: #ff6969;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #ff6969;
        /* box-shadow: 0 0 1px #c2ff69; */
    }
  </style>
  