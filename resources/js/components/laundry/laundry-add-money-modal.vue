<template>
  <div class="laundry-section">
    <div
      class="modal fade"
      id="lAddMoneyModal"
      tabindex="-1"
      aria-labelledby="lAddMoneyModalLabel"
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
          <div class="modal-body">
            <div class="mb-40">
              <h1 class="h1 mb-12">Add Money</h1>
              <p class="w-50 mx-auto text-normal color-secondary">
                Add money to the resident cart
              </p>
            </div>

            <div class="row mb-22">
              <div class="col-lg-4 col-md-6 mx-auto multiselect-border-shadow">
                <div class="dropdown-wrapper text-start">
                  <label class="dropdown-label text-capitalize">Cart</label>
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
            </div>

            <div class="row mb-22">
              <div class="col-lg-4 col-md-6 mx-auto multiselect-border-shadow">
                <div class="dropdown-wrapper text-start">
                  <label class="dropdown-label text-capitalize">Building</label>
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
            </div>

            <div class="row mb-22">
              <div class="col-lg-4 col-md-6 mx-auto multiselect-border-shadow">
                <div class="dropdown-wrapper text-start">
                  <label class="dropdown-label text-capitalize"
                    >Residents</label
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
            </div>

            <div class="row mb-22">
              <div class="col-lg-4 col-md-6 mx-auto multiselect-border-shadow">
                <div class="dropdown-wrapper text-start">
                  <label class="dropdown-label text-capitalize">Amount</label>
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
            </div>

            <div class="row mb-70">
              <div class="col-lg-4 col-md-6 mx-auto multiselect-border-shadow">
                <div class="d-flex">
                  <input
                    class="form-check-input me-12"
                    type="checkbox"
                    value=""
                    id="flexCheckDefault"
                  />

                  <p
                    class="text-normal color-secondary align-self-center mt-9 mb-0"
                  >
                    Non payment credit/dabit transaction
                  </p>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center flex-row flex-wrap mb-70">
              <button class="btn btn-dark me-12 mb-12">Prepaymet/Credit</button>
              <button class="btn btn-dark me-12 mb-12">
                Good faith credit
              </button>
              <button class="btn bkg-danger text-white me-12 mb-12">
                Reduce Prepayment
              </button>
              <button class="btn bkg-danger text-white mb-12">
                Vacate Refund
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedItem: 1,
      value1: [{ name: "payment 1", code: "1" }],
      options1: [
        { name: "payment 1", code: "1" },
        { name: "payment 2", code: "2" },
        { name: "payment 3", code: "3" },
        { name: "payment 4", code: "4" },
      ],
    };
  },
  methods: {
    selectIt(index) {
      this.selectedItem = index;
    },
  },
};
</script>
