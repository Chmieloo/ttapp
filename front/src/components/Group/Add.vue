<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addPlayerForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">New group</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
          <div>
            <input
              type="text"
              name=""
              value="name"
              v-model="name"
              placeholder="Name"
            />
          </div>
          <div class="mart10">
            <select v-model="selected" name="tournaments">
              <option disabled value="">Please select one</option>
              <option
                v-for="tournament in tournaments"
                v-bind:key="tournament.id"
                v-bind:value="tournament.id"
              >
                {{ tournament.name }}
              </option>
            </select>
          </div>
          <div class="mart10">
            <button type="submit" name="button">Submit</button>
          </div>
        </form>
      </div>
      <div>
        <span v-if="errors.length">
          <b>Please correct the following error(s):</b>
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

export default {
  name: "formPost",
  data() {
    return {
      tournaments: this.getTournaments(),
      name: "",
      show: false,
      errors: [],
    };
  },
  methods: {
    getTournaments() {
      axios.get("/api/tournaments").then((res) => {
        this.tournaments = res.data;
      });
    },
    postNow() {
      axios
        .post("/api/groups/add", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          name: this.name,
          tournament: this.selected,
        })
        .then(() => {
          this.errors = [];
          this.errors.push("Group added");
        })
        .catch((error) => {
          this.errors.push(error.response.data.errorText);
        });
    },
  },
};
</script>

<style lang="less" scoped>
#addPlayerForm {
  input {
    font-size: 16px;
    padding: 8px;
    font-weight: 400;
    color: #000;
    min-width: 200px;
  }
}

select {
  font-size: 16px;
  padding: 8px;
  font-weight: 400;
  color: #000;
  min-width: 200px;
  font-family: "Poppins", "Avenir", Helvetica, Arial, sans-serif;
  height: 40px;
}
</style>
