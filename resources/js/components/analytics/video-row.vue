<template>
  <td v-if="data">
      <span v-if="data.entry_log && data.entry_log.video_file">
        <a @click="loadVideo(data)" href="javascript:void(0)">Play Video</a>
      </span>
    <span v-else>-</span>
  </td>
</template>
<script>
export default {
  props: ["data"],
  created() {
  },
  data() {
    return {};
  },
  methods: {
    loadVideo(data) {
      let video = data.building_id+"/"+data.entry_log.video_file;
      this.$parent.$refs.EntryVideoModal.loadVideo(video);
    }
  },
};
</script>
