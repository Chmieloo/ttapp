<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addMatchForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">New match</span>
        <form class="mart10" method="post" @submit.prevent="postNow">
          <div class="mart10">
            <div class="span-label">tournament</div>
            <select v-model="selectedTournament" name="tournaments">
              <option disabled value="">Please select one</option>
              <option v-for="tournament in tournaments" v-bind:key="tournament.id" v-bind:value="tournament.id">
                {{ tournament.name }}
              </option>
            </select>
          </div>
          <div class="mart10">
            <div class="span-label">match mode</div>
            <select v-model="selectedMode" name="modes">
              <option disabled value="">Please select one</option>
              <option v-for="mode in modes" v-bind:key="mode.id" v-bind:value="mode.id">
                {{ mode.short_name }} : {{ mode.name }}
              </option>
            </select>
          </div>
          <div class="mart10">
            <div class="span-label">home player</div>
            <select v-model="selectedHomePlayer" name="homePlayers" placeholder="home player">
              <option disabled selected value="">Please select one</option>
              <option v-for="player in homePlayers" v-bind:key="player.id" v-bind:value="player.id">
                {{ player.name }}
              </option>
            </select>
          </div>
          <div class="mart10">
            <div class="span-label">away player</div>
            <select v-model="selectedAwayPlayer" name="awayPlayers" placeholder="away player">
              <option disabled selected value="">Please select one</option>
              <option v-for="player in awayPlayers" v-bind:key="player.id" v-bind:value="player.id">
                {{ player.name }}
              </option>
            </select>
          </div>
          <div class="mart10">
            <div class="span-label">date and time of match</div>
            <datetime
              type="datetime"
              v-model="datetime"
              format="yyyy-MM-dd HH:mm"
              value-zone="Europe/Berlin"></datetime>
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
import { Datetime } from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

export default {
  name: 'MatchAdd',
  components: {
    Datetime
  },
  data () {
    return {
      modes: [],
      tournaments: [],
      homePlayers: [],
      awayPlayers: [],
      errors: [],
      selectedTournament: null,
      selectedMode: null,
      selectedHomePlayer: null,
      selectedAwayPlayer: null
    }
  },
  mounted () {
    axios.all([
      axios.get('/api/matches/modes'),
      axios.get('/api/tournaments'),
      axios.get('/api/players')
    ]).then(axios.spread((modes, tournaments, players) => {
      this.modes = modes.data
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
        awayPlayer: this.selectedAwayPlayer,
        datetime: this.datetime
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
select {
    font-size: 16px;
    padding: 8px;
    font-weight: 400;
    color: #000;
    min-width: 200px;
    font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;
    height: 40px;
}

div.span-label {
  width: 300px;
}
</style>
