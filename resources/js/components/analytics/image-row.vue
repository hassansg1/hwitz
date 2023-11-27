<template>
  <td v-if="data">
      <span v-if="data.entry_log && data.entry_log.img_file">
        <img @click="loadFiveImages(data.entry_log.img_file,data.building_id)" style="width: 100px;cursor: pointer"
             :src="this.amazonUrl+data.building_id+'/'+data.entry_log.img_file"
             alt="">
        <br>
        <br>
        <a @click="loadFiveImages(data.entry_log.img_file,data.building_id)" href="javascript:void(0)">want more info?</a>
      </span>
    <span v-else>-</span>
  </td>
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
