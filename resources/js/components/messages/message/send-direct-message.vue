<template>
  <div>
    <div class="row">
      <div class="col-md-6 mx-auto">
        <h1 class="h1">Send Direct Message</h1>
        <p class="text-medium color-secondary">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis
          quisquam numquam corporis non libero sapiente cum sit, quam voluptatem
          ab!
        </p>

        <div class="multiselect-area">
          <RichMultiSelect :optionsArray="dataArray" :header="'Recipients'" />
        </div>

        <div id="text-area">
          <p class="text-medium color-secondary">Message</p>
          <textarea rows="8" class="w-100"></textarea>x
        </div>

        <div class="d-flex justify-content-evenly">
          <button class="btn btn-dark">Cancel</button>
          <button class="btn bkg-success">Send Message</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RichMultiSelect from "../../utils/rich-multi-select.vue";

export default {
  components: {
    RichMultiSelect,
  },
  data() {
    return {
      dataArray: [
        { id: 1, name: "john doe", image: "/images/worker.png" },
        { id: 2, name: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, name: "jane doe 2", image: "/images/Ellipse14.png" },

        { id: 4, name: "john doe", image: "/images/worker.png" },
        { id: 5, name: "jane doe", image: "/images/Ellipse14.png" },
        { id: 6, name: "jane doe 2", image: "/images/Ellipse1.png" },
      ],
    };
  },
};
</script>
