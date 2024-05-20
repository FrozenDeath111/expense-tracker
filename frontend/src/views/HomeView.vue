<template>
  <div class="home">
    <ExpenseForm></ExpenseForm>
    <DetailShow></DetailShow>
  </div>
</template>

<script>
import DetailShow from "../components/DetailShow.vue";
import ExpenseForm from "../components/ExpenseForm.vue";

export default {
  name: "HomeView",
  components: {
    ExpenseForm,
    DetailShow,
  },
};
</script>
