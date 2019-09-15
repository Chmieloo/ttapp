<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addPlayerForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">New player</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
          <div>
            <input type="text" name="" value="name" v-model="name" placeholder="Name">
          </div>
          <div class="mart10">
            <input type="text" name="" value="nickname" v-model="nickname" placeholder="Nickname">
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
      nickname: '',
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
#addPlayerForm {
  input {
    font-size: 16px;
    padding: 8px;
    font-weight: 400;
    color: #000;
    min-width: 200px;
  }
}
</style>
