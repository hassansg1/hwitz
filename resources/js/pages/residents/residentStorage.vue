<template>
    <div class="col-lg-6">
        <div class="mb-64">
            <h3 class="h3 mb-28">Storage assigned</h3>
            <div class="text-start">
                <div class="table-responsive">
                <table>
                    <tr>
                    <th>Unit No.</th>
                    <th>Building</th>
                    <th>Date assigned</th>
                    </tr>

                    <tr class="border_bottom" v-for="building in storages" v-if="building.type == 'Storage'">
                        <td>
                            <div class="text-medium-bold color-primary">{{building.unit_no}}</div>
                        </td>
                        <td>
                            <span class="text-medium color-secondary">{{building.name}}</span>
                        </td>
                        <td>
                            <span class="text-medium color-secondary">{{formatDate(building.created)}}</span>
                        </td>
                    </tr>
                    <tr v-if="!storages || storages.length == 0">
                        <td class="text-center" colspan="3">No Data found.</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="d-flex justify-content-center">
                            <button class="btn btn-table bkg-primary text-white" @click="openAddStorageModal">
                                + Assign new storage
                            </button>
                            </div>
                        </td>
                    </tr>
                </table>
                </div>
            </div>
        </div>
        <addStorageAndParkingModalVue :id="'s'" ref="addNewStorageAndParkingModal" />
    </div>
 </template>
 
 <script>
 import addStorageAndParkingModalVue from "./addStorageAndParkingModal.vue"
 export default {
     components: {
        addStorageAndParkingModalVue
     },
     props :["storages","user"],
     watch: {
     },
     data() {
         return {
             
         };
     },
     computed: {
         
     },
     methods: {
        openAddStorageModal(){
            this.$refs.addNewStorageAndParkingModal.title = 'Assign new storage';
            this.$refs.addNewStorageAndParkingModal.parking = 0;
            this.$refs.addNewStorageAndParkingModal.storage  = 1;
            this.$refs.addNewStorageAndParkingModal.getBuildings('Storage');
            this.$refs.addNewStorageAndParkingModal.form.reset();
            this.$refs.addNewStorageAndParkingModal.form.user_id = this.user.id;
            $('#addNewStorageAndParkingModals').modal('show');
        },
        reloadData(){
            this.$parent.reloadData();
        },
     },
 };
 </script>
 