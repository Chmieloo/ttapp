<template>
  <div class="mainContainer">
    <div v-bind:class="flipped ? 'container-fr' : 'container-fl'">
      <div>
        <span class="header-title">{{ match.homePlayerDisplayName }}</span>
        <div v-if="match.isFinished == 1">
          <div v-if="match.winnerId == 0">
            <div class="rallyScore">
              {{ match.homeScoreTotal }}
            </div>
          </div>
          <div v-else-if="match.winnerId == match.homePlayerId">
            <div class="rallyScoreWinner">
              {{ match.homeScoreTotal }}
            </div>
          </div>
          <div v-else>
            <div class="rallyScore">
              {{ match.homeScoreTotal }}
            </div>
          </div>
        </div>
        <div v-else>
          <div class="rallyScore">
            {{ homeScore }}
          </div>
        </div>
      </div>
    </div>
    <div v-bind:class="flipped ? 'container-fl' : 'container-fr'">
      <div>
        <span class="header-title">{{ match.awayPlayerDisplayName }}</span>
        <div v-if="match.isFinished == 1">
          <div v-if="match.winnerId == 0">
            <div class="rallyScore">
              {{ match.awayScoreTotal }}
            </div>
          </div>
          <div v-else-if="match.winnerId == match.awayPlayerId">
            <div class="rallyScoreWinner">
              {{ match.awayScoreTotal }}
            </div>
          </div>
          <div v-else>
            <div class="rallyScore">
              {{ match.awayScoreTotal }}
            </div>
          </div>
        </div>
        <div v-else>
          <div class="rallyScore">
            {{ awayScore }}
          </div>
        </div>
      </div>
    </div>
    <div class="container-mid">
      <div class="midInfoHeader">MATCH MODE</div>
      <div class="midInfoValue">BO4</div>
      <div class="midInfoHeader">SET SCORE</div>
      <div class="midInfoValue">
        <div class="tableSetScores">
          <div v-bind:class="flipped ? 'set-container-fr' : 'set-container-fl'">
            <div v-for="score in match.scores" v-bind:key="score.id" class="rowData">
              <span>{{ score.home }}</span>
            </div>
          </div>
          <div v-bind:class="flipped ? 'set-container-fl' : 'set-container-fr'">
            <div v-for="score in match.scores" v-bind:key="score.id" class="rowData">
              <span>{{ score.away }}</span>
            </div>
          </div>
        </div>
      </div>
      <div v-show="endSet" class="endSet" v-gamepad:shoulder-right="finalizeSet">
        <div>Press [R] to confirm set score.</div>
        <div><img src="./assets/padr.png" /></div>
      </div>
    </div>
    <div style="clear: both;"></div>
    <div style="display: none;">
      <button v-gamepad:button-y="addPointLeft">Press me!</button>
      <button v-gamepad:button-a="addPointRight">Press me!</button>
      <button v-gamepad:button-x="subPointLeft">Press me!</button>
      <button v-gamepad:button-b="subPointRight">Press me!</button>
      <button v-gamepad:shoulder-left="flipSides">Press me!</button>
      <button v-gamepad:button-select="setServer">Press me!</button>
    </div>
    <div v-bind:class="serverFlipped ? 'container-fr' : 'container-fl'">
      <span v-if="this.numServes === 2" class="server">
        <i class="fas fa-table-tennis"></i>
        <i class="fas fa-table-tennis"></i>
      </span>
      <span v-if="this.numServes === 1" class="server">
        <i class="fas fa-table-tennis"></i>
      </span>
    </div>
    <div v-if="isConnected()">
      <i class="fas fa-gamepad pad"></i>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueGamepad from 'vue-gamepad'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'

// import CustomView from './Custom/CustomDialog.vue';

// const VIEW_NAME = 'custom-comonent';
// Vue.dialog.registerComponent(VIEW_NAME, CustomView);

Vue.use(VuejsDialog)
Vue.use(VueGamepad)

export default {
  components: {
    VueGamepad,
    VuejsDialog
  },
  data () {
    return {
      match: [],
      flipped: 0,
      gamepadConnected: 0,
      homeScore: 0,
      awayScore: 0,
      endSet: 0,
      matchScores: [],
      serverFlipped: null,
      numServes: 2
    }
  },
  mounted () {
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.match = res.data
    })
  },
  created: function () {
    // window.addEventListener('gamepadconnected', this.handleGamepadConnect)
    // window.addEventListener('gamepaddisconnected', this.handleGamepadDisconnect)
  },
  methods: {
    checkServer () {
      if (this.homeScore === 0 && this.awayScore === 0) {
        return false
      }
      if (this.homeScore <= 10 && this.awayScore <= 10) {
        this.numServes = 2 - ((this.homeScore + this.awayScore) % 2)
        if (((this.homeScore + this.awayScore) % 2) === 0) {
          this.flipServer()
        }
      } else {
        this.numServes = 1
        this.flipServer()
      }
      // when should we flip sides ?
    },
    // logic to check if set is finished
    isFinishedSet () {
      if (this.homeScore >= 11 || this.awayScore >= 11) {
        if (Math.abs(this.homeScore - this.awayScore) < 2) {
          return false
        } else {
          return this.confirmFinalScore()
        }
      } else {
        return false
      }
    },
    checkFinalScore () {
      if (this.homeScore >= 11 || this.awayScore >= 11) {
        if (Math.abs(this.homeScore - this.awayScore) < 2) {
          this.endSet = 0
        } else {
          this.endSet = 1
        }
      } else {
        this.endSet = 0
      }
    },
    finalizeSet () {
      if (this.endSet) {
        axios.get('/api/matches/' + this.$route.params.id + '/finish').then((res) => {
          if (res.data) {
            this.endSet = 0
            this.flipSides()
            this.resetScores()
            this.match = res.data
            this.numServes = 2
            console.log(res.data)
            if (this.flipped === 1) {
              // home on the right
            } else {
              // home on the left
            }
            if (res.data.serverId === res.data.homePlayerId) {
              // TODO
            } else {
              // TODO
            }
          }
        })
      }
    },
    flipSides () {
      this.flipped = (this.flipped + 1) % 2
    },
    flipServer () {
      this.serverFlipped = (this.serverFlipped + 1) % 2
    },
    setServer () {
      // change INITIAL server, by default it is home
      axios.get('/api/matches/' + this.$route.params.id + '/server').then((res) => {
        if (res.data) {
          this.flipServer()
          this.match = res.data
          this.checkServer()
        }
      })
    },
    resetScores () {
      this.homeScore = 0
      this.awayScore = 0
    },
    addPointLeft () {
      if (!this.endSet) {
        if (this.flipped) {
          this.awayScore++
          this.savePoint(0, 1, this.match.matchId)
        } else {
          this.homeScore++
          this.savePoint(1, 0, this.match.matchId)
        }
        this.checkFinalScore()
        this.checkServer()
      }
    },
    addPointRight () {
      if (!this.endSet) {
        if (this.flipped) {
          this.homeScore++
          this.savePoint(1, 0, this.match.matchId)
        } else {
          this.awayScore++
          this.savePoint(0, 1, this.match.matchId)
        }
        this.checkFinalScore()
        this.checkServer()
      }
    },
    subPointLeft () {
      if (this.flipped) {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
        this.delPoint(0, 1, this.match.matchId)
      } else {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
        this.delPoint(1, 0, this.match.matchId)
      }
      this.checkFinalScore()
      this.checkServer()
    },
    subPointRight () {
      if (this.flipped) {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
        this.delPoint(1, 0, this.match.matchId)
      } else {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
        this.delPoint(0, 1, this.match.matchId)
      }
      this.checkFinalScore()
      this.checkServer()
    },
    isConnected () {
      var body = document.getElementsByTagName('body')
      return body[0].classList.contains('gamepad-connected')
    },
    savePoint (homeScore, awayScore, matchId) {
      axios.post('/api/points/add', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        home: homeScore,
        away: awayScore,
        matchId: matchId
      }).then((res) => {
        console.log('Point added')
      }).catch(error => {
        console.log(error.response)
      })
    },
    delPoint (homeScore, awayScore, matchId) {
      axios.post('/api/points/del', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        home: homeScore,
        away: awayScore,
        matchId: matchId
      }).then((res) => {
        console.log('Point removed')
      }).catch(error => {
        console.log(error.response)
      })
    }
  }
}
</script>

<style lang="less" scoped>
.rowData {
  border: none;
  font-size: 60px;
  color: #f5f5f5;
}

.mainContainer {
  margin-top: 30px;
}

.infoPanel {
  width: 100%;
  padding: 5px 20px;
  background-color: #222;
}

.pad {
  color: #fff;
  font-size: 30px;
  position: absolute;
  top: 0px;
  right: 10px;
  line-height: 50px;
}

.container-fl {
  float: left;
  width: 40%;
  text-align: center;
  border-right: 1px solid #222;
}

.container-fr {
  float: right;
  width: 40%;
  text-align: center;
  border-left: 1px solid #222;
}

.set-container-fl {
  float: left;
  width: 40%;
  text-align: right;
}

.set-container-fr {
  float: right;
  width: 40%;
  text-align: left;
}

.container-mid {
  margin-left: auto;
  margin-right: auto;
  width: 20%;
  text-align: center;
  .midInfoHeader {
    text-transform: uppercase;
    font-size: 40px;
    font-weight: 600;
  }
  .midInfoValue {
    padding-top: 0px;
    font-size: 40px;
    color: #555;
  }
}

.rallyScore {
  width: 95%;
  font-size: 400px;
  line-height: 400px;
  font-weight: 900;
  color: white;
}

.endSet {
  font-size: 50px;
  color: white;
  font-weight: 600;
  position: absolute;
  background: #666666ed;
  left: 0;
  right: 0;
  padding: 50px 0px;
}

.rallyScoreWinner {
  width: 95%;
  font-size: 400px;
  line-height: 400px;
  font-weight: 900;
  color: #40c500;
}

.scoreContainer {
  width: 40%;
  vertical-align: top;
  text-align: center;
}

.scoreLeft {
  border-right: 1px solid #222;
}

.scoreRight {
  border-left: 1px solid #222;
}

.server {
  font-size: 50px;
  color: white;
}

.header-title {
  font-size: 50px;
  color: #40c500;
}
</style>
