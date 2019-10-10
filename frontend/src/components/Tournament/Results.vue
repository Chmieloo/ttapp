<template>
  <div class="mainMatchContainer">
    <table>
      <tr v-for="match in filteredMatches" v-bind:key="match.id" class="row-data row-schedule">
        <td>{{ match.datePlayed }}</td>
        <td class="txt-right" v-bind:class="match.winnerId == match.homePlayerId ? 'winner-color' : ''">
          <router-link :to="'/player/' + match.homePlayerId + '/info'">{{ match.homePlayerDisplayName }}</router-link>
        </td>
        <td style="width: 30px; text-align: center;">-</td>
        <td v-bind:class="match.winnerId == match.awayPlayerId ? 'winner-color' : ''">
          <router-link :to="'/player/' + match.awayPlayerId + '/info'">{{ match.awayPlayerDisplayName }}</router-link>
        </td>
        <td>
          <span class="totalScore">
          {{ match.homeScoreTotal }} - {{ match.awayScoreTotal }}
          </span>
          <span v-if="match.isWalkover == '1'">
            walkover
          </span>
          <span v-else class="setScores">
            (
              <span v-for="score in match.scores" v-bind:key="score.set" class="score">{{ score.home }} - {{ score.away }}</span>
              )
          </span>
        </td>
        <td v-if="parseInt(match.pts) > 0" style="color: #fff;">
          <router-link :to="'/match/' + match.matchId + '/summary'"><i class="fas fa-eye"></i></router-link>
        </td>
        <td v-else></td>
      </tr>
    </table>
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
  name: 'FullMatchResults',
  data () {
    return {
      matches: [],
      officeId: 1
    }
  },
  mounted () {
    axios.get('/api/tournaments/' + this.$route.params.id + '/results/0').then((res) => {
      this.matches = res.data
      this.officeId = parseInt(this.$localStorage.get('ttappOfficeId', 1))
    })
  },
  computed: {
    filteredMatches: function () {
      return this.matches.filter((match) => {
        return match.officeId === this.officeId
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainMatchContainer {
  background: #0e3c46;
  padding: 20px;
  box-shadow: 0px 0px 3px black;
  table {
    border-collapse: collapse;
    width: 100%;
    .totalScore {
      color: white;
      font-weight: 600;
      padding-right: 15px;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    a:hover {
      color: white;
    }
    .setScores {
      color: #979797;
    }
    .row-schedule {
      margin-left: 20px;
      padding: 0px 20px;
      border-top: none;
      border-bottom: 1px solid #ffffff2b;
    }
    :last-child {
      border-bottom: none;
    }
  }
}

.containerLink {
  margin-top: 10px;
  padding: 20px 0px 0px 0px;
  border-top: 1px solid #6f6f6f;
}

.winner-color {
  color: #40c500;
}

.w30pc {
  width: 30%;
}

.score + .score:before {
  content: ", ";
}
</style>
