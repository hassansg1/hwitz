<template>
  <div class="users-section" v-if="user">
    <p class="text-normal color-primary mb-0">{{ user.mobile }}</p>
    <p v-if="user.mobile_verification === 'No'" class="text-normal color-primary mb-0">
      <span class="red">Unverified</span> |
      <span v-if="user.mobile_verification === 'No'" class="color-primary select-cursor"
            @click="verifyMobileOtp(user.id)">Verify via OTP</span>
    </p>
  </div>
</template>

<script>

export default {
  props: ["user"],
  data() {
    return {
    };
  },
  methods: {
    verifyMobileOtp(user_id) {
      this.showLoader();
      this.$http
          .get("/verifyMobileByOtp/" + user_id)
          .then((response) => {
            console.log(response);
            let status = response.data.status;
            let message = response.data.message;
            if (status == 'success') message = 'Token (' + response.data.token + ') sent successfully. Expires at ' + this.formatDateTime(response.data.expire_at)

            Toast.fire({
              icon: status,
              title: message,
              timer: 13000
            });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    },
  },
};
</script>
