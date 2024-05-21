<template>
  <div class="details">
    <div v-if="dataLoadError">
      <h1>Some Thing Wrong While Loading Data</h1>
    </div>
    <div v-else>
      <input
        v-if="toggleHistoryExpense"
        type="text"
        v-model="searchCategory"
        placeholder="Search Category"
      />
      <input
        v-if="!toggleHistoryExpense"
        type="text"
        v-model="searchHistory"
        placeholder="Search History"
      />
      <table>
        <thead>
          <tr>
            <th>
              <button v-if="budgetIndex > 0" @click="decreaseIndex">
                Prev
              </button>
            </th>
            <th>Month: {{ budgetData[budgetIndex].budget.month }}</th>
            <th>Year: {{ budgetData[budgetIndex].budget.year }}</th>
            <th>
              <button
                v-if="budgetIndex < budgetData.length - 1"
                @click="increaseIndex"
              >
                Next
              </button>
            </th>
            <th>
              <button
                v-if="toggleHistoryExpense"
                @click="toggleHistoryExpense = !toggleHistoryExpense"
              >
                To History
              </button>
              <button
                v-if="!toggleHistoryExpense"
                @click="toggleHistoryExpense = !toggleHistoryExpense"
              >
                To Expenses
              </button>
            </th>
          </tr>
          <tr v-if="toggleHistoryExpense">
            <th>Category</th>
            <th>Expense</th>
          </tr>
          <tr v-else>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Pay Method</th>
            <th>Location</th>
          </tr>
        </thead>
        <tbody v-if="toggleHistoryExpense">
          <tr v-for="expense in filteredExpenses" :key="expense.id">
            <td>{{ expense.category }}</td>
            <td>{{ expense.amount }}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr v-for="history in filteredHistories" :key="history.id">
            <td>{{ history.category }}</td>
            <td>{{ history.description }}</td>
            <td>{{ history.amount }}</td>
            <td>{{ history.date }}</td>
            <td>{{ history.method }}</td>
            <td>{{ history.location }}</td>
          </tr>
        </tbody>
      </table>
    </div>
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
      budgetData: [],
      histories: [],
      filteredExpenses: [],
      filteredHistories: [],
      month_to_number: {
        Jan: "01",
        Feb: "02",
        Mar: "03",
        Apr: "04",
        May: "05",
        Jun: "06",
        Jul: "07",
        Aug: "08",
        Sep: "09",
        Oct: "10",
        Nov: "11",
        Dec: "12",
      },
      budgetIndex: 0,
      dataLoadError: true,
      toggleHistoryExpense: true,
      searchCategory: "",
      searchHistory: "",
    };
  },
  methods: {
    getExpenseData() {
      axios
        .get("http://127.0.0.1:8000/get-expense-data")
        .then((res) => {
          this.budgetData = res.data.data;
          this.histories = res.data.histories;
          this.filteredExpenses = this.budgetData[this.budgetIndex].expenses;
          this.filterHistory(
            this.budgetData[this.budgetIndex].budget.month,
            this.budgetData[this.budgetIndex].budget.year
          );
          console.log(res.data);
          this.dataLoadError = false;
        })
        .catch((error) => {
          this.dataLoadError = true;
          console.log(error);
        });
    },
    increaseIndex() {
      if (this.budgetIndex < this.budgetData.length - 1) {
        this.budgetIndex++;
      }
    },
    decreaseIndex() {
      if (this.budgetIndex > 0) {
        this.budgetIndex--;
      }
    },
    filterExpense(searchData) {
      if (searchData == "") {
        this.filteredExpenses = this.budgetData[this.budgetIndex].expenses;
      } else {
        this.filteredExpenses = this.budgetData[
          this.budgetIndex
        ].expenses.filter((item) => item.category.includes(searchData));
      }
    },
    filterHistory(month, year) {
      console.log(this.month_to_number[month], year);
      let historyFilterArray = [];
      this.histories.forEach((history) => {
        let month_number = history.date.substring(5, 7);
        let year_number = history.date.substring(0, 4);
        if (
          this.month_to_number[month] == month_number &&
          year == year_number
        ) {
          historyFilterArray.push(history);
        }
        this.filteredHistories = historyFilterArray;
      });
    },
    filterSearchHistory(searchData) {
      console.log(searchData);
    },
  },
  watch: {
    searchCategory(newVal) {
      this.filterExpense(newVal);
    },

    searchHistory(newVal) {
      this.filterSearchHistory(newVal);
    },

    budgetIndex(newVal) {
      this.filterExpense(this.searchCategory);
      this.filterHistory(
        this.budgetData[newVal].budget.month,
        this.budgetData[newVal].budget.year
      );
    },
  },
};
</script>

<style scoped></style>
