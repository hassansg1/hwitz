<template>
    <div>
      <div
        class="modal fade"
        :id="'modal-' + id"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <p class="mb-8 text-medium-bold text-uppercase color-secondary mt-16">{{title}}</p>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start " :class="'modal-html-'+id" v-html="body">
              
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
    props: ["id","body","title"],
    data() {
      return {
        
      };
    },
    methods: {
        
    },
  };
  </script>

  