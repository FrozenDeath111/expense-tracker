<template>
  <div class="home">
    <ExpenseForm></ExpenseForm>
  </div>
</template>

<script>
import ExpenseForm from "../components/ExpenseForm.vue";

export default {
  name: "HomeView",
  components: {
    ExpenseForm,
  },
};
</script>
