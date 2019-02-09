<template>
  <div class="mainMatchContainer">
    <table>
      <tr v-for="match in matches" v-bind:key="match.id" class="row-data">
        <td>{{ match.dateOfMatch }}</td>
        <td class="txt-right" v-bind:class="match.winnerId == match.homePlayerId ? 'winner-color' : ''">
          {{ match.homePlayerName }}
        </td>
        <td style="width: 30px; text-align: center;">-</td>
        <td v-bind:class="match.winnerId == match.awayPlayerId ? 'winner-color' : ''">
          {{ match.awayPlayerName }}
        </td>
        <td>
          <span class="totalScore">
          {{ match.homeScoreTotal }} - {{ match.awayScoreTotal }} :: {{ match.isWalkover }}
          </span>
          <span v-if="match.isWalkover = 1">
            {{ match.isWalkover }}
          </span>
          <span v-else class="setScores">
            (
            <span v-for="score in match.scores" v-bind:key="score.set" class="score">
              {{ score.home }} - {{ score.away }}
            </span>
            )
          </span>
        </td>
      </tr>
    </table>
    <div class="containerLink">
      <i class="fas fa-arrow-circle-right"></i>
      <router-link to="/tournament/match/list">show all tournament matches</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MatchResults',
  data () {
    return {
      matches: []
    }
  },
  mounted () {
    axios.get('/api/tournaments/0/results/10').then((res) => {
      this.matches = res.data
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainMatchContainer {
  background: #3e3e3e;
  padding: 20px;
  table {
    width: 100%;
    .totalScore {
      color: white;
      font-weight: 600;
      padding-right: 15px;
    }
    .setScores {
      color: #979797;
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
