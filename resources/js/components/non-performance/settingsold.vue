<template>
  <div class="non-performance-settings">
    <div class="row g-3">
      <div class="col-lg-9">
        <div class="mb-4">
          <h3 class="h3">Set up up your default sequence</h3>
          <p class="text-medium color-secondary">
            Lorem ipsum, dolor sit amet consectetur adipisicing elit.
            Necessitatibus in dolorum, incidunt porro quis delectus blanditiis
            numquam natus illum deleniti, atque odit amet aspernatur assumenda
            recusandae quidem consectetur voluptates labore!
          </p>
        </div>

        <div class="mb-5">
          <h3 class="h3">Alert Fields</h3>

          <div class="row gy-3">
            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize"
                  >First notice</label
                >
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value1"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options1"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>

            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize"
                  >Second notice</label
                >
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value2"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options2"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>

            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize"
                  >Third notice</label
                >
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value3"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options3"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-5">
          <h3 class="h3">Eviction Sequence</h3>

          <div class="row gy-3">
            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize">Building</label>
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value4"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options4"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>

            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize">Building</label>
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value5"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options5"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>

            <div class="multiselect-border-shadow col-xl-3 col-lg-4 col-md-6">
              <div class="dropdown-wrapper">
                <label class="dropdown-label text-capitalize">Building</label>
                <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                  v-model="value6"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="code"
                  :options="options6"
                  :multiple="false"
                ></multiselect>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-5">
          <h3 class="h3">Blocked Services</h3>

          <div class="row">
            <div class="col-3">
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Wi-Fi
              </p>
            </div>
            <div class="col-3">
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Tv
              </p>
            </div>
            <div class="col-3">
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Laundry
              </p>
            </div>
            <div class="col-3">
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Calls
              </p>
            </div>
            <div class="col-3">
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                some
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div
          class="d-flex justify-content-between"
          data-bs-toggle="collapse"
          data-bs-target="#collapseSection2"
          aria-expanded="false"
          aria-controls="collapseSection2"
        >
          <div>
            <h3 class="h3">Permissions</h3>
          </div>
          <div class="align-self-center">
            <img src="/images/icons/collapse.png" alt="collapse" />
          </div>
        </div>

        <div class="collapse" id="collapseSection2">
          <p class="text-medium mb-4">
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sequi
            accusantium assumenda quibusdam asperiores est, rerum illo molestias
            perferendis facilis, vitae accusamus molestiae porro eius numquam
            exercitationem, minus consectetur optio vel!
          </p>

          <div class="mb-4">
            <p class="text-uppercase text-normal-bold-md color-secondary">
              services to block
            </p>
            <div>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Wi-Fi
              </p>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Laundry
              </p>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Storage
              </p>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Tv
              </p>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Calls
              </p>
            </div>
          </div>

          <div class="mb-4">
            <p class="text-uppercase text-normal-bold-md color-secondary">
              type of alert
            </p>
            <div>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Text Message
              </p>
              <p class="bkg-secondary mb-3 border-standered text-medium-bold">
                Call
              </p>
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
  data() {
    return {
      value1: [{ name: "Building 1", code: "1" }],
      options1: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],

      value2: [{ name: "Building 1", code: "1" }],
      options2: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],

      value3: [{ name: "Building 1", code: "1" }],
      options3: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],

      value4: [{ name: "Building 1", code: "1" }],
      options4: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],

      value5: [{ name: "Building 1", code: "1" }],
      options5: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],

      value6: [{ name: "Building 1", code: "1" }],
      options6: [
        { name: "Building 2", code: "1" },
        { name: "Building 2", code: "2" },
        { name: "Building 3", code: "3" },
        { name: "Building 4", code: "4" },
      ],
    };
  },
};
</script>
