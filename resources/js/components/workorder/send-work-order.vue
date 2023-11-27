<template>
  <div class="send-work-order-modal">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <h1 class="h1 mb-13">Send Work Order</h1>
        <!-- <p class="text-medium color-secondary">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis
          quisquam numquam corporis non libero sapiente cum sit, quam voluptatem
          ab!
        </p> -->

        <div class="mb-6">
          <div class="dropdown-wrapper">
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="form.assign_maint"
              placeholder="Select maintenance in charge"
              label="name"
              track-by="id"
              :options="dataArray"
            ></multiselect>
          </div>
        </div>

        <div class="mb-6">
          <div class="multiselect-area">
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="form.watcher"
              placeholder="Select a watcher"
              label="name"
              track-by="id"
              :options="dataArray"
            ></multiselect>
          </div>
        </div>

        <div id="text-area" class="mb-6">
          <p class="text-normal-bold-md color-secondary mb-12">Instructions</p>
          <textarea rows="8" class="w-100"></textarea>
        </div>

        <p class="text-normal-bold-md color-secondary mb-12">Attachments</p>
        <label class="custom-file-upload mb-6">
          <input type="file" />
          <div class="d-flex justify-content-between">
            <div class="d-flex align-self-center">
              <div class="align-self-center">
                <img src="/images/icons/attachment.png" class="me-2" alt="" />
              </div>
              <div class="text-medium align-self-center mb-28">Attachment</div>
            </div>

            <div class="align-self-center">+</div>
          </div>
        </label>

        <div>
          <div class="row">
            <div class="col-lg-6 mb-6">
              <div class="multiselect-area">
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="form.watcher"
                  placeholder="Select a building"
                  label="name"
                  track-by="id"
                  :options="dataArray"
                ></multiselect>
              </div>
            </div>

            <div class="col-lg-6 mb-6">
              <div class="multiselect-area">
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="form.watcher"
                  placeholder="Select a unit"
                  label="name"
                  track-by="id"
                  :options="dataArray"
                ></multiselect>
              </div>
            </div>

            <div class="col-lg-6 mb-6">
              <div class="multiselect-area">
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="form.watcher"
                  placeholder="Select status"
                  label="name"
                  track-by="id"
                  :options="dataArray"
                ></multiselect>
              </div>
            </div>

            <div class="col-lg-6 mb-6">
              <div class="multiselect-area">
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="form.watcher"
                  placeholder="Select priority"
                  label="name"
                  track-by="id"
                  :options="dataArray"
                ></multiselect>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-evenly">
          <button class="btn btn-dark">Cancel</button>
          <button class="btn bkg-success">Send</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MultipleSelect from "../multi-select.vue";

export default {
  components: {
    MultipleSelect,
  },
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
      form: new Form({
        assign_maint: "",
        watcher: "",
        subject: "",
        description: "",
        images: "",
        issue_type_id: "",
        resident: "",
        status_id: "",
        priority: "",
        expire_days: 0,
      }),
    };
  },
};
</script>
