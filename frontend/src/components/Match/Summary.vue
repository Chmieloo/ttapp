<template>
  <div class="mainContainer">
    <span class="header-title">
      MATCH SUMMARY
    </span>
    <div v-for="(setData, index) in setsData" v-bind:key="index">
      <div class="setContainer">
        <table style="border-collapse: collapse;">
          <tr>
            <td rowspan="2" class="setPointsScored">
              {{ setData.scores.homePoints }}
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" style="width: 40px;">
              <span v-if="pointData.currentServer == pointData.homePlayerId" class="serve">
                <i class="fas fa-table-tennis"></i>
              </span>
            </td>            
          </tr>
          <tr>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" class="pointsScored">
              <span>
                {{ pointData.homePointsScored }}
              </span>
            </td>            
          </tr>
          <tr style="height: 40px; line-height: 40px;">
            <td class="playerName">
              <span v-if="setData.scores.homePoints > setData.scores.awayPoints" class="winnerColor">
                {{ homeName }}
              </span>
              <span v-else>
                {{ homeName }}
              </span>
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" style="width: 40px;">
              <span v-if="pointData.isHomePoint" class="point">
                <i class="fas fa-circle"></i>
              </span>
              <span v-else>
                <i class="far fa-circle"></i>
              </span>
            </td>
          </tr>
          <tr style="height: 50px; line-height: 50px;">
            <td class="playerName">
              <span v-if="setData.scores.awayPoints > setData.scores.homePoints" class="winnerColor">
                {{ awayName }}
              </span>
              <span v-else>
                {{ awayName }}
              </span>
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2">
              <span v-if="pointData.isAwayPoint" class="point">
                <i class="fas fa-circle"></i>
              </span>
              <span v-else>
                <i class="far fa-circle"></i>
              </span>
            </td>
          </tr>
          <tr>
            <td rowspan="2" class="setPointsScored">
              {{ setData.scores.awayPoints }}
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" class="pointsScored">
              <span>
                {{ pointData.awayPointsScored }}
              </span>
            </td>            
          </tr>
          <tr>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" style="width: 40px;">
              <span v-if="pointData.currentServer == pointData.awayPlayerId" class="serve">
                <i class="fas fa-table-tennis"></i>
              </span>
            </td>            
          </tr>
        </table>
        <div class="summaryFacts">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      setsData: [],
      homeName: null,
      awayName: null,
      match: [],
      homeScore: 0,
      awayScore: 0,
      endSet: 0,
      matchScores: [],
      serverFlipped: false,
      numServes: 2,
      idle: true,
      currentSet: 0,
      isFinished: 0,
      spectators: 0,
      homeScoreTotal: 0,
      awayScoreTotal: 0
    }
  },
  mounted () {
    this.idle = false
    axios.get('/api/matches/' + this.$route.params.id + '/timeline').then((res) => {
      this.setsData = res.data.setsData
      this.homeName = res.data.homeName
      this.awayName = res.data.awayName
      /*
      this.currentSet = res.data.currentSet
      this.match = res.data
      this.isFinished = res.data.isFinished
      this.matchScores = res.data.scores
      this.homeScore = res.data.currentHomePoints ? res.data.currentHomePoints : 0
      this.awayScore = res.data.currentAwayPoints ? res.data.currentAwayPoints : 0
      this.idle = true
      this.homeScoreTotal = res.data.homeScoreTotal
      this.awayScoreTotal = res.data.awayScoreTotal
      */
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
table {
  td {
    border-right: 1px solid #585858;
  }
}

.summaryFacts {
  margin-top: 10px;
  background-color: #105869;
  padding: 10px;
}

.point {
  color: white;
  font-size: 20pt;
}

.setPointsScored {
  text-align: left;
  font-size: 20pt;
  font-weight: 600;
}

.serve {
  color: #636363;
  font-size: 16pt;
}

.pointsScored {
  color: white;
  font-size: 12pt;
}

.mainContainer {
  margin-top: 20px;
  text-align: center;
}

.setContainer {
  background: #0e3c46;
  padding: 20px;
  width: 90%;
  margin: 50px auto;
}

.playerName {
  text-align: left;
  font-size: 18pt;
  color: white;
  min-width: 250px;
}

.winnerColor {
  color: #40c500;
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
