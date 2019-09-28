<template>
  <div class="mainMatchContainer">
    <div v-if="matches.length">
      <table class="fullWidth">
        <tr v-for="match in matches" v-bind:key="match.id" class="row-data row-schedule">
          <td>
            {{ match.dateOfMatch }}
          </td>
          <td>
            {{ match.timeOfMatch }}
          </td>
          <td class="padl20 padr20">
            {{ match.groupName }}
          </td>
          <td class="playerName txt-right">
            <router-link :to="'/player/' + match.homePlayerId + '/info'">{{ match.homePlayerDisplayName }}</router-link>
          </td>
          <td class="padl20 padr20" style="text-align: center;">-</td>
          <td class="playerName">
            <router-link :to="'/player/' + match.awayPlayerId + '/info'">{{ match.awayPlayerDisplayName }}</router-link>
          </td>
          <td style="text-align: right; color: white;">
            <div><router-link :to="{ name: 'MatchView', params: { id: match.matchId }}"><i class="fas fa-play-circle"></i></router-link></div>
          </td>
        </tr>
      </table>
    </div>
    <div class="containerLink">
      <i class="fas fa-arrow-circle-right"></i>
      <router-link :to="'/tournament/' + currentTournament + '/match/list'">show full schedule</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MatchSchedule',
  data () {
    return {
      matches: [],
      currentTournament: 0
    }
  },
  mounted () {
    axios.get('/api/tournaments/0/fixtures/20').then((res) => {
      this.matches = res.data
      this.currentTournament = res.data[0].tournamentId
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.fullWidth {
  width: 100%;
}

.mainMatchContainer {
  background: #0e3c46;
  padding: 20px;
  box-shadow: 0px 0px 3px black;
  table {
    border-collapse: collapse;
    .padl20 {
      padding-left: 20px;
    }
    .padr20 {
      padding-right: 20px;
    }
    .playerName {
      color: white;
    }
    .totalScore {
      color: white;
      font-weight: 600;
      padding-right: 15px;
    }
    .setScores {
      color: #979797;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    a:hover {
      color: white;
      text-decoration: underline;
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
