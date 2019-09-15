<template>
  <div class="mainMatchContainer">
    <div v-if="matches.length">
      <table class="fullWidth">
        <tr v-for="match in matches" v-bind:key="match.id" class="row-data">
          <td>
            {{ match.dateOfMatch }}
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
          <td style="text-align: right;">
            <div><router-link :to="{ name: 'MatchView', params: { id: match.matchId }}"><i class="fas fa-eye"></i></router-link></div>
          </td>
        </tr>
      </table>
    </div>
    <div v-else>
      No overdue matches
      </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MatchScheduleOverdue',
  data () {
    return {
      matches: []
    }
  },
  mounted () {
    axios.get('/api/tournaments/0/overdue/10').then((res) => {
      this.matches = res.data
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
  margin-bottom: 20px;
  background: #3e3e3e;
  padding: 20px;
  table {
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
