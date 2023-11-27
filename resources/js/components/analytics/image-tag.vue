<template>
  <span>
      <span v-if="data && data.img_file">
        <img @click="loadFiveImages(data.img_file,data.building_id)" width="200"
             :src="this.amazonUrl+data.building_id+'/'+data.img_file"
             alt="">
        <br>
        <br>
      </span>
    <span v-else>-</span>
    </span>
</template>
<script>
export default {
  props: ["data"],
  created() {
    this.loadAmazonUrl();
  },
  data() {
    return {
      amazonUrl: "",
    };
  },
  methods: {
    loadAmazonUrl() {
      this.amazonUrl = $('#mus_entry_url').val();
    },
    loadFiveImages(image,building_id) {
      this.$parent.$refs.FiveImagesModal.loadFiveImages(image,building_id);
    }
  },
};
</script>
