<template>
  <div class="mainContainer">
    <div>
      <span data-v-729845b3="" class="fa-stack fa-2x" style="opacity: 0.5; margin-left: -60px;">
        <i data-v-729845b3="" class="fas fa-circle fa-stack-2x"></i> 
        <i data-v-729845b3="" class="fas fa-square fa-stack-2x" style="margin-left: 30px; margin-top: -3px; font-size: 53pt;"></i>
        <i data-v-729845b3="" class="fas fa-circle fa-stack-2x" style="margin-left: 59px;"></i>
      </span>
    </div>
    <div style="margin-top: -58px; font-size: 10pt; margin-left: -60px;">
      <span class="fa-stack fa-2x">
        <i class="fas fa-circle fa-stack-2x" style="color: #03a9f4;"></i>
        <i class="fas fa-eye fa-stack-1x fa-inverse"></i>
      </span>
    </div>
    <div style="margin-top: -54px; font-size: 10pt; margin-left: 50px; color: white;">
      <span class="fa-stack fa-2x">
        {{ spectators }}
      </span>
    </div>
    <div style="margin: 0 auto; width: 80%; clear: both; height: 350px;">
      <div class="container-fl">
        <div>
          <span class="fa-stack fa-2x">
            <i class="fas fa-walking" style="font-size: 300px; color: #105869; margin-left: -40px;"></i>
            <i class="fas fa-table-tennis fa-stack-1x fa-inverse" style="margin-top: -200px;margin-left: 120px;font-size: 60px;color: #105869;"></i>
          </span>
        </div>
      </div>
      <div class="container-fr">
        <div>
          <span class="fa-stack fa-2x">
            <i class="fas fa-walking fa-flip-horizontal" style="font-size: 300px; color: #105869; margin-left: -70px;"></i>
            <i class="fas fa-table-tennis fa-stack-1x fa-inverse fa-flip-horizontal" style="margin-top: -200px;margin-left: -120px;font-size: 60px;color: #105869;"></i>
          </span>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
    <div v-if="parseInt(isFinished) == 0" style="margin: 0 auto; width: 100%; clear: both;">
      <div class="container-score-fl">
        <div class="score-left">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x" style="color: white;"></i>
            <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ homeScore }}</i>
          </span>
        </div>
      </div>
      <div class="container-score-fr">
        <div class="score-right">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x" style="color: white;"></i>
            <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ awayScore }}</i>
          </span>
        </div>
      </div>      
      <div style="clear: both;"></div>
    </div>
    <div v-else style="margin: 0 auto; width: 100%; clear: both;">
      <div class="container-score-fl">
        <div class="score-left">
          <span class="fa-stack fa-2x">
            <i v-if="parseInt(awayScoreTotal) < parseInt(homeScoreTotal)" class="fas fa-circle fa-stack-2x" style="color: #40c500;"></i>
            <i v-else-if="parseInt(awayScoreTotal) === parseInt(homeScoreTotal)" class="fas fa-circle fa-stack-2x" style="color: #7f949a;"></i>
            <i v-else class="fas fa-circle fa-stack-2x" style="color: white;"></i>  
            <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ homeScoreTotal }}</i>
          </span>
        </div>
      </div>
      <div class="container-score-fr">
        <div class="score-right">
          <span class="fa-stack fa-2x">
            <i v-if="parseInt(awayScoreTotal) > parseInt(homeScoreTotal)" class="fas fa-circle fa-stack-2x" style="color: #40c500;"></i>
            <i v-else-if="parseInt(awayScoreTotal) === parseInt(homeScoreTotal)" class="fas fa-circle fa-stack-2x" style="color: #7f949a;"></i>            
            <i v-else class="fas fa-circle fa-stack-2x" style="color: white;"></i>          
            <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ awayScoreTotal }}</i>
          </span>
        </div>
      </div>      
      <div style="clear: both;"></div>
    </div>
    <div v-if="parseInt(isFinished) == 1" style="width: 100%;clear: both;position: absolute;margin-top: -325px;margin-left: -20px;font-size: 20pt;font-weight: 900;color: white;">
      FINAL MATCH SCORE
    </div>
    <div style="width: 100%;clear: both;position: absolute;margin-top: -80px;margin-left: -20px;font-size: 20pt;font-weight: 900;color: white;">
      <div style="background: rgb(16, 88, 105); padding: 10px; box-shadow: black 0px 0px 3px; width: 400px; margin: 0 auto; border-radius: 10px;">
        {{ match.groupName }}
      </div>
    </div>
    <div class="versusContainer">
      <div class="container-fl">
        <span class="header-title">{{ match.homePlayerDisplayName }}</span>
      </div>
      <div class="container-fr">
        <span class="header-title">{{ match.awayPlayerDisplayName }}</span>
      </div>
      <div class="container-mid">
        <div class="midInfoHeader">BEST OF 4</div>
        <div class="midInfoHeader">SET SCORES</div>
        <div class="midInfoValue">
          <div class="tableSetScores">
            <div class="set-container-fl">
              <div v-for="(score, index) in matchScores" v-bind:key="index" class="rowData">
                <span v-if="currentSet - 1 != index">
                  <span class="fa-stack" style="font-size: 30px;">
                    <i v-if="parseInt(score.home) > parseInt(score.away)" class="fas fa-circle fa-stack-2x" style="color: #40c500;"></i>
                    <i v-else class="fas fa-circle fa-stack-2x" style="color: white;"></i>
                    <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ score.home }}</i>
                  </span>
                </span>
              </div>
            </div>
            <div class="set-container-fr">
              <div v-for="(score, index) in matchScores" v-bind:key="index" class="rowData">
                <span v-if="currentSet - 1 != index">
                  <span class="fa-stack" style="font-size: 30px;">
                    <i v-if="parseInt(score.away) > parseInt(score.home)" class="fas fa-circle fa-stack-2x" style="color: #40c500;"></i>
                    <i v-else class="fas fa-circle fa-stack-2x" style="color: white;"></i>
                    <i class="fas fa-stack-1x" style="color: black; font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;">{{ score.away }}</i>
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
    <div style="clear: both;"></div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'
import io from 'socket.io-client'

Vue.use(VuejsDialog)

export default {
  components: {
    VuejsDialog
  },
  data () {
    return {
      match: [],
      homeScore: 0,
      awayScore: 0,
      endSet: 0,
      matchScores: [],
      serverFlipped: false,
      numServes: 2,
      idle: true,
      socket: io(window.location.hostname + ':3001'),
      currentSet: 0,
      isFinished: 0,
      spectators: 0,
      homeScoreTotal: 0,
      awayScoreTotal: 0
    }
  },
  mounted () {
    this.socket.on('MESSAGE', (data) => {
      if (this.isFinished === 0) {
        this.homeScore = data.message.score.homeScore
        this.awayScore = data.message.score.awayScore
        this.matchScores = data.message.setScores
        this.currentSet = data.message.currentSet
        this.isFinished = data.message.isFinished
        this.homeScoreTotal = data.message.homeScoreTotal
        this.awayScoreTotal = data.message.awayScoreTotal
      }
    })
    this.socket.on('CONNECTIONS', (data) => {
      console.log(data)
      this.spectators = data
    })
    this.idle = false
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.currentSet = res.data.currentSet
      this.match = res.data
      this.isFinished = res.data.isFinished
      this.matchScores = res.data.scores
      this.homeScore = res.data.currentHomePoints ? res.data.currentHomePoints : 0
      this.awayScore = res.data.currentAwayPoints ? res.data.currentAwayPoints : 0
      this.idle = true
      this.homeScoreTotal = res.data.homeScoreTotal
      this.awayScoreTotal = res.data.awayScoreTotal
    })
  },
  methods: {
    range: function (min, max) {
      var array = []
      var j = 0
      for (var i = min; i <= max; i++) {
        array[j] = i
        j++
      }
      return array
    },
    resetScores () {
      this.homeScore = 0
      this.awayScore = 0
    }
  }
}
</script>

<style lang="less" scoped>
.score-left {
  text-align: right;
  font-size: 2.5em;
  margin-top: -280px;
}

.score-right {
  text-align: left;
  font-size: 2.5em;
  margin-top: -280px;
}

.mainContainer {
  margin-top: 20px;
  text-align: center;
}

.versusContainer {
  background: #0e3c46;
  margin-top: 50px;
  padding: 20px;
  width: 80%;
  margin: 0 auto;
}

.eloInfo {
  font-size: 20pt;
}

.rowData {
  border: none;
  font-size: 60px;
  color: #f5f5f5;
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

.container-score-fl {
  float: left;
  width: 50%;
  text-align: center;
  border-right: 1px solid #222;
}

.container-score-fr {
  float: right;
  width: 50%;
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
    font-size: 20px;
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
  font-size: 40px;
  color: #40c500;
}

.colWinner {
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
