<template>
  <div>
    <div
      class="modal fade send-work-order-modal"
      id="sendWorkOrder"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
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
            <form
              @submit.prevent="addWorkOrder"
              @keydown="form.onKeydown($event)"
              enctype="multipart/form-data"
            >
              <alert-error :form="form"></alert-error>
              <div class="row">
                <div class="col-md-6 mx-auto">
                  <h1 class="h1 mb-13">Send Work Order</h1>
                  <!-- <p class="text-medium color-secondary">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Officiis quisquam numquam corporis non libero sapiente cum
                    sit, quam voluptatem ab!
                  </p> -->

                  <div class="row">
                    <div class="multiselect-border col-lg-6 mb-6">
                      <div class="dropdown-wrapper">
                        <label class="dropdown-label">Building</label>
                        <multiselect 
                          :selectLabel="''"
                          :deselectLabel= "''"
                          v-model="form.building"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :options="buildings"
                          @input="loadUnits"
                        ></multiselect>
                      </div>

                      <has-error :form="form" field="building"></has-error>
                    </div>

                    <div class="multiselect-border col-lg-6 mb-6">
                      <div class="dropdown-wrapper">
                        <label class="dropdown-label text-capitalize"
                          >Units</label
                        >
                        <multiselect 
                          :selectLabel="''"
                          :deselectLabel= "''"
                          v-model="form.unit"
                          placeholder=""
                          label="unit_no"
                          track-by="id"
                          :options="units"
                          @input="loadResidents"
                        ></multiselect>
                      </div>

                      <has-error :form="form" field="unit"></has-error>
                    </div>

                    <div class="multiselect-border col-lg-6 mb-6">
                      <div class="dropdown-wrapper">
                        <label class="dropdown-label text-capitalize"
                          >Resident</label
                        >
                        <multiselect 
                          :selectLabel="''"
                          :deselectLabel= "''"
                          v-model="form.resident_id"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :options="residents"
                        ></multiselect>
                      </div>

                      <has-error :form="form" field="unit"></has-error>
                    </div>

                    <div class="multiselect-border col-lg-6 mb-6">
                      <div class="dropdown-wrapper">
                        <label class="dropdown-label text-capitalize"
                          >Status</label
                        >
                        <multiselect 
                          :selectLabel="''"
                          :deselectLabel= "''"
                          v-model="form.status_id"
                          placeholder=""
                          label="name"
                          track-by="id"
                          :options="statuses"
                        ></multiselect>
                      </div>

                      <has-error :form="form" field="status_id"></has-error>
                    </div>
                  </div>

                  <div class="multiselect-border multiselect-bold mb-16">
                    <label for="subject" class="text-normal-bold-md color-secondary mb-12">
                      Work Type
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                        :selectLabel="''"
                        :deselectLabel= "''"
                        v-model="work_type"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :multiple="false"
                        :options="workTypes"
                        @input="loadMainteneanceUsers"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="assign_maint"></has-error>
                  </div>


                  <div class="multiselect-border multiselect-bold mb-16">
                    <label
                      for="subject"
                      class="text-normal-bold-md color-secondary mb-12"
                    >
                      Maintenance in charge
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                        :selectLabel="''"
                        :deselectLabel= "''"
                        v-model="form.assign_maint"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :multiple="false"
                        :options="maintainersUsers"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="assign_maint"></has-error>
                  </div>
                  <div class="multiselect-border multiselect-bold mb-16">
                    <label
                      for="subject"
                      class="text-normal-bold-md color-secondary mb-12"
                    >
                      Watching manager
                    </label>
                    <div class="dropdown-wrapper">
                      <multiselect 
                        :selectLabel="''"
                        :deselectLabel= "''"
                        v-model="form.watcher"
                        placeholder=""
                        label="name"
                        track-by="id"
                        :multiple="false"
                        :options="maintainersList"
                      ></multiselect>
                    </div>
                    <has-error :form="form" field="watcher"></has-error>
                  </div>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label
                        for="subject"
                        class="text-normal-bold-md color-secondary mb-12"
                      >
                        Subject
                      </label>
                      <div>
                        <input
                          type="text"
                          id="subject"
                          class="form-control"
                          v-model="form.subject"
                        />
                      </div>
                      <has-error :form="form" field="subject"></has-error>
                    </div>
                  </div>
                  <div id="text-area" class="mb-6">
                    <p class="text-normal-bold-md color-secondary mb-12">
                      Instructions
                    </p>
                    <textarea
                      rows="8"
                      class="w-100"
                      v-model="form.description"
                    ></textarea>
                    <has-error :form="form" field="description"></has-error>
                  </div>
                  <div class="row">
                    <div class="col-">
                      <p class="text-normal-bold-md color-secondary mb-12">
                        Attachments
                      </p>
                      <label class="custom-file-upload mb-6">
                        <input
                          type="file"
                          id="work-order-attachment"
                          multiple
                          @change="attachFile('#work-order-attachment')"
                        />
                        <div class="d-flex justify-content-between">
                          <div class="d-flex align-self-center">
                            <div class="align-self-center">
                              <img
                                src="/images/icons/attachment.png"
                                class="me-2"
                                alt=""
                              />
                            </div>
                            <div class="text-medium align-self-center">
                              Attachment
                            </div>
                          </div>

                          <div class="align-self-center">+</div>
                        </div>
                      </label>
                      <div class="mb-12">{{ attachedImages }}</div>
                    </div>
                  </div>
                  <div>
                    <div class="row gy-3">
                      <div class="multiselect-border col-lg-6 mb-6">
                        <label class="text-normal-bold-md color-secondary">Priority</label>
                        <div class="dropdown-wrapper">
                          <multiselect 
                            :selectLabel="''"
                            :deselectLabel= "''"
                            v-model="form.priority"
                            placeholder=""
                            label="name"
                            track-by="id"
                            :options="priorities"
                          ></multiselect>
                        </div>

                        <has-error :form="form" field="priority"></has-error>
                      </div>

                      <!-- <div class="multiselect-border col-lg-6 mb-6">
                        <div class="dropdown-wrapper">
                          <label class="dropdown-label text-capitalize"
                            >Type</label
                          >
                          <multiselect 
                            :selectLabel="''"
                            :deselectLabel= "''"
                            v-model="form.issue_type_id"
                            placeholder=""
                            label="name"
                            track-by="id"
                            :options="issueTypes"
                          ></multiselect>
                        </div>
                        <has-error :form="form" field="priority"></has-error>
                      </div> -->


                      <div class="col-lg-6 mb-6">
                        <label for="subject" class="text-normal-bold-md color-secondary">
                          No. of Days
                        </label>
                        <div>
                          <input
                            type="number"
                            id="expire_days"
                            class="form-control"
                            v-model="form.expire_days"
                          />
                        </div>
                        <has-error :form="form" field="expire_days"></has-error>
                      </div>

                      <div class="col-lg-6 mb-6">
                        <label for="subject" class="text-normal-bold-md color-secondary">
                          How long has this been an issue?
                        </label>
                        <div>
                          <input
                            type="text"
                            id="how_long_issue"
                            class="form-control"
                            v-model="form.how_long_issue"
                          />
                        </div>
                        <has-error :form="form" field="how_long_issue"></has-error>
                      </div>

                      <p class="text-normal-bold-md color-secondary mb-12">
                        What time you will be available?
                      </p>
                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Monday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.mon.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.mon.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.mon.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Tuesday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.tue.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.tue.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.tue.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Wednesday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.wed.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.wed.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.wed.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Thursday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.thu.from">
                            <option value="" disabled></option>
                            <option :value="time"  v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.thu.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.thu.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Friday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.fri.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.fri.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.fri.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Saturday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.sat.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.sat.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.sat.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-3 pt-2">
                          <span class="color-secondary">Sunday:</span>
                        </div>
                        <div class="col-lg-2 pt-2">
                          <span class="color-secondary">From</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.sun.from">
                            <option value="" disabled></option>
                            <option :value="time" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                        <div class="col-lg-2 pt-2 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-2">
                          <select class="form-control" v-model="form.time_available.sun.to">
                            <option value="" disabled></option>
                            <option :value="time" :disabled="isToOptionDisabled(time,form.time_available.sun.from)" v-for="time in times">{{ time }}</option>
                          </select>
                        </div>
                      </div>

                    </div>
                  </div>
                  

                  <div class="d-flex justify-content-center">
                    <button class="btn btn-dark me-12" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button class="btn bkg-success" type="submit">Send</button>
                  </div>
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
  components: {},
  data() {
    return {
      buildingArray: ["Building 1", "building 2", "building 3", "building 4"],
      status: ["Status 1", "Status 2", "Status 3", "Status 4"],
      buildingUnit: ["A block", "B Block", "C Block 3", "D Block"],
      priority: ["Hign", "Medium", "Low"],
      dataArray: [
        { id: 1, name: "john doe", image: "/images/worker.png" },
        { id: 2, name: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, name: "jane doe 2", image: "/images/Ellipse14.png" },

        { id: 4, name: "john doe", image: "/images/worker.png" },
        { id: 5, name: "jane doe", image: "/images/Ellipse14.png" },
        { id: 6, name: "jane doe 2", image: "/images/Ellipse1.png" },
      ],
      statuses: [
        { id: "Open", name: "Open" },
        { id: "Inprogress", name: "Inprogress" },
        { id: "Resolved", name: "Resolved" },
        { id: "Close", name: "Close" },
        { id: "Reopen", name: "Reopen" },
        { id: "Due", name: "Due" },
      ],
      priorities: [
        { id: "Low", name: "Low" },
        { id: "Medium", name: "Medium" },
        { id: "High", name: "High" },
      ],
      form: new Form({
        assign_maint: "",
        watcher: "",
        subject: "",
        description: "",
        images: [],
        issue_type_id: "",
        resident_id: "",
        status_id: "",
        priority: "",
        expire_days: 0,
        building: "",
        unit: "",
        time_available : {
          mon : { from : '',to : '' },
          tue : { from : '',to : '' },
          wed : { from : '',to : '' },
          thu : { from : '',to : '' },
          fri : { from : '',to : '' },
          sat : { from : '',to : '' },
          sun : { from : '',to : '' },
        },
        how_long_issue : '',
        alt_phone : ''
      }),
      buildings: [],
      maintainersList: [],
      units: [],
      issueTypes: [],
      attachedImages: "",
      residents: [],

      times : [],
      workTypes : [],
      work_type : '',
      maintainersUsers : []
    };
  },
  methods: {
    fetchWorkTypes(){
      this.$http
        .post("/loadSubRoles")
        .then((response) => {
          this.workTypes = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    loadMainteneanceUsers(){
      let building_id = this.form.building ? this.form.building.id : '';
      let sub_role = this.work_type ? this.work_type.alias : '';
      const params = {building_id : building_id, sub_role : sub_role}
      this.$http
        .get("/getMaintenanceUsers",{params})
        .then((response) => {
          this.maintainersUsers = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    isToOptionDisabled(selectedTime, fromTime) {
      if (!fromTime || !selectedTime) {
        return false; // Don't disable if either field is not selected
      }

      // Compare times to check if "To" time is in the past
      return fromTime >= selectedTime;
    },
    loadResidents() {
      this.$http
        .get("/getResidentsByUnit/" + this.form.unit.id)
        .then((response) => {
          this.residents = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    attachFile(id) {
      let files = this.getAttachedFilesObjects(id);
      this.form.images = files;
      const names = files.map((item) => item.name);
      if (names.length > 1) {
        this.attachedImages = names.join(", ");
      } else if (names.length === 1) {
        this.attachedImages = names[0];
      } else {
        this.attachedImages = "";
      }
      console.log(this.attachedImages, names);
    },
    getBuildings() {
      this.$http
        .get("/buildings")
        .then((response) => {
          this.buildings = response.data.data;
          if (this.$gate.building_id > 0) {
            this.form.building = this.buildings.find(
              (obj) => obj.id === this.$gate.building_id
            );
            this.units = this.form.building.units;
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getWatcherAndMaintainerList(id = 0) {
      this.$http
        .get("/getMaintenanceAndWatchersList/"+id)
        .then((response) => {
          this.maintainersList = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getIssueTypes() {
      this.$http
        .get("/getIssueTypes")
        .then((response) => {
          this.issueTypes = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    loadUnits() {
      this.form.maintainersUsers = [];
      this.form.assign_maint = '';
      this.work_type = '';
      this.form.unit = '';
      this.form.watcher = '';
      this.units = this.form.building.units;
      this.getWatcherAndMaintainerList(this.form.building.id);
    },
    addWorkOrder() {
      this.showLoader();
      console.log(this.form);
      this.form.assign_maint = this.form.assign_maint.id;
      this.form.watcher = this.form.watcher.id;
      this.form.status_id = this.form.status_id.id;
      this.form.priority = this.form.priority.id;
      this.form.building = this.form.building.id;
      this.form.unit = this.form.unit.id;
      this.form.issue_type_id = this.form.issue_type_id.id;
      this.form.resident_id = this.form.resident_id.id;
      this.form
        .post("/api/addWorkOrder")
        .then((response) => {
          this.form.reset();
          $("#sendWorkOrder").modal("hide");
          Toast.fire({
            icon: "success",
            title: "Work Order created successfully.",
          });
          this.$parent.toggleBtn(1);
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
  },
  created() {
    for (let hour = 0; hour < 24; hour++) {
      for (let minute = 0; minute < 60; minute += 30) {
        const formattedTime = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
        this.times.push(formattedTime);
      }
    }
  },
  mounted(){

  }
};
</script>
