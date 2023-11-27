<template>
  <div class="users-section" v-if="user">
    <p v-if="user.mobile_verification === 'No' || user.email_verified != 1" class="text-normal color-primary mb-0">
      <span class="color-primary select-cursor"
            @click="resendVerificationLink(user.id)">Resend self-verification links</span>
    </p>
  </div>
</template>

<script>

export default {
  props: ["user", "unit"],
  data() {
    return {};
  },
  methods: {
    resendVerificationLink(user_id) {
      this.$http
          .get("/resendVerificationLink/" + user_id)
          .then((response) => {
            let status = response.data.status;
            let message = response.data.message;
            if (status)
              Toast.fire({
                icon: status,
                title: message,
              });
            this.removeLoader();
          })
          .catch((error) => {
            this.removeLoader();
            console.error(error);
          });
    }
  }
};
</script>
