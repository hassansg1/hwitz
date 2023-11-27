<template>
  <div class="users-section" v-if="user && unit">
    <p v-if="user.mobile_verification === 'No'" class="text-normal color-primary mb-0">
      <span class="red">Unverified</span>
    </p>
    <p v-else class="text-normal color-primary mb-0">
      <span v-if="stripCharacter(user.mobile,/-/g) === unit.call1" class="color-primary">Position 1</span>
      <span v-if="stripCharacter(user.mobile,/-/g) === unit.call2" class="color-primary">Position 2</span>
      <span v-if="stripCharacter(user.mobile,/-/g) === unit.call3" class="color-primary">Position 3</span>
    </p>
  </div>
</template>

<script>

export default {
  props: ["user", "unit"],
  data() {
    return {
    };
  },
};
</script>
