<template>
  <div class="login">
    <form @submit.prevent="handleSubmit">
      <input type="text" v-model="formData.username" />
      <input type="password" v-model="formData.password" />
      <button type="submit">Submit</button>
    </form>
    <div v-if="response.code">
      <h4>Status: {{ response.status }}</h4>
      <h5>Code: {{ response.code }}</h5>
      <p>Message: {{ response.message }}</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "LoginSection",
  emits: ["set-username"],
  data() {
    return {
      formData: {
        username: "",
        password: "",
      },
      response: {
        status: "",
        message: "",
        code: null,
      },
      username: "",
    };
  },
  methods: {
    handleSubmit() {
      axios
        .post("http://127.0.0.1:8000/login", {
          username: this.formData.username,
          password: this.formData.password,
        })
        .then((res) => {
          this.response = res.data;
          if (res.data.code == 200) {
            this.$emit("set-username", this.formData.username);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped></style>
