<template>
    <div class="dropend" style="padding-top: 20px;padding-left: 10px;">
            <i
                class="fa fa-ellipsis-h color-secondary select-cursor"
                id="unitDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            ></i>
            <div class="dropdown">
              <ul style="cursor: pointer"
                  class="dropdown-menu dropdown-menu-end border-standered"
                  aria-labelledby="unitDropdown"
              >
                <li
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                    @click="vacateUnit(data)"
                >
                  Vacant Unit
                </li>
                <li
                    @click="relocateResident(data)"
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  Relocate One/All Residents
                </li>
                <li
                    @click="disableServices(data)"
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  Disable All Services
                </li>
                <li
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  Docs Signed : 3/5
                </li>
                <li
                    @click="leaseHistory(data)"
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  View Lease history
                </li>
                <li
                    @click="unitHistory(data)"
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  View Unit history
                </li>
                <li
                    @click="packageHistory(data)"
                    class="color-secondary px-2 py-1 border-bottom text-sm-bold"
                >
                  View Package history
                </li>
              </ul>
            </div>
            <leaseHistoryModal ref="leaseHistoryModal"/>
            <packageHistoryModal ref="packageHistoryModal"/>
            <relocateResidentModal ref="relocateResidentModal"/>
          </div>
</template>
<script>
import relocateResidentModal from './relocate-resident-modal.vue';
import leaseHistoryModal from './lease-history-modal.vue';
import packageHistoryModal from './package-history-modal.vue';
import {config} from "../../config";
    export default {
        props : ["data"],
        components : {
            relocateResidentModal,
            leaseHistoryModal,
            packageHistoryModal
        },
        methods : {
            vacateUnit(unit){
                // this.$parent.vacateUnit(data);
                let usersCount = unit.users.length;
                if (usersCount > 0) {
                    Swal.fire({
                    title: config.confirmBoxTitle,
                    text: 'By vacating the unit, you are agreeing to zero out the balances of all carts associated with this unit.  Balances of the vacated guarantor will remain their responsibility and pending charges will be available for payment at anytime.  NOTE: you may "write-off" balances in the "manual transactions tab" under payments? Are you sure you want to vacate unit?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: config.confirmButtonColor,
                    cancelButtonColor: config.cancelButtonColor,
                    confirmButtonText: config.confirmButtonText,
                    }).then((result) => {
                    if (result.value) {
                        let params = {
                        unitId: unit.id,
                        };
                        this.showLoader();
                        this.$http
                            .post("/vacateUnit", params)
                            .then((response) => {
                                response = response.data;
                                if (response.status) {
                                    Toast.fire({
                                    icon: "success",
                                    position: "top",
                                    title: "The unit has been successfully vacated.",
                                    });
                                    this.$parent.loadData();
                                } else {
                                    Toast.fire({
                                    icon: "error",
                                    position: "top",
                                    title: response.message ?? "Something went wrong",
                                    });
                                }
                                this.removeLoader();
                            })
                            .catch((error) => {
                                this.removeLoader();
                                console.error(error);
                            });
                    }
                    });
                }
            },
            relocateResident(unit){
                // this.$parent.relocateResident(data);
                this.$refs.relocateResidentModal.relocateResident(unit);
            },
            disableServices(data){
                // this.$parent.disableServices(data);
                Swal.fire({
                    title: config.confirmBoxTitle,
                    text: 'Are you sure you want to disable all services?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: config.confirmButtonColor,
                    cancelButtonColor: config.cancelButtonColor,
                    confirmButtonText: config.confirmButtonText,
                }).then((result) => {
                    if (result.value) {
                    let params = {
                        unitId: unit.id,
                    };
                    this.showLoader();
                    this.$http
                        .post("/disableServices", params)
                        .then((response) => {
                            response = response.data;
                            if (response.status) {
                                Toast.fire({
                                    icon: "success",
                                    position: "top",
                                    title: "All services have been successfully disabled.",
                                });
                                this.$parent.loadData();
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    position: "top",
                                    title: response.message ?? "Something went wrong",
                                });
                            }
                            this.removeLoader();
                        })
                        .catch((error) => {
                            this.removeLoader();
                            console.error(error);
                        });
                    }
                });
            },
            leaseHistory(unit){
                // this.$parent.leaseHistory(data);

                $("#lease-history-modal").modal("show");
                this.$refs.leaseHistoryModal.loadData(null, unit);
            },
            unitHistory(unit){
                window.open("/unit_history/" + unit.id, '_blank')
            },
            packageHistory(unit){
                // this.$parent.packageHistory(data);

                this.$refs.packageHistoryModal.unit = unit;
                $("#package-history-modal").modal("show");
                this.$refs.packageHistoryModal.getDetails(unit);
            }
        }
    }
</script>