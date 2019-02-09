<template>
  <div id="app">
    <div class="mainContainer">
      <div id="addMatchForm">
        <span class="header-icon"><i class="fas fa-plus-circle"></i></span>
        <span class="header-title">Edit results</span>
        <table>
          <tr v-for="match in matches" v-bind:key="match.matchId" v-bind:value="match.matchId">
              <td>{{ match.matchId }}</td>
              <td class="padl20">{{ match.dateOfMatch }}</td>
              <td class="padl20">{{ match.homePlayerName }}</td>
              <td>{{ match.awayPlayerName }}</td>
              <td>
                <form class="mart10" v-bind:key="'form_' + match.matchId" method="post" @submit.prevent="postResults">
                  <div>
                    <input type="hidden" name="matchId" :value=match.matchId />
                  </div>
                  <div v-for="score in match.scores" v-bind:key="score.set" class="span-score">
                      <input type="text" :name="'home_set_' + score.set" :value=score.home />
                      <input type="text" :name="'away_set_' + score.set" :value=score.away />
                  </div>
                  <div v-if="match.scores.length == 0">
                    <span v-for="i in range(1, match.maxSets)" v-bind:key="i" class="span-score">
                      <input type="text" :name="'home_set_' + i" value="" />
                      <input type="text" :name="'away_set_' + i" value="" />
                    </span>
                  </div>
                  <div v-else-if="match.scores.length < match.maxSets" class="span-score">
                    <span v-for="i in range(match.maxSets - (match.maxSets - match.scores.length) +  1, match.maxSets)" v-bind:key="i" class="span-score">
                      <input type="text" :name="'home_set_' + i" value="" />
                      <input type="text" :name="'away_set_' + i" value="" />
                    </span>
                  </div>
                  <div style="float: left;">
                    <input type="submit" value="save" class="submit-button" />
                  </div>
                </form>
              </td>
          </tr>
        </table>
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
  name: 'TournamentResultsEdit',
  data () {
    return {
      matches: [],
      errors: []
    }
  },
  mounted () {
    axios.all([
      axios.get('/api/tournaments/' + this.$route.params.id + '/matches/fullfeed')
    ]).then(axios.spread((matches) => {
      this.matches = matches.data
    })).catch(error => {
      console.log('Error when getting data for matches ' + error)
    })
  },
  methods: {
    range: function (min, max) {
      var array = []
      var j = 0
      for (var i = min; i <= max; i++) {
        array[j] = i
        j++
      }
      return array
    },
    postResults (event) {
      axios.post('/api/matches/save', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        matchId: event.target.elements.matchId.value,
        h1: event.target.elements.home_set_1.value,
        h2: event.target.elements.home_set_2.value,
        h3: event.target.elements.home_set_3.value,
        h4: event.target.elements.home_set_4.value,
        a1: event.target.elements.away_set_1.value,
        a2: event.target.elements.away_set_2.value,
        a3: event.target.elements.away_set_3.value,
        a4: event.target.elements.away_set_4.value
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
  min-width: 100px;
  float: left;
  input {
    max-width: 30px;
    padding: 2px;
    text-align: center;
  }
}

.submit-button {
  font-size: 12px;
  padding: 6px;
  width: 50px;
  background: #909090;
  color: white;
  font-weight: 600;
}

div.span-label {
  width: 300px;
}
</style>
