<template>
  <div class="expense">
    <form>
      <input type="text" />
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ExpenseForm",
  mounted() {
    this.getCategory();
  },
  data() {
    return {
      categories: [],
      date: new Date(),
    };
  },
  methods: {
    getCategory() {
      let month = this.date.toLocaleDateString("default", { month: "short" });
      let year = this.date.getFullYear();

      axios
        .get(`http://127.0.0.1:8000/get-category/${month}/${year}`)
        .then((res) => {
          console.log(res.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped></style>
