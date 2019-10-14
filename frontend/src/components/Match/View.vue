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
            <div class="eloInfo">
              {{ match.homeElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldHomeElo }} &rarr; {{ match.newHomeElo }}
            </div>
          </div>
          <div v-else-if="match.winnerId == match.homePlayerId">
            <div class="rallyScoreWinner">
              {{ match.homeScoreTotal }}
            </div>
            <div class="eloInfo">
              {{ match.homeElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldHomeElo }} &rarr; {{ match.newHomeElo }}
            </div>
          </div>
          <div v-else>
            <div class="rallyScore">
              {{ match.homeScoreTotal }}
            </div>
            <div class="eloInfo">
              {{ match.homeElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldHomeElo }} &rarr; {{ match.newHomeElo }}
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
            <div class="eloInfo">
              {{ match.awayElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldAwayElo }} &rarr; {{ match.newAwayElo }}
            </div>
          </div>
          <div v-else-if="match.winnerId == match.awayPlayerId">
            <div class="rallyScoreWinner">
              {{ match.awayScoreTotal }}
            </div>
            <div class="eloInfo">
              {{ match.awayElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldAwayElo }} &rarr; {{ match.newAwayElo }}
            </div>
          </div>
          <div v-else>
            <div class="rallyScore">
              {{ match.awayScoreTotal }}
            </div>
            <div class="eloInfo">
              {{ match.awayElo }} ELO 
            </div>
            <div class="eloInfo">
              {{ match.oldAwayElo }} &rarr; {{ match.newAwayElo }}
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
      <div @click="toggleVisibility()" style="color: white; border-radius: 10px; padding: 10px; font-size: 20px; margin-bottom: 10px; background-color: #4a4a4a;" v-if="!match.isFinished">
        <span>ENTER RESULT</span>
      </div>
      <div v-if="startMessage" style="color: white; border-radius: 10px; padding: 10px; font-size: 20px; margin-bottom: 10px; background-color: #10880f;">
        {{ startMessage }}
      </div>
      <div class="midInfoHeader">MATCH MODE</div>
      <div class="midInfoValue">BO4</div>
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
      <div class="result-dialog" v-if="resultVisible">
        <table style="margin: 0 auto;">
          <tr>
            <td>
              <form class="mart10" v-bind:key="'form_' + match.matchId" method="post" @submit.prevent="postResults">
                <div>
                  <input type="hidden" name="matchId" :value=match.matchId />
                </div>
                <div style="font-size: 40px;">
                  <span class="padl20">{{ match.homePlayerName }}</span> -
                  <span>{{ match.awayPlayerName }}</span>
                </div>
                <div v-for="score in match.scores" v-bind:key="score.set" class="span-score">
                  <div style="margin-bottom: 20px;">
                    <input type="text" :name="'home_set_' + score.set" :value=score.home />
                    <input type="text" :name="'away_set_' + score.set" :value=score.away />
                  </div>
                </div>
                <div v-if="match.scores.length == 0">
                  <span v-for="i in range(1, match.maxSets)" v-bind:key="i" class="span-score">
                    <div style="margin-bottom: 20px;">
                      <input type="text" :name="'home_set_' + i" value="" />
                      <input type="text" :name="'away_set_' + i" value="" />
                    </div>
                  </span>
                </div>
                <div v-else-if="match.scores.length < match.maxSets">
                  <div v-for="i in range(match.maxSets - (match.maxSets - match.scores.length) +  1, match.maxSets)" v-bind:key="i" class="span-score">
                    <div style="margin-bottom: 20px;">
                      <input type="text" :name="'home_set_' + i" value="" />
                      <input type="text" :name="'away_set_' + i" value="" />
                    </div>
                  </div>
                </div>
                <div>
                  <input type="submit" value="save" class="submit-button" style="background-color: #10880f;" />
                  <input type="button" value="cancel" class="submit-button"  @click="toggleVisibility()" />
                </div>
              </form>
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
      <button v-gamepad:button-start="startGame">Press me!</button>
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
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueGamepad from 'vue-gamepad'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'
import io from 'socket.io-client'

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
      resultVisible: false,
      post2Channel: true,
      socket: io(window.location.hostname + ':3001'),
      broadcasted: false,
      startMessage: null
    }
  },
  mounted () {
    this.idle = false
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.match = res.data
      this.setScores = res.data.scores
      this.homeScore = res.data.currentHomePoints ? res.data.currentHomePoints : 0
      this.awayScore = res.data.currentAwayPoints ? res.data.currentAwayPoints : 0
      this.idle = true
      this.checkServer()
    })
  },
  methods: {
    sendMessage (data) {
      this.socket.emit('SEND_MESSAGE', {
        message: data
      })
    },
    toggleVisibility () {
      this.resultVisible = !this.resultVisible
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
      var setNumber = this.match.currentSet
      if (parseInt(this.match.currentSet) === 0) {
        setNumber = 1
      }
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
            this.checkServer()
            this.sendMessage(this.getPayload())
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
    startGame () {
      // allow only if we are in the idle state
      if (this.idle === false) {
        return false
      }
      if (this.broadcasted === true) {
        return false
      }
      this.startMessage = 'GAME STARTED'
      this.broadcasted = true

      // set idle state to 1, as we are sending request to change server
      this.idle = false
      axios.get('/api/matches/' + this.$route.params.id + '/broadcast').then((res) => {
        if (res.data) {
          this.idle = true
        }
        this.postSpectators()
      })
      this.sendMessage(this.getPayload())
    },
    postSpectators () {
      // add 0 spectators at the beginning of the match
      axios.post('/api/matches/add/spectator', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        gameId: this.$route.params.id,
        spectatorCount: 0,
        type: 0
      }).then((res) => {
        this.errors = []
        if (res.status === 200) {
          // added initial spectators
        }
        return true
      }).catch(error => {
        console.log(error)
        this.errors = []
      })
    },
    resetScores () {
      this.homeScore = 0
      this.awayScore = 0
    },
    addPointLeft () {
      if (!this.broadcasted) {
        this.startMessage = 'PRESS START ON GAMEPAD'
        return
      }
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
        this.sendMessage(this.getPayload())
      }
    },
    getPayload () {
      return {
        'score': {
          'homeScore': this.homeScore,
          'awayScore': this.awayScore
        },
        'setScores': this.match.scores,
        'currentSet': this.match.currentSet,
        'isFinished': this.match.isFinished,
        'homeScoreTotal': this.match.homeScoreTotal,
        'awayScoreTotal': this.match.awayScoreTotal,
        'startingServer': this.match.serverId,
        'serverFlipped': this.serverFlipped,
        'numServes': this.numServes
      }
    },
    addPointRight () {
      if (!this.broadcasted) {
        this.startMessage = 'PRESS START ON GAMEPAD'
        return
      }
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
        this.sendMessage(this.getPayload())
      }
    },
    /**
     * Substract points from player on the left side of the screen
     */
    subPointLeft () {
      if (!this.broadcasted) {
        this.startMessage = 'PRESS START ON GAMEPAD'
        return
      }
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
        this.sendMessage(this.getPayload())
      }
    },
    subPointRight () {
      if (!this.broadcasted) {
        this.startMessage = 'PRESS START ON GAMEPAD'
        return
      }
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
        this.sendMessage(this.getPayload())
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
.eloInfo {
  font-size: 20pt;
}

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
  top: 50px;
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
  width: 100px;
  text-align: center;
}

.span-score {
  min-width: 100px;
  margin-bottom: 20px;
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
  background-color: #000000ed;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
}
</style>
