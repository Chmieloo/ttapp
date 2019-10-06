/* global _ */
<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-icon"><i class="fas fa-users"></i></span>
      <span class="header-title">Player list</span>
      <table class="table-player-list">
        <tr class="row-header">
          <th class="txt-left"><a @click="nameSort()">name</a></th>
          <th class="txt-right mw-100"><a @click="eloSort()">elo</a></th>
          <th class="txt-right" style="width: 100px;"><a @click="eloChangeSort()">change</a></th>
          <th class="txt-center mw-100"><a @click="gameSort()">m.played</a></th>
          <th class="txt-center mw-100">m.won / m.drawn / m.lost</th>                    
          <th class="txt-center mw-100"><a @click="winSort()">m.win%</a></th>
        </tr>
        <tr v-for="player in this.players" v-bind:key="player.id" class="row-data">
          <td class="txt-left player-link"><router-link :to="'/player/' + player.id + '/info'">{{ player.name }}</router-link></td>
          <td class="txt-right">{{ player.elo }}</td>
          <td class="txt-right">
            {{ player.elo - player.oldElo }}
            <span style="margin-left: 10px;">
              <span style="color: red;" v-if="player.elo - player.oldElo <= -100">
                <i class="fas fa-angle-double-down"></i>
              </span>
              <span style="color: red;" v-else-if="player.elo - player.oldElo < 0">
                <i class="fas fa-angle-down"></i>
              </span>
              <span style="color: green;" v-else-if="player.elo - player.oldElo > 100">
                <i class="fas fa-angle-double-up"></i>
              </span>
              <span style="color: green;" v-else-if="player.elo - player.oldElo > 0">
                <i class="fas fa-angle-up"></i>
              </span>
              <span v-else>
                <i style="color: #4a4a4a;" class="fas fa-caret-left"></i>
              </span>
            </span>
          </td>
          <td class="txt-center">{{ player.gamesPlayed }}</td>
          <td class="txt-center">{{ player.wins }} / {{ player.draws }} / {{ player.losses }}</td>
          <td class="txt-center">{{ player.winPercentage.toFixed(2) }}</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import _ from 'lodash'
import Vue from 'vue'
import VueCookie from 'vue-cookie'

Vue.use(VueCookie)

export default {
  name: 'App',
  data () {
    return {
      players: [],
      eloOrder: 'asc',
      eloChangeOrder: 'asc',
      nameOrder: 'desc',
      gamesPlayedOrder: 'asc',
      winOrder: 'asc',
      officeCookieId: 1
    }
  },
  mounted () {
    axios.get('/api/players').then((res) => {
      this.players = res.data
      this.nameSort()
      this.officeCookieId = parseInt(this.$cookie.get('officeId'))
      // console.log(this.officeCookieId)
    })
  },
  computed: {
    filteredPlayers: function () {
      return this.players.filter((player) => {
        return parseInt(player.officeId) === this.officeCookieId
      })
    }
  },
  methods: {
    eloChangeSort () {
      if (this.eloChangeOrder === 'desc') {
        this.eloChangeOrder = 'asc'
      } else {
        this.eloChangeOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'eloChange', this.eloChangeOrder)
    },
    eloSort () {
      if (this.eloOrder === 'desc') {
        this.eloOrder = 'asc'
      } else {
        this.eloOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'elo', this.eloOrder)
    },
    nameSort () {
      if (this.nameOrder === 'desc') {
        this.nameOrder = 'asc'
      } else {
        this.nameOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'name', this.nameOrder)
    },
    gameSort () {
      console.log(this.players)
      if (this.gamesPlayedOrder === 'desc') {
        this.gamesPlayedOrder = 'asc'
      } else {
        this.gamesPlayedOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'gamesPlayed', this.gamesPlayedOrder)
    },
    winSort () {
      if (this.winOrder === 'desc') {
        this.winOrder = 'asc'
      } else {
        this.winOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'winPercentage', this.winOrder)
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
