<template>
  <div>
    <NavComponent></NavComponent>
    <button
      v-if="username && showLogin == false"
      @click="showLogin = !showLogin"
    >
      Login
    </button>
    <button
      v-else-if="username && showLogin == true"
      @click="showLogin = !showLogin"
    >
      Close
    </button>
    <LoginSection v-if="showLogin" @set-username="setUsername" />
  </div>
</template>

<script>
import NavComponent from "./components/NavComponent.vue";
import LoginSection from "./components/LoginSection.vue";

export default {
  name: "App",
  components: {
    NavComponent,
    LoginSection,
  },
  data() {
    return {
      username: "",
      showLogin: false,
    };
  },
  methods: {
    setUsername(name) {
      this.username = name;
      this.showLogin = false;
    },
  },
};
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>
