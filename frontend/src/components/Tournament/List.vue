<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-icon"><i class="fas fa-list"></i></span>
      <span class="header-title">List of tournaments</span>
      <table class="table-player-list">
        <tr class="row-header">
          <th class="txt-left">name</th>
          <th class="txt-center mw-100">phase</th>
          <th class="txt-center mw-100">participants</th>
          <th class="txt-center mw-100">matches played</th>
          <th class="txt-center mw-100">matches scheduled</th>
          <th class="txt-center mw-100">options</th>
        </tr>
        <tr v-for="tournament in this.filteredTournaments" v-bind:key="tournament.id" class="row-data">
          <td class="txt-left">{{ tournament.name }}</td>
          <td class="txt-center">{{ tournament.phase }}</td>
          <td class="txt-center">{{ tournament.participants }}</td>
          <td class="txt-center">{{ tournament.finished }}</td>
          <td class="txt-center">{{ tournament.scheduled }}</td>
          <td class="txt-center cell-options">
            <span v-if="tournament.isPlayoffs == 0">
              <router-link :to="'/tournament/' + tournament.id + '/standings'">standings</router-link>
              <span v-if="tournament.isFinished == 0">
                | <router-link :to="'/tournament/' + tournament.id + '/match/list'">schedule</router-link>
              </span>
              <span v-if="tournament.isFinished == 1">
                | <router-link :to="'/tournament/' + tournament.id + '/match/list'">results</router-link>
              </span>
            </span>
            <span v-else>
              <router-link :to="'/playoffs/' + tournament.id + '/ladders'">ladder</router-link>
            </span>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueLocalStorage from 'vue-localstorage'
Vue.use(VueLocalStorage, {
  name: 'localStorage',
  bind: true
})

export default {
  name: 'App',
  data () {
    return {
      tournaments: [],
      officeId: 1
    }
  },
  mounted () {
    axios.get('/api/tournaments').then((res) => {
      this.tournaments = res.data
      this.officeId = parseInt(this.$localStorage.get('ttappOfficeId', 1))
    })
  },
  computed: {
    filteredTournaments: function () {
      return this.tournaments.filter((tournament) => {
        return parseInt(tournament.officeId) === this.officeId
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
}

.table-player-list {
  border-collapse: collapse;
  margin-top: 20px;
  width: 100%;
}

.cell-options {
  a {
    color: white;
  }
}
</style>
