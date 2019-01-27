<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addTournamentForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">New tournament</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
          <div>
            <input id="tournamentName" type="text" name="name" value="name" :state="nameState" v-model="name" placeholder="Name">
          </div>
          <div class="mart10">
            <input id="tournamentDate" type="date" name="date" value="" v-model="startDate" placeholder="Start date">
          </div>
          <div class="mart10">
            <button type="submit" name="button">Submit</button>
          </div>
        </form>
      </div>
      <div>
        <p v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li v-for="error in errors" v-bind:key="error.id">{{ error }}</li>
          </ul>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'formPost',
  data () {
    return {
      name: '',
      startDate: new Date(),
      show: false,
      errors: []
    }
  },
  methods: {
    postNow () {
      axios.post('/api/players/add', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        name: this.name,
        nickname: this.nickname
      }).then((res) => {
        this.errors = []
        this.errors.push('Player added')
      }).catch(error => {
        this.errors.push(error.response.data.errorText)
      })
    }
  }
}
</script>

<style lang="less" scoped>
#addTournamentForm {
  input {
    font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;
    font-size: 16px;
    padding: 8px;
    font-weight: 400;
    color: #000;
    min-width: 200px;
  }
}
</style>
