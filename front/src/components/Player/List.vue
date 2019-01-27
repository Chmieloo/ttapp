<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-icon"><i class="fas fa-list"></i></span>
      <span class="header-title">Player list</span>
      <table class="table-player-list">
        <tr class="row-header">
          <th class="txt-left">name</th>
          <th class="txt-center mw-100">elo</th>
          <th class="txt-center mw-100">m.played</th>
          <th class="txt-center mw-100">m.won</th>
          <th class="txt-center mw-100">m.%</th>
          <th class="txt-center mw-100">s.played</th>
          <th class="txt-center mw-100">s.won</th>
          <th class="txt-center mw-100">s.%</th>
          <th class="txt-center mw-100">form</th>
        </tr>
        <tr v-for="player in players" v-bind:key="player.id" class="player-row">
          <td class="txt-left">{{ player.name }}</td>
          <td class="txt-center">{{ player.tournament_elo }}</td>
          <td class="txt-center">0</td>
          <td class="txt-center">0</td>
          <td class="txt-center">20</td>
          <td class="txt-center">0</td>
          <td class="txt-center">0</td>
          <td class="txt-center">20</td>
          <td class="txt-center">W.W.L.D.W</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'App',
  data () {
    return {
      players: null
    }
  },
  mounted () {
    axios.get('/api/players').then((res) => {
      this.players = res.data
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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

.player-row {
  height: 25px;
  line-height: 25px;
  border-bottom: 1px solid #2d2d2d;
}
</style>
