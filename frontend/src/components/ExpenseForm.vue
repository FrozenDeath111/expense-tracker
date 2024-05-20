<template>
  <div class="expense">
    <h3>Date: {{ date }}</h3>
    <form @submit.prevent="handleSubmit">
      <input type="number" v-model="formData.amount" />
      <select v-model="formData.category">
        <option value="" selected disabled>Categories</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.category"
        >
          {{ category.category }}
        </option>
      </select>
      <input type="text" v-model="formData.description" />
      <select v-model="formData.method">
        <option value="" selected disabled>Payment</option>
        <option v-for="method in paymentMethods" :key="method" :value="method">
          {{ method }}
        </option>
      </select>
      <input type="text" v-model="formData.location" />
      <button type="submit">Submit</button>
    </form>
    <div v-if="response.code">
      <h3>Status: {{ response.status }}</h3>
      <h4>Code: {{ response.code }}</h4>
      <p>Message: {{ response.message }}</p>
    </div>
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
      budget: null,
      paymentMethods: ["GuessCash", "NoMoney", "MoneyGone", "GreenNull"],
      date: new Date(),
      formData: {
        amount: null,
        category: "",
        description: "",
        method: "",
        location: "",
      },
      response: {
        status: "",
        message: "",
        code: null,
      },
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
          if (res.data.status == "success") {
            this.categories = res.data.expenses;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleSubmit() {
      axios
        .post("http://127.0.0.1:8000/add-expense-data", {
          amount: this.formData.amount,
          category: this.formData.category,
          description: this.formData.description,
          method: this.formData.method,
          location: this.formData.location,
          date: this.date.toJSON().slice(0, 10),
        })
        .then((res) => {
          this.response = res.data;
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
