<template>
  <div class="mainContainer">
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="padding-right: 20px; width: 25%;">
          <router-link to="/playoffs/ladders" tag="div" class="playoffBanner">
            <div style="width: 100%; text-align: center;">
              <div style="margin-bottom: 10px;">
                <span class="header-title">
                  SHOW ALL LADDERS
                </span>
              </div>
              <div>
                <span class="fa-stack">
                    <i class="fas fa-trophy" style="font-size: 25px; color: white;"></i>
                </span>
              </div>
            </div>
          </router-link>
        </td>
        <td style="padding-right: 20px; width: 25%;">
          <router-link to="/playoffs/group/8/ladder" tag="div" class="playoffBanner">
            <div style="width: 100%; text-align: center;">
              <div style="margin-bottom: 10px;">
                <span class="header-title">
                  PREMIER LEAGUE LADDER
                </span>
              </div>
              <div>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
              </div>
            </div>
          </router-link>
        </td>
        <td style="padding-right: 20px; width: 25%;">
          <router-link to="/playoffs/group/9/ladder" tag="div" class="playoffBanner">
            <div style="width: 100%; text-align: center;">
              <div style="margin-bottom: 10px;">
                <span class="header-title">
                  CHAMPIONSHIP LADDER
                </span>
              </div>
              <div>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
              </div>
            </div>
          </router-link>
        </td>
        <td style="width: 25%;">
          <router-link to="/playoffs/group/10/ladder" tag="div" class="playoffBanner">
            <div style="width: 100%; text-align: center;">
              <div style="margin-bottom: 10px;">
                <span class="header-title">
                  CONFERENCE LADDER
                </span>
              </div>
              <div>
                <span class="fa-stack">
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield"></i>
                    <i class="fas fa-shield-alt fa-stack-2x stack-shield-reversed"></i>
                    <i class="fas fa-star fa-stack-2x stack-star"></i>
                </span>
              </div>
            </div>
          </router-link>
        </td>
      </tr>
    </table>
    <span class="header-title">
      <i class="fas fa-clipboard-list marr20"></i>
      PLAYOFFS SCHEDULE <span style="margin-left: 40px;">( * TO BE DECIDED )</span>
    </span>
    <div class="mainMatchContainer">
      <table style="width: 100%">
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
            <span style="color: #aaa;" v-if="match.homePlayerId == 0">{{ match.homePlayerDisplayName }}</span>
            <span v-else>{{ match.homePlayerDisplayName }}</span>
          </td>
          <td class="padl20 padr20 txt-center">-</td>
          <td class="playerName txt-left">
            <span style="color: #aaa;" v-if="match.awayPlayerId == 0">{{ match.awayPlayerDisplayName }}</span>
            <span v-else>{{ match.awayPlayerDisplayName }}</span>
          </td>
          <td class="playerName">
            {{ match.name }}
          </td>
          <td style="text-align: center; min-width: 50px;" v-if="playoffs && !match.winnerId && match.homePlayerId != 0 && match.awayPlayerId != 0">
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
      playoffs: true
    }
  },
  mounted () {
    axios.get('/api/playoffs/0').then((res) => {
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
  margin-top: 20px;
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

.header-title {
  margin-bottom: 20px;
  font-size: 20px;
}

.w30pc {
  width: 30%;
}

.score + .score:before {
  content: ", ";
}

.playoffBanner {
   margin-top: 40px;
   background-color: #556187;
   padding: 20px 10px;
   width: 95%;
   border-radius: 10px;
}

.playoffBanner:hover {
  background-color: #9cbed2;
  cursor: pointer;
}

.stack-shield-reversed {
  transform: scale(-1, 1);
}

.stack-shield-reversed, .stack-shield {
  color: white;
}

.stack-star {
  color: #556187;
  font-size: 15px;
  margin-top: 7px;
}

.txt-left {
  text-align: left;
}
</style>
