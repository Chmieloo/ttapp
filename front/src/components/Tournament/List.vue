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
          <th class="txt-center mw-100">phase</th>
          <th class="txt-center mw-100">matches played</th>
          <th class="txt-center mw-100">matches scheduled</th>
          <th class="txt-center mw-100">options</th>
        </tr>
        <tr v-for="tournament in tournaments" v-bind:key="tournament.id" class="player-row">
          <td class="txt-left">{{ tournament.name }}</td>
          <td class="txt-center">{{ tournament.phase }}</td>
          <td class="txt-center">58</td>
          <td class="txt-center">20</td>
          <td class="txt-center">0</td>
          <td class="txt-center">0</td>
          <td class="txt-center">schedule | results | standings</td>
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
      tournaments: null
    }
  },
  mounted () {
    axios.get('/api/tournaments').then((res) => {
      this.tournaments = res.data
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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

.row-header {
  font-size: 20px;;
}

.player-row {
  height: 25px;
  line-height: 25px;
  border-bottom: 1px solid #2d2d2d;
}
</style>
