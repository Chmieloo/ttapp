<template>
  <div class="mainContainer">
    <div style="margin-bottom: 10px; padding: 0px 10px;">
      <span class="header-title">
        {{ division }} LADDER
      </span>
    </div>
    <table style="width: 100%;">
      <tr>
        <td v-for="i in this.stages" v-bind:key="i" class="stageColumn">
          <div class="stageDiv">
            <div v-for="match in matches" v-bind:key="match.id">
              <div v-if="match.stage == i" style="margin-bottom: 20px;">
                <table class="ladderTable">
                  <tr>
                    <td rowspan="3" class="matchOrder">
                      {{ match.order }}
                    </td>
                    <td style="padding-left: 20px;" class="matchName">
                      {{ match.name }}
                    </td>
                  </tr>
                  <tr>
                    <td style="padding-left: 20px;">{{ match.homePlayerDisplayName }}</td>
                  </tr>
                  <tr>
                    <td style="padding-left: 20px;">{{ match.awayPlayerDisplayName }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </td>
      </tr>
    </table>
    <div class="mainMatchContainer" style="margin-top: 20px;">
      <table style="width: 100%;">
        <tr v-for="match in matches" v-bind:key="match.id" class="row-data">
          <td>
            {{ match.order }}
          </td>
          <td>
            {{ match.dateOfMatch }}
          </td>
          <td class="padl20 padr20">
            {{ match.division }}
          </td>
          <td class="playerName txt-right">
            {{ match.homePlayerDisplayName }}
          </td>
          <td class="padl20 padr20">-</td>
          <td class="playerName">
            {{ match.awayPlayerDisplayName }}
          </td>
          <td class="playerName">
            {{ match.name }}
          </td>
          <td style="text-align: center; min-width: 50px;" v-if="playoffs">
            <router-link :to="{ name: 'MatchPlayoffView', params: { id: match.matchId }}"><i class="fas fa-play-circle"></i></router-link>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PlayoffsMatchList',
  data () {
    return {
      matches: [],
      stages: 4,
      division: null,
      playoffs: false
    }
  },
  mounted () {
    axios.get('/api/playoffs/0/group/' + this.$route.params.id).then((res) => {
      this.matches = res.data
      this.division = res.data[0].division
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

.stageColumn {
  width: 25%;
  padding: 10px;
  .stageDiv {
    padding: 20px;
    padding-top: 40px;
    background-color: #151515;
  }
}

.matchOrder {
  background-color: black;
  color: white;
  width: 50px;
  text-align: center;
}

.matchName {
  padding-left: 20px;
  background-color: brown;
}

.ladderTable {
   width: 100%;
   color: white;
   td {
     padding: 10px 10px;
   }
}
</style>
