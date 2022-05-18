<template>
  <div class="mainContainer" :key="componentKey" v-if="this.match">
    <div class="matchInfo">MATCH MODE: {{ match.modeName }}</div>
    <div v-bind:class="flipped ? 'container-fr' : 'container-fl'">
      <div>
        <div>
          <span v-if="!match.isFinished">
            <a
              @click="walkover(match.homePlayerId, match.homePlayerDisplayName)"
            >
              <i class="fas fa-trophy" style="color: #313131"></i>
            </a>
          </span>
        </div>
        <span class="header-title">
          {{ match.homePlayerDisplayName }}
        </span>
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
        <div>
          <span v-if="!match.isFinished">
            <a
              @click="walkover(match.awayPlayerId, match.awayPlayerDisplayName)"
            >
              <i class="fas fa-trophy" style="color: #313131"></i>
            </a>
          </span>
        </div>
        <span class="header-title">
          {{ match.awayPlayerDisplayName }}
        </span>
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
      <div
        v-if="startMessage"
        style="
          color: white;
          border-radius: 10px;
          padding: 10px;
          font-size: 20px;
          margin-bottom: 10px;
          background-color: #10880f;
        "
      >
        {{ startMessage }}
      </div>
      <div
        v-if="match.isFinished"
        style="
          color: white;
          border-radius: 10px;
          padding: 10px;
          font-size: 20px;
          margin-bottom: 10px;
          background-color: #10880f;
        "
      >
        <router-link :to="'/playoffs/' + match.tournamentId + '/info'"
          >BACK TO SCHEDULE
        </router-link>
      </div>
      <div class="midInfoHeader">SET SCORES</div>
      <div class="midInfoValue">
        <div class="tableSetScores">
          <div v-bind:class="flipped ? 'set-container-fr' : 'set-container-fl'">
            <div
              v-for="(score, index) in match.scores"
              v-bind:key="index"
              class="rowData"
            >
              <span v-if="match.currentSet - 1 != index">
                <span class="fa-stack" style="font-size: 30px">
                  <i
                    v-if="parseInt(score.home) > parseInt(score.away)"
                    class="fas fa-circle fa-stack-2x"
                    style="color: #40c500"
                  ></i>
                  <i
                    v-else
                    class="fas fa-circle fa-stack-2x"
                    style="color: white"
                  ></i>
                  <i
                    class="fas fa-stack-1x"
                    style="
                      color: black;
                      font-family: 'Poppins', 'Avenir', Helvetica, Arial,
                        sans-serif;
                    "
                    >{{ score.home }}</i
                  >
                </span>
              </span>
            </div>
          </div>
          <div v-bind:class="flipped ? 'set-container-fl' : 'set-container-fr'">
            <div
              v-for="(score, index) in match.scores"
              v-bind:key="index"
              class="rowData"
            >
              <span v-if="match.currentSet - 1 != index">
                <span class="fa-stack" style="font-size: 30px">
                  <i
                    v-if="parseInt(score.away) > parseInt(score.home)"
                    class="fas fa-circle fa-stack-2x"
                    style="color: #40c500"
                  ></i>
                  <i
                    v-else
                    class="fas fa-circle fa-stack-2x"
                    style="color: white"
                  ></i>
                  <i
                    class="fas fa-stack-1x"
                    style="
                      color: black;
                      font-family: 'Poppins', 'Avenir', Helvetica, Arial,
                        sans-serif;
                    "
                    >{{ score.away }}</i
                  >
                </span>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="result-dialog" v-if="warmupVisible">
        <div class="warmupTable">
          <div
            class="wtTitle"
            style="
              color: white;
              padding-top: 50px;
              font-family: Alata, 'sans-serif';
              font-size: 64px;
              font-weight: 600;
            "
          >
            Sportradar Table Tennis Playoffs
          </div>
          <div style="font-size: 30px">
            {{ match.matchName }} / {{ match.groupName }} / {{ match.modeName }}
          </div>
          <div style="font-size: 30px; color: #97724e; padding-top: 20px">
            WARM-UP
          </div>
          <div class="wtClock">{{ this.clock }}</div>
          <div>
            <table style="width: 90%; margin: 0 auto; padding-top: 50px">
              <tr>
                <td
                  style="
                    width: 50%;
                    font-size: 45px;
                    color: #97724e;
                    font-weight: 600;
                  "
                >
                  <div style="margin-bottom: 10px">
                    {{ match.homePlayerDisplayName }}
                  </div>
                  <div class="cutoutPic">
                    <img :src="match.homePlayerPic" class="pic" />
                  </div>
                </td>
                <td
                  style="
                    width: 50%;
                    font-size: 45px;
                    color: #97724e;
                    font-weight: 600;
                  "
                >
                  <div style="margin-bottom: 10px">
                    {{ match.awayPlayerDisplayName }}
                  </div>
                  <div class="cutoutPic">
                    <img :src="match.awayPlayerPic" class="pic" />
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-shadow"></div>
          </div>
        </div>
        <table class="warmupTable1" v-if="this.match">
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
                {{ match.matchName }} / {{ match.groupName }} /
                {{ match.modeName }}
              </div>
              <div class="label-players">
                <span>{{ match.homePlayerDisplayName }}</span>
                <span style="color: #6f6f6f">vs</span>
                <span>{{ match.awayPlayerDisplayName }}</span>
              </div>
              <div v-if="match.nextMatchId">
                <div class="label-match-name-next" style="margin-top: 30px">
                  next: {{ match.nextMatchName }}
                </div>
                <div class="label-players-next">
                  <span>{{ match.nextMatchHomePlayer }}</span>
                  <span style="color: #6f6f6f">vs</span>
                  <span>{{ match.nextMatchAwayPlayer }}</span>
                </div>
              </div>
              <div>
                <ul class="circles">
                  <li>playoffs</li>
                  <li>table tennis</li>
                </ul>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="loader-container">
                <div class="loader"></div>
                <div class="loader-shadow"></div>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <div
        v-show="endSet"
        class="endSet"
        v-gamepad:shoulder-right="finalizeSet"
      >
        <div>Press [R] to confirm set score.</div>
        <div><img src="./assets/padr.png" /></div>
      </div>
    </div>
    <div style="clear: both"></div>
    <div style="display: none">
      <button v-gamepad:button-y="addPointLeft">Press me!</button>
      <button v-gamepad:button-a="addPointRight">Press me!</button>
      <button v-gamepad:button-x="subPointLeft">Press me!</button>
      <button v-gamepad:button-b="subPointRight">Press me!</button>
      <button v-gamepad:shoulder-left="flipSides">Press me!</button>
      <button v-gamepad:button-select="setServer">Press me!</button>
      <button v-gamepad:button-start="startGame">Press me!</button>
      <button v-gamepad:left-analog-down="toggleVisibility">Press me!</button>
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
import axios from "axios";
import "vuejs-dialog/dist/vuejs-dialog.min.css";
import io from "socket.io-client";
import VueGamepad from "vue-gamepad";
import VuejsDialog from "vuejs-dialog";

export default {
  components: {
    VueGamepad,
    VuejsDialog,
  },
  data() {
    return {
      componentKey: 0,
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
      clockInterval: null,
      clockPaused: false,
      clockRemaining: 0,
      broadcasted: 0,
      currentServerId: null,
      currentNumServes: 2,
      startMessage: null,
    };
  },
  mounted() {
    this.idle = false;
    axios.get("/api/matches/" + this.$route.params.id).then((res) => {
      this.match = res.data;
      this.currentServerId = res.data.currentServerId;
      this.currentNumServes = res.data.currentNumServes;
      this.homeScore = res.data.currentHomePoints
        ? res.data.currentHomePoints
        : 0;
      this.awayScore = res.data.currentAwayPoints
        ? res.data.currentAwayPoints
        : 0;
      this.idle = true;
      this.checkServer();
      this.socket = io(
        window.location.hostname + ":3001?game_id=" + this.match.matchId
      );
    });
    this.setupClock();
  },
  methods: {
    sendMessage(data) {
      this.socket.emit("SEND_MESSAGE", {
        message: data,
      });
    },
    walkover(playerId, playerName) {
      if (confirm("Are you sure player " + playerName + " wins by walkover?")) {
        axios
          .get(
            "/api/matches/" + this.$route.params.id + "/walkover/" + playerId
          )
          .then((res) => {
            this.match = res.data;
          });
      }
    },
    setupClock() {
      this.warmupSeconds = 120;
      var currentTime = Date.parse(new Date());
      this.warmupDeadline = new Date(
        currentTime + (this.warmupSeconds / 60) * 60 * 1000
      );
      this.clockInterval = setInterval(this.runClock, 1000);
    },
    runClock() {
      var t = this.timeRemaining(this.warmupDeadline);
      this.clock = t.minutes + ":" + t.seconds;
      if (t.total <= 0) {
        clearInterval(this.clockInterval);
        this.warmupVisible = false;
        // if (this.broadcasted === 0) {
        // this.postMatchStart()
        // this.broadcasted = 1
        // }
      }
    },
    startGame() {
      if (!this.warmupVisible && !this.broadcasted) {
        this.startMessage = "GAME STARTED";
        this.broadcasted = true;
        this.postMatchStart();
      }
    },
    timeRemaining(endtime) {
      var t = Date.parse(endtime) - Date.parse(new Date());
      var seconds = Math.floor((t / 1000) % 60);
      var minutes = Math.floor((t / 1000 / 60) % 60);
      if (minutes < 10) {
        minutes = "0" + minutes;
      }
      if (seconds < 10) {
        seconds = "0" + seconds;
      }
      return { total: t, minutes: minutes, seconds: seconds };
    },
    toggleWarmupTimer() {
      if (this.clockPaused === false) {
        var t = this.timeRemaining(this.warmupDeadline);
        this.clockInterval = clearInterval(this.clockInterval);
        this.clockPaused = true;
        this.clockRemaining = t.total / 1000;
        this.clock = t.minutes + ":" + t.seconds + " [paused]";
      } else {
        var currentTime = Date.parse(new Date());
        this.warmupDeadline = new Date(
          currentTime + (this.clockRemaining / 60) * 60 * 1000
        );
        this.clock =
          this.timeRemaining(this.warmupDeadline).minutes +
          ":" +
          this.timeRemaining(this.warmupDeadline).seconds;
        this.clockInterval = setInterval(this.runClock, 1000);
        this.clockPaused = false;
      }
    },
    postMatchStart() {
      axios
        .get("/api/matches/" + this.$route.params.id + "/startmessage")
        .then((res) => {
          if (res.data) {
            this.postSpectators();
          }
        });
    },
    getPayload() {
      return {
        score: {
          homeScore: this.homeScore,
          awayScore: this.awayScore,
        },
        setScores: this.match.scores,
        currentSet: this.match.currentSet,
        isFinished: this.match.isFinished,
        homeScoreTotal: this.match.homeScoreTotal,
        awayScoreTotal: this.match.awayScoreTotal,
        startingServer: this.match.serverId,
        serverFlipped: this.serverFlipped,
        currentNumServes: this.currentNumServes,
        currentServerId: this.currentServerId,
      };
    },
    toggleVisibility() {
      if (!this.broadcasted) {
        this.warmupVisible = !this.warmupVisible;
      }
    },
    postResults(event) {
      axios
        .post("/api/matches/save", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          matchId: event.target.elements.matchId.value,
          post2Channel: this.post2Channel,
          h1:
            typeof event.target.elements.home_set_1 === "undefined"
              ? ""
              : event.target.elements.home_set_1.value,
          h2:
            typeof event.target.elements.home_set_2 === "undefined"
              ? ""
              : event.target.elements.home_set_2.value,
          h3:
            typeof event.target.elements.home_set_3 === "undefined"
              ? ""
              : event.target.elements.home_set_3.value,
          h4:
            typeof event.target.elements.home_set_4 === "undefined"
              ? ""
              : event.target.elements.home_set_4.value,
          a1:
            typeof event.target.elements.away_set_1 === "undefined"
              ? ""
              : event.target.elements.away_set_1.value,
          a2:
            typeof event.target.elements.away_set_2 === "undefined"
              ? ""
              : event.target.elements.away_set_2.value,
          a3:
            typeof event.target.elements.away_set_3 === "undefined"
              ? ""
              : event.target.elements.away_set_3.value,
          a4:
            typeof event.target.elements.away_set_4 === "undefined"
              ? ""
              : event.target.elements.away_set_4.value,
        })
        .then((res) => {
          this.errors = [];
          console.log(res.data);
          if (res.status === 200) {
            // self.$router.push({ name: 'MatchView', params: { id: this.match.matchId } })
            // TODO for now - fix that to use router
            document.location.href = "/";
          }
          return true;
        })
        .catch((error) => {
          console.log(error);
          this.errors = [];
        });
    },
    range: function (min, max) {
      var array = [];
      var j = 0;
      for (var i = min; i <= max; i++) {
        array[j] = i;
        j++;
      }
      return array;
    },
    checkServer() {
      console.log("Checking server.");
      console.log("Current set: " + this.match.currentSet);
      var setNumber = this.match.currentSet;
      if (this.homeScore === 0 && this.awayScore === 0) {
        this.numServes = 2;

        if (this.match.serverId === this.match.homePlayerId) {
          this.serverFlipped =
            (parseInt(setNumber) + 1) % 2 === 0 ? this.flipped : !this.flipped;
        } else {
          this.serverFlipped =
            (parseInt(setNumber) + 1) % 2 === 0 ? !this.flipped : this.flipped;
        }
      }
      var totalScore = this.homeScore + this.awayScore;
      if (totalScore < 20) {
        this.numServes = 2 - (totalScore % 2);

        var mod4 = totalScore % 4;
        if (mod4 === 1) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          }
        } else if (mod4 === 2) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          }
        } else if (mod4 === 3) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          }
        } else if (mod4 === 0) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          }
        }
      } else {
        this.numServes = 1;
        var mod2 = totalScore % 2;
        if (mod2 === 1) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          }
        } else if (mod2 === 0) {
          if (this.match.serverId === this.match.homePlayerId) {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? this.flipped
                : !this.flipped;
          } else {
            this.serverFlipped =
              (parseInt(setNumber) + 1) % 2 === 0
                ? !this.flipped
                : this.flipped;
          }
        }
      }
    },
    // logic to check if set is finished
    isFinishedSet() {
      if (this.homeScore >= 11 || this.awayScore >= 11) {
        if (Math.abs(this.homeScore - this.awayScore) < 2) {
          return false;
        } else {
          return this.confirmFinalScore();
        }
      } else {
        return false;
      }
    },
    checkFinalScore() {
      if (this.homeScore >= 11 || this.awayScore >= 11) {
        if (Math.abs(this.homeScore - this.awayScore) < 2) {
          this.endSet = 0;
        } else {
          this.endSet = 1;
        }
      } else {
        this.endSet = 0;
      }
    },
    finalizeSet() {
      if (this.endSet && this.idle) {
        this.idle = false;
        axios
          .get("/api/matches/" + this.$route.params.id + "/finish")
          .then((res) => {
            if (res.data) {
              this.endSet = 0;
              this.flipSides();
              this.resetScores();
              this.match = res.data;
              this.idle = true;
              this.numServes = 2;
              this.currentNumServes = res.data.currentNumServes;
              this.currentServerId = res.data.currentServerId;
              this.checkServer();
              this.sendMessage(this.getPayload());
            }
          });
        /*
        axios.get('/api/matches/' + this.$route.params.id + '/finish').then((res) => {
          if (res.data) {
            if (res.data.isFinished === 1) {
              this.endSet = 0
              this.resetScores()
              this.match = res.data
              this.idle = true
              this.numServes = 2
            } else {
              this.endSet = 0
              this.flipSides()
              this.resetScores()
              this.match = res.data
              this.idle = true
              this.numServes = 2
              this.checkServer()
            }
            this.sendMessage(this.getPayload())
          }
        })
        */
      }
    },
    flipSides() {
      this.flipped = !!((this.flipped + 1) % 2);
      this.checkServer();
    },
    flipServer() {
      this.serverFlipped = !!((this.serverFlipped + 1) % 2);
    },
    setServer() {
      // allow only if we are in the idle state
      if (this.idle === false) {
        return false;
      }
      // set idle state to 1, as we are sending request to change server
      this.idle = false;
      // change INITIAL server, by default it is home
      axios
        .get("/api/matches/" + this.$route.params.id + "/server")
        .then((res) => {
          if (res.data) {
            this.flipServer();
            this.match = res.data;
            this.checkServer();
            this.idle = true;
          }
        });
    },
    resetScores() {
      this.homeScore = 0;
      this.awayScore = 0;
    },
    postSpectators() {
      // add 0 spectators at the beginning of the match
      axios
        .post("/api/matches/add/spectator", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          gameId: this.$route.params.id,
          spectatorCount: 0,
          type: 0,
        })
        .then((res) => {
          this.errors = [];
          if (res.status === 200) {
            // added initial spectators
          }
          return true;
        })
        .catch((error) => {
          console.log(error);
          this.errors = [];
        });
    },
    addPointLeft() {
      if (!this.broadcasted) {
        this.startMessage = "PRESS START ON GAMEPAD";
        return;
      }
      if (!this.endSet && this.idle) {
        if (this.flipped) {
          this.awayScore++;
          this.savePoint(0, 1, this.match.matchId);
        } else {
          this.homeScore++;
          this.savePoint(1, 0, this.match.matchId);
        }
        this.checkFinalScore();
        this.checkServer();
        this.sendMessage(this.getPayload());
      }
    },
    addPointRight() {
      if (!this.broadcasted) {
        this.startMessage = "PRESS START ON GAMEPAD";
        return;
      }
      if (!this.endSet && this.idle) {
        if (this.flipped) {
          this.homeScore++;
          this.savePoint(1, 0, this.match.matchId);
        } else {
          this.awayScore++;
          this.savePoint(0, 1, this.match.matchId);
        }
        this.checkFinalScore();
        this.checkServer();
        this.sendMessage(this.getPayload());
      }
    },
    /**
     * Substract points from player on the left side of the screen
     */
    subPointLeft() {
      if (!this.broadcasted) {
        this.startMessage = "PRESS START ON GAMEPAD";
        return;
      }
      if (this.idle) {
        if (this.flipped) {
          if (this.awayScore > 0) {
            this.awayScore =
              this.awayScore - 1 < 0 ? this.awayScore : --this.awayScore;
            this.delPoint(0, 1, this.match.matchId);
          }
        } else {
          if (this.homeScore > 0) {
            this.homeScore =
              this.homeScore - 1 < 0 ? this.homeScore : --this.homeScore;
            this.delPoint(1, 0, this.match.matchId);
          }
        }
        this.checkFinalScore();
        this.checkServer();
        this.sendMessage(this.getPayload());
      }
    },
    subPointRight() {
      if (!this.broadcasted) {
        this.startMessage = "PRESS START ON GAMEPAD";
        return;
      }
      if (this.idle) {
        if (this.flipped) {
          if (this.homeScore > 0) {
            this.homeScore =
              this.homeScore - 1 < 0 ? this.homeScore : --this.homeScore;
            this.delPoint(1, 0, this.match.matchId);
          }
        } else {
          if (this.awayScore > 0) {
            this.awayScore =
              this.awayScore - 1 < 0 ? this.awayScore : --this.awayScore;
            this.delPoint(0, 1, this.match.matchId);
          }
        }
        this.checkFinalScore();
        this.checkServer();
        this.sendMessage(this.getPayload());
      }
    },
    isConnected() {
      var body = document.getElementsByTagName("body");
      return body[0].classList.contains("gamepad-connected");
    },
    savePoint(homeScore, awayScore, matchId) {
      // allow only when idle
      if (this.idle === false) {
        return false;
      }
      // set state
      this.idle = false;
      axios
        .post("/api/points/add", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          home: homeScore,
          away: awayScore,
          matchId: matchId,
        })
        .then((res) => {
          console.log("response status: " + res.status + ", " + res.data.text);
          this.match.currentSet = res.data.currentSet;
          this.currentServerId = res.data.currentServerId;
          this.currentNumServes = res.data.currentNumServes;
          this.idle = true;
          this.sendMessage(this.getPayload());
        })
        .catch((error) => {
          this.idle = true;
          console.log("error while adding point: " + error.response);
        });
    },
    delPoint(homeScore, awayScore, matchId) {
      // allow only when idle
      if (this.idle === false) {
        return false;
      }
      // set state
      this.idle = false;
      axios
        .post("/api/points/del", {
          headers: {
            "Content-type": "application/x-www-form-urlencoded",
          },
          home: homeScore,
          away: awayScore,
          matchId: matchId,
        })
        .then((res) => {
          // TODO log
          this.idle = true;
          this.currentServerId = res.data.currentServerId;
          this.currentNumServes = res.data.currentNumServes;
          this.sendMessage(this.getPayload());
        })
        .catch((error) => {
          // TODO log
          this.idle = true;
          console.log(error.response);
        });
    },
  },
};
</script>

<style lang="less" scoped>
.mainContainer {
  overflow: hidden;
  font-family: "Poppins", "Avenir", Helvetica, Arial, sans-serif;
}

.rowData {
  border: none;
  font-size: 60px;
  color: #f5f5f5;
}

.pic {
  margin: 0 auto;
  width: 150px;
  margin-top: -20px;
}

div.cutoutPic {
  width: 160px;
  height: 154px;
  position: relative;
  overflow: hidden;
  margin: 0 auto;
}

div.cutoutPic:before {
  content: "";
  position: absolute;
  bottom: 0;
  width: 146px;
  height: 146px;
  border-radius: 100%;
  border: 4px solid white;
  border-radius: 100%;
  box-shadow: 0px 200px 0px 300px #1a303d;
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
  overflow: hidden;
}

.warmupTable {
  background-color: #1a303d;
  width: 100%;
  height: 100%;
  font-family: Alata, "sans-serif";
}

.warmupTable1 {
  visibility: hidden;
  border: none;
  margin: 0;
  width: 100%;
  height: 100%;
  border-collapse: collapse;

  .left {
    background-color: #0e0e11;
    width: 30%;
    font-family: "Biryani", sans-serif;
    font-weight: 800;

    .label-sr {
      font-size: 50px;
    }

    .label-tt {
      font-size: 30px;
      font-weight: 100;
      font-family: "Biryani", sans-serif;
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
    font-family: "Biryani", sans-serif;
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
  0% {
    transform: translateX(0) rotate(0deg);
    opacity: 0.05;
  }
  100% {
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

@width: 40px;
@height: 40px;
@color: #fff;
@bounce-height: 60px;
@bounce-width: 3; // float, suggested 0-3
@bounce-duration: 1s;
@shadow-strength: 0.5;

.loader {
  width: @width;
  height: @height;
  background-color: @color;
  border-radius: @width + @height;
  position: absolute;
  animation: bounce @bounce-duration linear infinite,
    move @bounce-duration * 6 linear infinite @bounce-duration / 2.5;
}

.loader-shadow {
  width: @width / 2;
  height: 0;
  margin-left: (@width / 4);
  background-color: rgba(0, 0, 0, 0.2);
  box-shadow: 0 0 (@width / 10) 1px rgba(0, 0, 0, @shadow-strength);
  border-radius: @width @height;
  position: absolute;
  animation: move @bounce-duration * 6 linear infinite @bounce-duration / 2.5;
}

@keyframes bounce {
  0% {
    bottom: 0;
    animation-timing-function: ease-out;
  }
  50% {
    bottom: @bounce-height;
    animation-timing-function: ease-in;
  }
  100% {
    bottom: 0;
  }
}

@keyframes move {
  0% {
    transform: translateX(@width * @bounce-width * -1);
  }
  16.666% {
    transform: translateX(@width * @bounce-width * -0.3333);
  }
  33.3333% {
    transform: translateX(@width * @bounce-width * 0.3333);
  }
  50% {
    transform: translateX(@width * @bounce-width * 1);
  }
  66.6666% {
    transform: translateX(@width * @bounce-width * 0.3333);
  }
  83.333% {
    transform: translateX(@width * @bounce-width * -0.3333);
  }
  100% {
    transform: translateX(@width * @bounce-width * -1);
  }
}

.loader-container {
  bottom: 40%;
  left: 50%;
  position: absolute;
}

.wtClock {
  font-size: 50px;
  font-family: Montserrat;
  font-weight: 300;
}
</style>
