<template>
  <div class="setup">
    <h1>Budget for {{ month }} {{ year }}</h1>
    <form
      v-if="!budgetExist"
      ref="budgetInputForm"
      @submit.prevent="handleSubmit"
    >
      <input type="number" v-model.number="formData.amount" />
      <button type="button" @click="addCategory">Add Category</button>
      <div v-for="(item, index) in formData.categories" :key="index">
        <input type="text" v-model="item.category" />
        <input type="number" v-model="item.budget" />
        <button
          type="button"
          @click="removeCategory(index)"
          v-show="index != 0"
        >
          Remove
        </button>
      </div>
      <button type="submit">Submit</button>
    </form>
    <h4 v-if="success">{{ success }}</h4>
    <h4 v-if="error">No Expenses Added Yet / Invalid Expense Insert</h4>
    <form v-if="budgetExist" @submit.prevent="handleUpdateBudget">
      <h3>This Month Budget: {{ monthlyBudget }}</h3>
      <label for="update-budget">Update Amount</label>
      <input type="text" id="update-budget" v-model="updatedAmount" />
      <button type="submit">Update Budget</button>
    </form>
    <form v-if="budgetExist" @submit.prevent="handleNewCategory">
      <input type="text" v-model="newCategory.category" />
      <input type="number" v-model="newCategory.budget" />
      <button type="submit">New Category</button>
    </form>
    <table>
      <thead>
        <th>Category ID</th>
        <th>Category</th>
        <th>Budget</th>
        <th>Action</th>
      </thead>
      <tr v-for="(expense, index) in expenses" :key="index">
        <td>{{ expense.id }}</td>
        <td>{{ expense.category }}</td>
        <td>{{ expense.amount }}</td>
        <td>
          <input type="number" v-model="updateExpense[index]" />
          <button @click="handleExpense(index)">Update</button>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>Total</td>
        <td>{{ totalExpense }}</td>
      </tr>
    </table>
    <h3 v-if="expenseWarning">{{ expenseWarning }}</h3>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SetupBudgetView",
  data() {
    return {
      formData: {
        amount: null,
        categories: [
          {
            category: "",
            budget: null,
          },
        ],
      },
      newCategory: {
        category: "",
        budget: null,
      },
      response: {
        status: "",
        message: "",
        code: null,
      },
      month: new Date().toLocaleDateString("default", { month: "short" }),
      year: new Date().getFullYear(),
      budgetId: null,
      expenses: [],
      error: "",
      success: "",
      updatedAmount: null,
      budgetExist: null,
      monthlyBudget: null,
      totalExpense: 0,
      updateExpense: [],
      expenseWarning: "",
    };
  },
  mounted() {
    this.getCategory();
  },
  watch: {
    totalExpense(newVal) {
      if (this.monthlyBudget - newVal < 100) {
        this.expenseWarning = "Budget getting over";
      } else {
        this.expenseWarning = "";
      }
    },
  },
  methods: {
    getCategory() {
      axios
        .get(`http://127.0.0.1:8000/get-category/${this.month}/${this.year}`)
        .then((res) => {
          if (res.data.status == "success") {
            this.expenses = res.data.expenses;
            this.totalExpense = 0;
            let expenseHolder = [];
            this.expenses.forEach((expense) => {
              this.totalExpense += expense.amount;
              expenseHolder.push(expense.amount);
            });
            this.updateExpense = expenseHolder;
            this.success = res.data.message;
            this.budgetExist = true;
            this.budgetId = res.data.budget.id;
            this.monthlyBudget = res.data.budget.amount;
            console.log(res.data);
          } else {
            this.error = res.data.message;
            console.log(this.error);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addCategory() {
      this.formData.categories.push({
        category: "",
        budget: null,
      });
    },
    removeCategory(index) {
      this.formData.categories.splice(index, 1);
    },
    handleSubmit() {
      axios
        .post("http://127.0.0.1:8000/setup-budget", {
          month: this.month,
          year: this.year,
          amount: this.formData.amount,
          categories: this.formData.categories,
        })
        .then((res) => {
          if (res.data.status == "success") {
            this.success = res.data.message;
          } else {
            this.error = res.data.message;
          }
          console.log(res.data);
          this.$refs.budgetInputForm.reset();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleUpdateBudget() {
      axios
        .post(
          `http://127.0.0.1:8000/update-budget/${this.month}/${this.year}`,
          {
            updatedAmount: this.updatedAmount,
          }
        )
        .then((res) => {
          console.log(res.data);
          if (res.data.status == "success") {
            this.success = res.data.message;
            this.error = "";
            this.updatedAmount = null;
            this.getCategory();
          } else {
            this.success = "";
            this.error = res.data.message;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleNewCategory() {
      if (this.totalExpense + this.newCategory.budget > this.monthlyBudget) {
        this.expenseWarning = "Lower budget for newly adding expense";
      } else {
        axios
          .post("http://127.0.0.1:8000/add-category/", {
            budget_id: this.budgetId,
            category: this.newCategory.category,
            amount: this.newCategory.budget,
          })
          .then((res) => {
            console.log(res.data);
            if (res.data.status == "success") {
              this.success = res.data.message;
              this.error = "";
              this.updatedAmount = null;
              this.getCategory();
            } else {
              this.success = "";
              this.error = res.data.message;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleExpense(index) {
      this.totalExpense +=
        this.updateExpense[index] - this.expenses[index].amount;

      if (this.totalExpense <= this.monthlyBudget) {
        axios
          .post("http://127.0.0.1:8000/update-expense", {
            id: this.expenses[index].id,
            updatedAmount: this.updateExpense[index],
          })
          .then((res) => {
            console.log(res.data);
            if (res.data.status == "success") {
              this.success = res.data.message;
              this.error = "";
              this.updatedAmount = null;
              this.getCategory();
            } else {
              this.success = "";
              this.error = res.data.message;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        this.totalExpense -=
          this.updateExpense[index] - this.expenses[index].amount;
        this.expenseWarning = "Expense Got Overboard...";
      }
    },
  },
};
</script>

<style scoped></style>
