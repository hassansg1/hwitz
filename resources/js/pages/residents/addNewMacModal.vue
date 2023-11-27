<template>
  <div>
    <div
        class="modal fade"
        :id="'addNewMacmodal'"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="row">
              <form @submit.prevent="addNewMac" id="broadcastMessageForm" @keydown="form.onKeydown($event)"
                    enctype="multipart/form-data">
                <div class="col-lg-6 mx-auto">
                  <h1 class="h1 mb-13">Add new mac</h1>
                  <alert-error :form="form"></alert-error>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Type
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                          v-model="connection_type"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :multiple="false"
                          :options="connectionTypes"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="connection_type"></has-error>
                  </div>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Device
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                          v-model="device_type"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :multiple="false"
                          :options="deviceTypes"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="device_type"></has-error>
                  </div>

                  <div class="row"
                       v-if="this.form.device_type && this.form.device_type.id === 'Other'">
                    <div class="col-12 mb-3">
                      <div>
                        <input type="text" id="other" required class="form-control mac-address" v-model="form.other"/>
                      </div>
                      <has-error :form="form" field="other"></has-error>
                    </div>
                  </div>

                  <div v-if="units.length > 0" class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Unit
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                          v-model="unit"
                          placeholder=""
                          label="unit_no"
                          track-by="id"
                          :multiple="false"
                          :options="units"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="type"></has-error>
                  </div>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Location
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                          v-model="location"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :multiple="false"
                          :options="locations"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="location"></has-error>
                  </div>

                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                        MAC Address
                      </label>
                      <div>
                        <input type="text" id="subject" class="form-control mac-address" v-model="form.mac_address"/>
                      </div>
                      <has-error :form="form" field="mac_address"></has-error>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-dark me-12" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button class="btn bkg-success" type="submit">Add</button>
                  </div>
                </div>
              </form>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  mounted() {
    $('.mac-address').mask('AA:AA:AA:AA:AA:AA', {
      'translation': {
        A: {pattern: /[A-Fa-f0-9]/}
      }
    });
  },
  data() {
    return {
      form: new Form({
        connection_type: '',
        mac_address: '',
        device_type: '',
        unit_id: '',
        location: '',
        user_id: '',
        building_id: '',
        other: ''
      }),


      connection_type : '',
      device_type : '',
      unit : '',
      location : '',

      unit_id: '',
      templates: [],
      template: '',

      deviceTypes: [
        {id: 'Smart TV', name: 'Smart TV'},
        {id: 'Xbox', name: 'Xbox'},
        {id: 'PlayStation', name: 'PlayStation'},
        {id: 'Fire TV Stick', name: 'Fire TV Stick'},
        {id: 'Chromecast', name: 'Chromecast'},
        {id: 'Roku', name: 'Roku'},
        {id: 'Other', name: 'Other'}
      ],
      connectionTypes: [
        {id: "Wired", name: "Wired"},
        {id: "Wireless", name: "Wireless"}
      ],
      locations: [
        {id: 'Kit', name: 'Kit'},
        {id: 'LR', name: 'LR'},
        {id: 'DR', name: 'DR'},
        {id: 'FamR', name: 'FamR'},
        {id: 'RecRm', name: 'RecRm'},
        {id: 'MstrBr', name: 'MstrBr'},
        {id: 'BR2', name: 'BR2'},
        {id: 'BR3', name: 'BR3'},
        {id: 'BR4', name: 'BR4'},
        {id: 'Other', name: 'Other'}
      ],
      units: []


    };
  },
  methods: {
    addNewMac() {
      this.showLoader();
      let connection_type = this.connection_type;
      let device_type = this.device_type;
      let unit_id = this.unit;
      let location = this.location;

      this.form.connection_type = this.connection_type ? this.connection_type.id : '';
      ;
      this.form.device_type = this.device_type ? this.device_type.id : '';
      if (this.unit_id === '')
        this.form.unit_id = this.unit ? this.unit.id : '';
      else
        this.form.unit_id = this.unit_id;

      this.form.location = this.location ? this.location.id : '';

      this.form
          .post("/api/addNewMacAddress")
          .then((response) => {
            this.$parent.reloadData();
            Toast.fire({
              icon: response.data.status,
              title: response.data.message,
            });
            this.form.reset();
            $("#addNewMacmodal").modal("hide");
            this.removeLoader();
          })
          .catch((error) => {
            this.form.connection_type = connection_type;
            this.form.device_type = device_type;
            this.form.unit_id = unit_id;
            this.form.location = location;
            this.removeLoader();
          });
    }
  },
};
</script>

  