<template>
    <div class="mb-64">
        <h3 class="h3 mb-28">FOBs assigned ({{ user.name }})</h3>
        <div class="text-start">
            <div class="table-responsive">
            <table>
                <tr>
                <th>Id</th>
                <th>date assigned</th>
                <th>Email</th>
                <th>Entrances</th>
                <th>Action</th>
                </tr>

                <tr class="border_bottom" v-for="fob in tokens">
                    <td>
                        <div class="text-medium-bold color-primary">{{fob.card_id}}</div>
                    </td>
                    <td>
                        <span class="text-medium color-secondary">{{formatDate(fob.created)}}</span>
                    </td>
                    <td>
                        <span class="text-medium color-secondary">{{user.email}}</span>
                    </td>

                    <td>
                        <a class="a-link" @click="viewEntrances">View</a>
                    </td>
                    <td>
                        <div>
                        <button class="btn btn-table bkg-gray-light text-white" @click="recycleFob(fob.id)">
                            Recycle
                        </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!tokens || tokens.length == 0">
                    <td class="text-center" colspan="5">No Data found.</td>
                </tr>
                <tr>
                <td colspan="5">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-table bkg-primary text-white" :disabled="tokens && tokens.length > 0"  @click="assignNewFobPopup()">
                            + Assign new FOB
                        </button>
                        <i class="fa fa-info-circle select-cursor" v-if="tokens && tokens.length > 0" aria-hidden="true" title="Recycle the already assigned fob to assign a new fob"></i>
                    </div>
                </td>
                </tr>
            </table>
            </div>
        </div>

		<assignNewFobPopup ref="assignNewFobPopup" />
    </div>
</template>

<script>


import assignNewFobPopup from "./assignNewFobModal.vue";
export default {
    components: {
        assignNewFobPopup
    },
    props :["tokens","user","building_id","unit_id"],
    watch: {
        // building_id: function (newBuildingId, oldBuildingId) {
        //     this.loadData();
        // }
    },
    data() {
        return {
            
        };
    },
    computed: {
        
    },
    methods: {
        assignNewFobPopup(){
            this.$refs.assignNewFobPopup.form.reset();
            this.$refs.assignNewFobPopup.loadTokens(this.building_id,this.user.id);
            this.$refs.assignNewFobPopup.form.user_id = this.user.id;
            this.$refs.assignNewFobPopup.form.building_id = this.building_id;
            this.$refs.assignNewFobPopup.form.unit_id = this.unit_id;
            $('#assignNewFobModal').modal('show');
        },
        recycleFob(id){
            this.$refs.assignNewFobPopup.form.reset();
            this.$refs.assignNewFobPopup.form.user_id = this.user.id;
            this.$refs.assignNewFobPopup.form.building_id = this.building_id;
            this.$refs.assignNewFobPopup.form.unit_id = this.unit_id;
            this.$refs.assignNewFobPopup.form.prev_token_id = id;
            this.$refs.assignNewFobPopup.submitForm(true);
        },
        reloadData(){
            this.$parent.reloadData();
        },
        
        
        viewEntrances(){
            this.showLoader();
            this.$http
                .get("/getEntrancesList/"+this.unit_id)
                .then((response) => {
                    console.log(response);
                    this.popupHtml = response.data.html;
                    $('#modal-0').modal('show');
                    this.removeLoader();
                })
                .catch((error) => {
                    this.removeLoader();
                    console.error(error);
                });
        },

    },
};
</script>
