<template>
  <div class="main verification">
    <div class="mb-32">
      <div class="row">
        <div class="col-lg-3 mb-6 multiselect-border">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Building</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="selectedBuilding"
              placeholder=""
              label="name"
              track-by="id"
              :options="buildings"
              :multiple="false"
              @input="getResidentsByFilters"
            ></multiselect>
          </div>
        </div>
      </div>
      <div class="d-flex">
        <div :class="residents.length == 0 ? 'cursor-not-allowed' : ''">
          <input
            class="form-check-input me-12"
            type="checkbox"
            id="flexCheckDefault1"
            @change="checkAll"
            :disabled="residents.length == 0"
          />
        </div>
        <div class="align-self-center">
          <label class="form-check-label text-normal" for="flexCheckDefault">
            Check All Residents
          </label>
        </div>
      </div>
    </div>
    <div class="row gy-5 gx-5 mb-20 mx-0">
      <div class="col-lg-4 col-md-6 col-12">
        <div class="row bkg-secondary color-secondary">
          <div class="col-2"></div>
          <div class="col-10">
            <div class="py-7">Name</div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12 d-none d-md-block">
        <div class="row bkg-secondary color-secondary">
          <div class="col-2"></div>
          <div class="col-10">
            <div class="py-7">Name</div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12 d-none d-lg-block">
        <div class="row bkg-secondary color-secondary">
          <div class="col-2"></div>
          <div class="col-10">
            <div class="py-7">Name</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-20 mx-0">
      <div class="col-lg-4 col-md-6 col-12" v-for="user in residents">
        <div class="row mx-0 border-bottom">
          <div class="col-2">
            <div class="py-7">
              <input class="form-check-input me-12" type="checkbox" :value="user.id" v-model="checkedUsers" :id="'flexCheckDefault' + user.id"/>
            </div>
          </div>

          <div class="col-10">
            <div class="d-flex py-7">
              <div class="align-self-center">
                <img :src=" user.profile_pic && user.profile_pic !== '' ? user.profile_pic : '/images/default-user.jpg'" class="avatar-sm me-8" alt="worker"/>
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">{{ user.name }}</p>
                <p class="text-normal mb-0 color-danger" v-if="user.mobile_verification === 'No' && user.email_verified === 0">
                  Mobile and email unverified
                </p>
                <p class="text-normal mb-0 color-danger" v-else-if="user.mobile_verification === 'No'">
                  Email unverified
                </p>
                <p class="text-normal mb-0 color-danger" v-else-if="user.email_verified === 0">
                  Email unverified
                </p>
                <p class="text-normal mb-0 color-primary" v-else>Verified</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end">
      <button
        class="btn bkg-primary text-white"
        @click="openVerificationModal"
        :disabled="checkedUsers.length == 0"
      >
        Continue
      </button>
    </div>

    <!-- modal design -->
    <!-- <div class="row">
      <div class="col-lg-6 mx-auto">
        <h1 class="h1 mb-13">Verification Broadcast</h1>
        <p class="mb-28 text-medium color-secondary">
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Architecto
          repellendus doloribus odio dignissimos expedita rem!
        </p>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">
            Select building
          </p>

          <div class="d-flex flex-wrap">
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>

            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>

            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N Addison sjdhfjs
            </span>
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N Addison dfg</span
            >
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N hhh h hhAddison</span
            >
          </div>
        </div>

        <div class="row mb-24">
          <div class="col-lg-9">
            <label class="text-normal-bold-md mb-12 color-secondary" for="title"
              >Title</label
            >
            <input id="title" type="text" class="form-control" />
          </div>
          <div class="col-lg-3">
            <label class="text-normal-bold-md mb-12 color-secondary" for="date"
              >Date</label
            >
            <input id="date" type="text" class="form-control" />
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">Message type</p>
          <div>
            <span class="custome-badge active">Both</span>
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">Message</p>
          <div>
            <textarea name="" id="" rows="5"></textarea>
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">
            Service de-activation date
          </p>
          <div class="row">
            <div class="col-lg-4">
              <input type="text" class="form-control" />
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-evenly">
          <button class="btn btn-dark">Reset</button>
          <button class="btn btn-primary">Send Verification</button>
        </div>
      </div>
    </div> -->

    <VerificationModal ref="verificalModal" :users="checkedUsers" />
  </div>
</template>
<script>
import VerificationModal from "./verification-modal.vue";

export default {
  components: {
    VerificationModal,
  },
  data() {
    return {
      params: {
        usertype_id: "all",
        template_type: "v",
        parking: 0,
        tv: 0,
        phone: 0,
        internet: 0,
        package: 0,
        building_id: "",
        unverified: true,
      },
      buildings: [],
      residents: "",
      selectedBuilding: "",
      checkedUsers: [],
    };
  },
  mounted() {},
  methods: {
    checkAll(event){
      if(event.target.checked){
        this.checkedUsers = this.residents.map(function (value, index, array) {
          return value.id; 
        });
      }else{
        this.checkedUsers = [];
      }
    },  
    openVerificationModal() {
      this.$refs.verificalModal.loadTemplates();
      this.$refs.verificalModal.form.users = this.checkedUsers;
      this.$refs.verificalModal.form.building_id = this.params.building_id;
      $("#verificationModal").modal("show");
    },
    getResidentsByFilters() {
      if (!this.selectedBuilding) {
        this.removeLoader();
        return;
      }
      this.showLoader();
      this.params.building_id = this.selectedBuilding
        ? this.selectedBuilding.id
        : 0;
      this.$http
        .post("/workorder/getResidentsByFilters", this.params)
        .then((response) => {
          this.residents =
            response.data && response.data.all && response.data.all.length > 0
              ? response.data.all
              : [];
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getBuildings() {
      this.showLoader();
      this.$http
        .get("/buildings")
        .then((response) => {
          this.buildings = response.data.data;
          this.selectedBuilding = this.buildings.find(
            (obj) => obj.id === this.$gate.building_id
          );
          this.getResidentsByFilters();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
