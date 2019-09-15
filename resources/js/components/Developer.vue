<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div v-if="$gate.canAccess()">
          <passport-clients></passport-clients>
          <passport-authorized-clients></passport-authorized-clients>
          <passport-personal-access-tokens></passport-personal-access-tokens>
        </div>
        <not-found v-if="!$gate.canAccess()" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
