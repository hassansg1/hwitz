<template>
  <div class="f-wallets">
    <div class="mb-28">
      <h3 class="h3 mb-30">Receiveables</h3>
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>Type</th>
              <th>Rent Cart</th>
              <th>Amenities Cart</th>
              <th>Laundry Cart</th>
            </tr>

            <template v-for="(data, index) in receivableBuildings">
              <tr>
                <td class="text-medium color-secondary width-33">{{ data.name }}</td>
                <td class="text-medium color-secondary">
                  <div class="multiselect-border">
                    <div class="dropdown-wrapper" :class="index % 2 != 0 ? 'mus-odd' : 'mus-even'">
                      <label class="dropdown-label text-capitalize">Rent Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="data.selected_rent_cart"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :options="receivableRentCartOptions"
                        transSourceId="3"
                        :buildingId="data.id"
                        defaultAreaStatus="5"
                        @input="updateRentStatus(data,3,5,'r','s','rent')"
                      ></multiselect>
                    </div>
                  </div>
                </td>
                <td class="text-medium color-secondary">
                  <div class="multiselect-border">
                    <div class="dropdown-wrapper" :class="index % 2 != 0 ? 'mus-odd' : 'mus-even'">
                      <label class="dropdown-label text-capitalize">Amenities Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="data.selected_amenities_cart"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :options="receivableAmentiesCartOptions"
                        @input="updateRentStatus(data,2,5,'r','s','amenities')"
                      ></multiselect>
                    </div>
                  </div>
                </td>
                <td class="text-medium color-secondary">
                  <div class="multiselect-border">
                    <div class="dropdown-wrapper" :class="index % 2 != 0 ? 'mus-odd' : 'mus-even'">
                      <label class="dropdown-label text-capitalize">Laundry Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="data.selected_laundry_cart"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :options="receivableLaundryCartOptions"
                        @input="updateRentStatus(data,4,5,'r','s','laundry')"
                      ></multiselect>
                    </div>
                  </div>
                </td>
              </tr>
              <!-- <tr v-if="showRDetail && showRTable === index">
                <td colspan="4" class="text-normal">
                  Detail will display here!
                </td>
              </tr> -->
            </template>
          </table>
        </div>
      </div>
    </div>

    <div class="mb-28">
      <h3 class="h3 mb-30">Payables</h3>
      <div class="text-start">
        <div class="table-responsive">
          <table>
            <tr>
              <th>Type</th>
              <th>Rent Cart</th>
              <th>Amenities Cart</th>
            </tr>

            <template v-for="(data, index) in payableBuildings">
              <tr>
                <td class="text-medium color-secondary width-33">{{ data.name }}</td>
                <td class="text-medium color-secondary width-33">
                  <div class="multiselect-border">
                    <div class="dropdown-wrapper" :class="index % 2 != 0 ? 'mus-odd' : 'mus-even'">
                      <label class="dropdown-label text-capitalize">Rent Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="data.selected_rent_cart"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :options="payableRentCartOptions"
                        @input="updateRentStatus(data,3,4,'p','s','rent')"
                      ></multiselect>
                    </div>
                  </div>
                </td>
                <td class="text-medium color-secondary width-33">
                  <div class="multiselect-border">
                    <div class="dropdown-wrapper" :class="index % 2 != 0 ? 'mus-odd' : 'mus-even'">
                      <label class="dropdown-label text-capitalize">Amenities Cart</label>
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                        v-model="data.selected_amenities_cart"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :options="payableAmentiesCartOptions"
                        @input="updateRentStatus(data,2,4,'p','s','amenities')"
                      ></multiselect>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      buildings : [],
      showRDetail: false,
      showPDetail: false,
      showRTable: 0,
      showPTable: 0,
      
      receivableWalletResponse : [],
      receivableRentCartOptions : [],
      receivableAmentiesCartOptions : [],
      receivableLaundryCartOptions : [],
      receivableBuildings : [],


      payableWalletResponse : [],
      payableRentCartOptions : [],
      payableAmentiesCartOptions : [],
      payableLaundryCartOptions : [],
      payableBuildings : [],
      

    };
  },
  methods: {
    updateRentStatus(building,transSourceId,defaultAreaStatus,portfolio,origin,type){
      const buildingId = building.id;
      let value = 0;
      if(type == 'rent') value = building.selected_rent_cart ? building.selected_rent_cart.id : 0;
      else if(type == 'amenities') value = building.selected_amenities_cart ? building.selected_amenities_cart.id : 0;
      else if(type == 'laundry') value = building.selected_laundry_cart ? building.selected_laundry_cart.id : 0;
      console.log(value);

      // buildingAccountAssignment/{buildingId}/{walletId}/{userId}/{transSourceId}/{portfolio}/{defaultAreaStatus}
      this.$http
        .get("/buildingAccountAssignment/"+buildingId+'/'+value+'/'+this.$gate.user.id+'/'+transSourceId+'/'+portfolio+'/'+defaultAreaStatus)
        .then((response) => {
          Toast.fire({icon: "success",title: response.data});
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    showPayDetails(index) {
      this.showPDetail = !this.showPDetail;
      this.showPTable = index;
    },

    showReceiveDetails(index) {
      this.showRDetail = !this.showRDetail;
      this.showRTable = index;
    },
    getPayableWallets(){
      this.showLoader();
      this.$http
        .post("/getMyAllWallets/"+this.$gate.user.id+'/p/s')
        .then((response) => {
          this.payableBuildings = response.data.usersAllBuildings;
          this.payableWalletResponse = response.data;

          this.payableRentCartOptions = response.data.usersAllRentWallets.map(item => {
            const { id, bnk_acc_no, card_number } = item;
            const name = bnk_acc_no ? bnk_acc_no : card_number;
            return { id, name };
          });
          
          this.payableAmentiesCartOptions = response.data.usersAllAmenitiesWallets.map(item => {
            const { id, bnk_acc_no, card_number } = item;
            const name = bnk_acc_no ? bnk_acc_no : card_number;
            return { id, name };
          });
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getReceivableWallets(){
      this.showLoader();
      this.$http
        .post("/getMyAllWallets/"+this.$gate.user.id+'/r/s')
        .then((response) => {
          this.receivableBuildings = response.data.usersAllBuildings;
          this.receivableWalletResponse = response.data;

          this.receivableRentCartOptions = response.data.usersAllRentWallets.map(item => {
            const { id, bnk_acc_no, card_number } = item;
            const name = bnk_acc_no ? bnk_acc_no : card_number;
            return { id, name };
          });
          
          this.receivableAmentiesCartOptions = response.data.usersAllAmenitiesWallets.map(item => {
            const { id, bnk_acc_no, card_number } = item;
            const name = bnk_acc_no ? bnk_acc_no : card_number;
            return { id, name };
          });

          this.receivableLaundryCartOptions = response.data.usersAllLaundryWallets.map(item => {
            const { id, bnk_acc_no, card_number } = item;
            const name = bnk_acc_no ? bnk_acc_no : card_number;
            return { id, name };
          });
          
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    }
  },
};
</script>
<style>
  tr:hover{
    background : unset !important;
  }
  tr:nth-child(odd) {
    background: unset;
  }
</style>