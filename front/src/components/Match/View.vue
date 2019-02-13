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
      <div class="midInfoValue">best of 4</div>
      <div class="midInfoHeader">SET SCORE</div>
      <div class="midInfoValue">
        <span>{{ match.homeScoreTotal }}</span>
        <span>:</span>
        <span>{{ match.awayScoreTotal }}</span>
      </div>
      <div v-show="endSet" class="endSet" v-gamepad:shoulder-right="finalizeSet">
        Press [R] to confirm set score.
      </div>
    </div>
    <div style="clear: both;"></div>
    <div style="display: none;">
      <button v-gamepad:button-y="addPointLeft">Press me!</button>
      <button v-gamepad:button-a="addPointRight">Press me!</button>
      <button v-gamepad:button-x="subPointLeft">Press me!</button>
      <button v-gamepad:button-b="subPointRight">Press me!</button>
      <button v-gamepad:shoulder-left="flipSides">Press me!</button>
    </div>
    <div>
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
      endSet: 0
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
    // logic to check if set is fninished
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
          // Now the confirmation dialog is visible
        }
      } else {
        this.endSet = 0
      }
    },
    finalizeSet () {
      if (this.endSet) {
        axios.get('/api/matches/' + this.$route.params.id + '/finish').then((res) => {
          this.endSet = 0
          this.flipSides()
          this.resetScores()
          this.match = res.data
        })
      }
    },
    flipSides () {
      this.flipped = (this.flipped + 1) % 2
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
      }
    },
    subPointLeft () {
      if (this.flipped) {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
      } else {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
      }
      this.checkFinalScore()
    },
    subPointRight () {
      if (this.flipped) {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
      } else {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
      }
      this.checkFinalScore()
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
        // this.errors.push(error.response.data.errorText)
      })
    }
  }
}
</script>

<style lang="less" scoped>
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

.container-mid {
  margin-left: auto;
  margin-right: auto;
  width: 20%;
  text-align: center;
  .midInfoHeader {
    text-transform: uppercase;
    font-size: 30px;
    font-weight: 600;
  }
  .midInfoValue {
    padding-top: 0px;
    font-size: 30px;
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
  background: #6666666b;
  left: 0;
  right: 0;
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

.header-title {
  font-size: 50px;
  color: #40c500;
}
</style>
