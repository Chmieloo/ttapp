<template>
  <div class="mainContainer">
    <div class="matchInfo">
      MATCH MODE: {{ match.modeName }}
      </div>
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
      <div class="midInfoHeader">SET SCORE</div>
      <div class="midInfoValue">
        <div class="tableSetScores">
          <div v-bind:class="flipped ? 'set-container-fr' : 'set-container-fl'">
            <div v-for="(score, index) in match.scores" v-bind:key="index" class="rowData">
              <span v-if="match.currentSet - 1 != index">{{ score.home }}</span>
            </div>
          </div>
          <div v-bind:class="flipped ? 'set-container-fl' : 'set-container-fr'">
            <div v-for="(score, index) in match.scores" v-bind:key="index" class="rowData">
              <span v-if="match.currentSet - 1 != index">{{ score.away }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="result-dialog" v-if="warmupVisible">
        <table class="warmupTable">
          <tr>
            <td class="left">
              <div class="label-sr">SPORTRADAR</div>
              <div class="label-tt">table tennis league</div>
              <div class="label-playoffs">PLAYOFFS</div>
            </td>
            <td class="right">
              <div class="label-warmup">
                <i class="fas fa-stopwatch"></i>
                warm-up
                </div>
              <div class="label-clock">{{ this.clock }}</div>
              <div class="label-match-name">
                <i class="fas fa-arrow-alt-circle-right"></i>
                {{ match.matchName }}
                </div>
              <div class="label-players">
                <span>{{ match.homePlayerDisplayName }}</span>
                <span style="color: #6f6f6f;">vs</span>
                <span>{{ match.awayPlayerDisplayName }}</span>
              </div>
              <div class="label-match-name-next" style="margin-top: 30px;">next: {{ match.nextMatchName }}</div>
              <div class="label-players-next">
                <span>{{ match.nextMatchHomePlayer }}</span>
                <span style="color: #6f6f6f;">vs</span>
                <span>{{ match.nextMatchAwayPlayer }}</span>
              </div>
              <div>
                <ul class="circles">
                  <li>playoffs</li>
                  <li>table tennis</li>
                </ul>
              </div>
            </td>
          </tr>
        </table>
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
      <button v-gamepad:button-start="toggleVisibility">Press me!</button>
    </div>
    <div v-if="!match.isFinished">
      <div v-bind:class="serverFlipped ? 'container-fr' : 'container-fl'">
        <span v-if="this.numServes === 2" class="server">
          <i class="fas fa-table-tennis"></i>
          <i class="fas fa-table-tennis"></i>
        </span>
        <span v-if="this.numServes === 1" class="server">
          <i class="fas fa-table-tennis"></i>
        </span>
      </div>
    </div>
    <div v-if="isConnected()">
      <i class="fas fa-gamepad pad"></i>
    </div>
    <div class="footer" v-if="warmupVisible == 0 && match.nextMatchId">
      <span>next:</span>
      <span>{{ match.nextMatchName }}</span>
      <span class="playerName">{{ match.nextMatchHomePlayer }}</span>
      <span>vs</span>
      <span class="playerName">{{ match.nextMatchAwayPlayer }}</span>
      <span v-if="match.nextMatchId && match.isFinished">
        <router-link :to="{ name: 'PlayoffsMatchList' }"><i class="fas fa-play-circle"></i></router-link>
      </span>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueGamepad from 'vue-gamepad'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'

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
      flipped: false,
      gamepadConnected: 0,
      homeScore: 0,
      awayScore: 0,
      endSet: 0,
      matchScores: [],
      serverFlipped: false,
      numServes: 2,
      idle: true,
      warmupVisible: true,
      post2Channel: true,
      clock: null,
      warmupSeconds: 0,
      warmupDeadline: null,
      clockInterval: null
    }
  },
  mounted () {
    this.idle = false
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.match = res.data
      this.homeScore = res.data.currentHomePoints ? res.data.currentHomePoints : 0
      this.awayScore = res.data.currentAwayPoints ? res.data.currentAwayPoints : 0
      this.idle = true
      this.checkServer()
    })
    this.setupClock()
  },
  methods: {
    setupClock () {
      this.warmupSeconds = 1
      var currentTime = Date.parse(new Date())
      this.warmupDeadline = new Date(currentTime + (this.warmupSeconds / 60) * 60 * 1000)
      this.clockInterval = setInterval(this.runClock, 1000)
    },
    runClock () {
      var t = this.timeRemaining(this.warmupDeadline)
      this.clock = t.minutes + ':' + t.seconds
      if (t.total <= 0) {
        clearInterval(this.clockInterval)
        this.warmupVisible = false
      }
    },
    timeRemaining (endtime) {
      var t = Date.parse(endtime) - Date.parse(new Date())
      var seconds = Math.floor((t / 1000) % 60)
      var minutes = Math.floor((t / 1000 / 60) % 60)
      if (minutes < 10) {
        minutes = '0' + minutes
      }
      if (seconds < 10) {
        seconds = '0' + seconds
      }
      return {'total': t, 'minutes': minutes, 'seconds': seconds}
    },
    toggleVisibility () {
      this.warmupVisible = !this.warmupVisible
    },
    postResults (event) {
      axios.post('/api/matches/save', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        matchId: event.target.elements.matchId.value,
        post2Channel: this.post2Channel,
        h1: typeof event.target.elements.home_set_1 === 'undefined' ? '' : event.target.elements.home_set_1.value,
        h2: typeof event.target.elements.home_set_2 === 'undefined' ? '' : event.target.elements.home_set_2.value,
        h3: typeof event.target.elements.home_set_3 === 'undefined' ? '' : event.target.elements.home_set_3.value,
        h4: typeof event.target.elements.home_set_4 === 'undefined' ? '' : event.target.elements.home_set_4.value,
        a1: typeof event.target.elements.away_set_1 === 'undefined' ? '' : event.target.elements.away_set_1.value,
        a2: typeof event.target.elements.away_set_2 === 'undefined' ? '' : event.target.elements.away_set_2.value,
        a3: typeof event.target.elements.away_set_3 === 'undefined' ? '' : event.target.elements.away_set_3.value,
        a4: typeof event.target.elements.away_set_4 === 'undefined' ? '' : event.target.elements.away_set_4.value
      }).then((res) => {
        this.errors = []
        console.log(res.data)
        if (res.status === 200) {
          // self.$router.push({ name: 'MatchView', params: { id: this.match.matchId } })
          // TODO for now - fix that to use router
          document.location.href = '/'
        }
        return true
      }).catch(error => {
        console.log(error)
        this.errors = []
      })
    },
    range: function (min, max) {
      var array = []
      var j = 0
      for (var i = min; i <= max; i++) {
        array[j] = i
        j++
      }
      return array
    },
    checkServer () {
      console.log('Checking server.')
      console.log('Current set: ' + this.match.currentSet)
      var setNumber = this.match.currentSet
      if (this.homeScore === 0 && this.awayScore === 0) {
        this.numServes = 2

        if (this.match.serverId === this.match.homePlayerId) {
          this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
        } else {
          this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
        }
      }
      var totalScore = this.homeScore + this.awayScore
      if (totalScore < 20) {
        this.numServes = 2 - (totalScore % 2)

        var mod4 = (totalScore % 4)
        if (mod4 === 1) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          }
        } else if (mod4 === 2) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          }
        } else if (mod4 === 3) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          }
        } else if (mod4 === 0) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          }
        }
      } else {
        this.numServes = 1
        var mod2 = (totalScore % 2)
        if (mod2 === 1) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          }
        } else if (mod2 === 0) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? this.flipped : !this.flipped
          } else {
            this.serverFlipped = ((parseInt(setNumber) + 1) % 2 === 0) ? !this.flipped : this.flipped
          }
        }
      }
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
      if (this.endSet && this.idle) {
        this.idle = false
        axios.get('/api/matches/' + this.$route.params.id + '/finish').then((res) => {
          if (res.data) {
            this.endSet = 0
            this.flipSides()
            this.resetScores()
            this.match = res.data
            this.idle = true
            this.numServes = 2
            console.log(res.data)
            this.checkServer()
          }
        })
      }
    },
    flipSides () {
      this.flipped = !!((this.flipped + 1) % 2)
      this.checkServer()
    },
    flipServer () {
      this.serverFlipped = !!((this.serverFlipped + 1) % 2)
    },
    setServer () {
      // allow only if we are in the idle state
      if (this.idle === false) {
        return false
      }
      // set idle state to 1, as we are sending request to change server
      this.idle = false
      // change INITIAL server, by default it is home
      axios.get('/api/matches/' + this.$route.params.id + '/server').then((res) => {
        if (res.data) {
          this.flipServer()
          this.match = res.data
          this.checkServer()
          this.idle = true
        }
      })
    },
    resetScores () {
      this.homeScore = 0
      this.awayScore = 0
    },
    addPointLeft () {
      if (!this.endSet && this.idle) {
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
      if (!this.endSet && this.idle) {
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
    /**
     * Substract points from player on the left side of the screen
     */
    subPointLeft () {
      if (this.idle) {
        if (this.flipped) {
          if (this.awayScore > 0) {
            this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
            this.delPoint(0, 1, this.match.matchId)
          }
        } else {
          if (this.homeScore > 0) {
            this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
            this.delPoint(1, 0, this.match.matchId)
          }
        }
        this.checkFinalScore()
        this.checkServer()
      }
    },
    subPointRight () {
      if (this.idle) {
        if (this.flipped) {
          if (this.homeScore > 0) {
            this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
            this.delPoint(1, 0, this.match.matchId)
          }
        } else {
          if (this.awayScore > 0) {
            this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
            this.delPoint(0, 1, this.match.matchId)
          }
        }
        this.checkFinalScore()
        this.checkServer()
      }
    },
    isConnected () {
      var body = document.getElementsByTagName('body')
      return body[0].classList.contains('gamepad-connected')
    },
    savePoint (homeScore, awayScore, matchId) {
      // allow only when idle
      if (this.idle === false) {
        return false
      }
      // set state
      this.idle = false
      axios.post('/api/points/add', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        home: homeScore,
        away: awayScore,
        matchId: matchId
      }).then((res) => {
        console.log('response status: ' + res.status + ', ' + res.data.text)
        this.match.currentSet = res.data.currentSet
        this.idle = true
      }).catch(error => {
        this.idle = true
        console.log('error while adding point: ' + error.response)
      })
    },
    delPoint (homeScore, awayScore, matchId) {
      // allow only when idle
      if (this.idle === false) {
        return false
      }
      // set state
      this.idle = false
      axios.post('/api/points/del', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        home: homeScore,
        away: awayScore,
        matchId: matchId
      }).then((res) => {
        // TODO log
        this.idle = true
        // console.log('Point removed')
      }).catch(error => {
        // TODO log
        this.idle = true
        console.log(error.response)
      })
    }
  }
}
</script>

<style lang="less" scoped>
.maincontainer {
  overflow: hidden;
}

.rowData {
  border: none;
  font-size: 60px;
  color: #f5f5f5;
}

.matchInfo {
  margin: 10px 0px 30px 0px;
  background-color: rgb(65, 65, 65);
  padding: 10px 10px;
  text-align: center;
  text-transform: uppercase;
  color: white;
  font-size: 30px;
  font-weight: 600;
  letter-spacing: 3px;
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

.submit-button {
  font-size: 16px;
  padding: 10px 20px;
  background: #909090;
  color: white;
  font-weight: 600;
}

.span-score {
  min-width: 100px;
  float: left;
  margin-right: 30px;
  input {
    max-width: 30px;
    padding: 10px 10px;
    margin: 0px 3px;
    text-align: center;
  }
}

.server {
  font-size: 70px;
  color: white;
}

.header-title {
  font-size: 50px;
  color: #40c500;
}

.result-button {
  background-color: #666666ed;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  margin-top: 10px;
}

.fl {
  float: left;
}

.result-dialog {
  color: white;
  width: 100%;
  height: 100%;
  background-color: #000000;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
}

.warmupTable {
  border: none;
  margin: 0;
  width: 100%;
  height: 100%;
  border-collapse: collapse;
  .left {
    background-color: #0e0e11;
    width: 30%;
    font-family: 'Biryani', sans-serif;
    font-weight: 800;
    .label-sr {
      font-size: 50px;
    }
    .label-tt {
      font-size: 30px;
      font-weight: 100;
      font-family: 'Biryani', sans-serif;
    }
    .label-playoffs {
      margin-top: 15px;
      font-size: 50px;
    }
  }
  .right {
    color: #0e0e11;
    text-align: left;
    padding-left: 80px;
    width: 70%;
    background: linear-gradient(180deg, #e4e3e1 45%, #b2b1b3 80%);
    font-family: 'Biryani', sans-serif;
    font-weight: 600;
    .label-warmup {
      font-size: 40px;
      width: 400px;
      border-bottom: 5px solid #e6a84a;
    }
    .label-clock {
      font-size: 70px;
    }
    .label-match-name {
      font-size: 30px;
      color: #000;
    }
    .label-players {
      font-size: 40px;
    }
    .label-match-name-next {
      font-size: 25px;
      color: #656565;
    }
    .label-players-next {
      font-size: 25px;
    }
  }
}

.tableImg {
  overflow: hidden;
  background: linear-gradient(100deg, #556187 30%, #333e6c 60%);
  height: 100px;
  width: 200px;
  border: 5px solid white;
  transform: rotate(10deg);
}

.circles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.circles li {
    position: absolute;
    display: block;
    list-style: none;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    animation: animate 25s linear infinite;
    bottom: -150px;
}

.circles li:nth-child(1) {
  top: 0;
  left: 100%;
  font-size: 600px;
  animation-delay: 0s;
}

.circles li:nth-child(2) {
  top: 0;
  left: 100%;
  width: 1000px;
  height: 20px;
  font-size: 100px;
  animation-delay: 2s;
  animation-duration: 60s;
}

@keyframes animate {
  0%{
    transform: translateX(0) rotate(0deg);
    opacity: 0.05;
  }
  100%{
    transform: translateX(-3000px) rotate(0deg);
    opacity: 0.01;
  }
}

.footer {
  left: 0;
  width: 100%;
  line-height: 80px;
  height: 80px;
  background-color: black;
  position: fixed;
  bottom: 0;
  text-align: center;
  font-size: 40px;
  .playerName {
    color: white;
    font-weight: 600;
  }
}
</style>
