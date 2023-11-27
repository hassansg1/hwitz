<template>
  <div class="residents-section">
    <div
      class="modal fade"
      id="residentsModal"
      tabindex="-1"
      aria-labelledby="residentsModal"
      aria-hidden="true"
    >
      <div class="modal-dialog">
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
            <div class="mb-32">
              <p class="text-medium-bold mb-4">Deactivate FOB</p>
              <p class="text-sm color-secondary">
              </p>
            </div>

            <div class="row gy-3 mb-52">
              <div class="col-lg-12" v-for="token in tokens">
                <div class="form-floating mb-3">
                  <input
                    type="test"
                    class="form-control"
                    id="floatingInput"
                    placeholder="Some text"
                    disabled
                  />
                  <label for="floatingInput">{{token.card_id}}</label>
                </div>
              </div>
            </div>
            <div class="col-lg-12 text-center" v-if="!tokens || tokens.length == 0">
              No data found.
            </div>

            <div class="d-flex justify-content-lg-end" v-if="tokens && tokens.length > 0">
              <button class="btn btn-primary" @click="permanentlyDiscardFob">Permanently Discard</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    components: {
        
    },
    data() {
        return {
          tokens : [] 
        };
    },
    computed: {
        
    },
    methods: {
      permanentlyDiscardFob(){
        this.showLoader();
        let self = this;
        this.tokens.forEach(function(fob, index) {
        
          self.$http
            .get("/remove_permanently/"+fob.id)
            .then((response) => {
              self.$parent.showDetail(self.$parent.activeIndex,self.$parent.activeUnitUsers);
              Toast.fire({
                  icon: 'success',
                  title: 'Fob discarded succesfully',
              });
              self.removeLoader();
              $("#residentsModal").modal("hide")
            })
            .catch((error) => {
              console.error(error);
            });
        });
      }

    },
};
</script>
