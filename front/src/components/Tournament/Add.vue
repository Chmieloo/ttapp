<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addTournamentForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">New tournament</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
          <div>
            <input
              id="tournamentName"
              type="text"
              name="name"
              value="name"
              v-model="name"
              placeholder="Name"
            />
          </div>
          <div class="mart10">
            <input
              id="tournamentDate"
              type="date"
              name="date"
              value=""
              :format="formatDate"
              v-model="date"
              placeholder="Start date"
            />
          </div>
          <div class="mart10">
            <button type="submit" name="button">Submit</button>
          </div>
        </form>
      </div>
      <div>
        <span v-if="errors.length">
          <b>Information:</b>
          <ul>
            <li v-for="error in errors" v-bind:key="error.id">{{ error }}</li>
          </ul>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
  name: "formPost",
  data() {
    return {
      name: "",
      date: moment(new Date()).format("YYYY-MM-DD"),
      show: false,
      errors: [],
    };
  },
  methods: {
    formatDate(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    postNow() {
      axios
        .post("/api/tournaments/add", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          name: this.name,
          date: this.date,
        })
        .then(() => {
          this.errors = [];
          this.errors.push("Tournament added");
        })
        .catch((error) => {
          this.errors.push(error.response.data.errorText);
        });
    },
  },
};
</script>

<style lang="less" scoped>
#addTournamentForm {
  input {
    font-family: "Poppins", "Avenir", Helvetica, Arial, sans-serif;
    font-size: 16px;
    padding: 8px;
    font-weight: 400;
    color: #000;
    min-width: 200px;
  }
}
</style>
