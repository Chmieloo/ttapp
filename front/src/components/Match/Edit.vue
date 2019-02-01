<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addMatchForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">Edit results</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
        <table>
          <tr v-for="match in matches" v-bind:key="match.matchId" v-bind:value="match.matchId">
              <td class="padl20">{{ match.matchId }}</td>
              <td class="padl20">{{ match.dateOfMatch }}</td>
              <td class="padl20">{{ match.groupName }}</td>
              <td class="padl20">{{ match.homePlayerName }}</td>
              <td class="padl20">{{ match.awayPlayerName }}</td>
              <td>
                <div v-for="score in match.scores" v-bind:key="score.set" class="span-score">
                  <input type="text" name="home" :value=score.home />
                  <input type="text" name="away" :value=score.away />
                </div>
              </td>
          </tr>
        </table>
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
  name: 'MatchEdit',
  data () {
    return {
      matches: [],
      errors: [],
      selectedMatch: null,
      selectedHomePlayer: null
    }
  },
  mounted () {
    axios.all([
      axios.get('/api/matches'),
      axios.get('/api/tournaments'),
      axios.get('/api/players')
    ]).then(axios.spread((matches, tournaments, players) => {
      this.matches = matches.data
      this.tournaments = tournaments.data
      this.homePlayers = players.data
      this.awayPlayers = players.data
    })).catch(error => {
      console.log('Error when getting data for matches ' + error)
    })
  },
  methods: {
    postNow () {
      axios.post('/api/matches/add/official', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        tournament: this.selectedTournament,
        mode: this.selectedMode,
        homePlayer: this.selectedHomePlayer,
        awayPlayer: this.selectedAwayPlayer
      }).then((res) => {
        this.errors = []
        this.errors.push('Match added')
      }).catch(error => {
        this.errors = []
        this.errors.push(error.response.data.errorText)
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.padl20 {
  padding-left: 20px;
}

.span-score {
  min-width: 150px;
  float: left;
  input {
    max-width: 30px;
  }
}

div.span-label {
  width: 300px;
}
</style>
