<template>
  <nav>
    <router-link to="/">Home</router-link> |
    <router-link to="/setup">Budget</router-link>
    <router-view />
  </nav>
</template>

<script>
export default {
  name: "NavComponent",
};
</script>

<style scoped></style>
