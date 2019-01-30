<template>
  <div class="mainMatchContainer">
    <table>
      <tr v-for="match in matches" v-bind:key="match.id" class="row-data">
        <td class="w30pc" v-bind:class="match.winnerId == match.homePlayerId ? 'winner-color' : ''">
          {{ match.homePlayerName }}
        </td>
        <td>-</td>
        <td class="w30pc" v-bind:class="match.winnerId == match.awayPlayerId ? 'winner-color' : ''">
          {{ match.awayPlayerName }}
        </td>
        <td>
          <span class="totalScore">
          {{ match.homeScoreTotal }} - {{ match.awayScoreTotal }}
          </span>
          <span class="setScores">
            (
            <span v-for="score in match.scores" v-bind:key="score.set" class="score">
              {{ score.home }} - {{ score.away }}
            </span>
            )
          </span>
        </td>
      </tr>
    </table>
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
    axios.get('/api/matches').then((res) => {
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
