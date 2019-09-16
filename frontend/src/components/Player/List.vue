/* global _ */
<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-icon"><i class="fas fa-users"></i></span>
      <span class="header-title">Player list</span>
      <table class="table-player-list">
        <tr class="row-header">
          <th class="txt-left">name</th>
          <th class="txt-center mw-100" @click="this.eloSort">elo</th>
          <th class="txt-center mw-100">m.played</th>
          <th class="txt-center mw-100">m.won</th>
          <th class="txt-center mw-100">m.%</th>
        </tr>
        <tr v-for="player in this.orderedPlayers" v-bind:key="player.id" class="row-data">
          <td class="txt-left player-link"><router-link :to="'/player/' + player.id + '/info'">{{ player.name }}</router-link></td>
          <td class="txt-center">{{ player.elo }}</td>
          <td class="txt-center">{{ player.gamesPlayed }}</td>
          <td class="txt-center">{{ player.wins }}</td>
          <td class="txt-center">{{ player.winPercentage }}</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import _ from 'lodash'

export default {
  name: 'App',
  data () {
    return {
      players: null,
      orderedPlayers: []
    }
  },
  mounted () {
    axios.get('/api/players').then((res) => {
      this.players = res.data
    })
  },
  methods: {
    eloSort: function () {
      this.orderedPlayers = _.orderBy(this.players, 'elo')
    }
  },
  computed: {
    nameSort: function () {
      this.orderedPlayers = _.orderBy(this.players, 'name')
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainContainer {
  padding: 20px 25px;
}

.header-icon {
  color: white;
  font-size: 20px;
  padding-right: 15px;
}

.table-player-list {
  border-collapse: collapse;
  margin-top: 20px;
  width: 100%;
}

.row-header {
  font-size: 20px;;
}

.player-link {
  a {
    color: white;
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
}
</style>
