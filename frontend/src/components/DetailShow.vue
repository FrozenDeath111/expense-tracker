<template>
  <div class="details">
    <div v-if="dataLoadError">
      <h1>Some Thing Wrong While Loading Data</h1>
    </div>
    <div v-else></div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "DetailShow",
  mounted() {
    this.getExpenseData();
  },
  data() {
    return {
      dataLoadError: true,
    };
  },
  methods: {
    getExpenseData() {
      axios
        .get("http://127.0.0.1:8000/get-expense-data")
        .then((res) => {
          console.log(res.data);
          this.dataLoadError = false;
        })
        .catch((error) => {
          this.dataLoadError = true;
          console.log(error);
        });
    },
  },
};
</script>

<style scoped></style>
