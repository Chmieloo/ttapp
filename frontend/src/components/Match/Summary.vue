<template>
  <div class="mainContainer">
    <div>
      <span class="header-title">
        MATCH SUMMARY
      </span>
    </div>
    <div>
      <span class="header-title-players">
        {{ homeName }} <span style="color: #8a8a8a;">vs</span> {{ awayName }}
      </span>
    </div>
    <div>
      <span class="header-title-group">
        {{ tournamentName }}, {{ groupName }}
      </span>
    </div>
    <div class="setContainer">
      <table style="border-collapse: collapse; color: white;">
        <tr style="height: 40px; line-height: 40px;">
          <td></td>
          <td style="min-width: 100px; color: white; font-weight: 600;">SCORE</td>
          <td style="min-width: 100px; color: #8e8e8e; font-weight: 600;" v-for="(setData, index) in setsData" v-bind:key="index">     
            SET {{ index }}
          </td>           
          <td style="min-width: 150px; color: white; font-weight: 600;">
            POINTS
          </td>
          <td style="min-width: 250px; color: white; font-weight: 600;">SERVICE POINTS / EFFICIENCY</td>
        </tr>
        <tr style="height: 40px; line-height: 40px;">
          <td class="playerName">
            <span v-if="parseInt(matchData.homeTotalScore) > parseInt(matchData.awayTotalScore)" class="winnerColor">
              {{ homeName }}
            </span>
            <span v-else>
              {{ homeName }}
            </span>
          </td>  
          <td style="font-weight: 600;">
            {{ matchData.homeTotalScore }}
          </td>  
          <td style="min-width: 100px;" v-for="(setData, index) in setsData" v-bind:key="index">     
            {{ setData.setSummary.homePoints }}
          </td> 
          <td>     
            {{ matchData.homeTotalPoints }} <span style="color: #8e8e8e;">( {{ matchData.homePointsPerc }}% )</span>
          </td> 
          <td>     
            {{ matchData.homeOwnServePointsTotal }} / {{ matchData.homeServesTotal }} <span style="color: #8e8e8e;">( {{ matchData.homeServePointsPerc }}% )</span>
          </td> 
        </tr>
        <tr style="height: 40px; line-height: 40px;">
          <td class="playerName">
            <span v-if="parseInt(matchData.homeTotalScore) < parseInt(matchData.awayTotalScore)" class="winnerColor">
              {{ awayName }}
            </span>
            <span v-else>
              {{ awayName }}
            </span>
          </td>     
          <td style="font-weight: 600;">
            {{ matchData.awayTotalScore }}
          </td>  
          <td style="min-width: 100px;" v-for="(setData, index) in setsData" v-bind:key="index">     
            {{ setData.setSummary.awayPoints }}
          </td>
          <td style="min-width: 100px;">     
            {{ matchData.awayTotalPoints }} <span style="color: #8e8e8e;">( {{ matchData.awayPointsPerc }}% )</span>
          </td>     
          <td>     
            {{ matchData.awayOwnServePointsTotal }} / {{ matchData.awayServesTotal }} <span style="color: #8e8e8e;">( {{ matchData.awayServePointsPerc }}% )</span>
          </td>    
        </tr>
      </table>
    </div>
    <div>
      <span class="header-title">
        SETS SUMMARY
      </span>
    </div>
    <div v-for="(setData, index) in setsData" v-bind:key="index">
      <div class="setContainer">
        <table style="border-collapse: collapse;">
          <tr>
            <td rowspan="2" class="setPointsScored">
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
              <span v-if="parseInt(setData.setSummary.homePoints) > parseInt(setData.setSummary.awayPoints)" class="winnerColor">
                {{ homeName }}
              </span>
              <span v-else>
                {{ homeName }}
              </span>
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2" style="width: 40px;">
              <span v-if="index2 === 0">
                <span v-if="pointData.isHomePoint" class="point">
                  <i class="fas fa-circle"></i>
                </span>
                <span v-else>
                  <i class="far fa-circle"></i>
                </span>                
              </span>
              <span v-else>
                <span v-if="pointData.isHomePoint" class="point tool" v-bind:data-tip="'Rally length: ' + pointData.rallySeconds + ' seconds'">
                  <i class="fas fa-circle"></i>
                </span>
                <span v-else>
                  <i class="far fa-circle"></i>
                </span>
              </span>
            </td>
            <td class="lastPointsResult">
              {{ setData.setSummary.homePoints }}
            </td>
          </tr>
          <tr style="height: 50px; line-height: 50px;">
            <td class="playerName">
              <span v-if="parseInt(setData.setSummary.homePoints) < parseInt(setData.setSummary.awayPoints)" class="winnerColor">
                {{ awayName }}
              </span>
              <span v-else>
                {{ awayName }}
              </span>
            </td>
            <td v-for="(pointData, index2) in setData.events" v-bind:key="index2">
              <span v-if="index2 === 0">
                <span v-if="pointData.isAwayPoint" class="point">
                  <i class="fas fa-circle"></i>
                </span>
                <span v-else>
                  <i class="far fa-circle"></i>
                </span>                
              </span>
              <span v-else>
                <span v-if="pointData.isAwayPoint" class="point tool" v-bind:data-tip="'Rally length: ' + pointData.rallySeconds + ' seconds'">
                  <i class="fas fa-circle"></i>
                </span>
                <span v-else>
                  <i class="far fa-circle"></i>
                </span>
              </span>
            </td>
            <td class="lastPointsResult">
              {{ setData.setSummary.awayPoints }}
            </td>
          </tr>
          <tr>
            <td rowspan="2" class="setPointsScored">
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
          <table>
            <tr>
              <td class="noborder txtc"></td>
              <td class="noborder txtc" style="color: #9a9a9a;">duration</td>
              <td class="noborder txtc"></td>
              <td class="noborder txtc" style="padding: 0px 20px; color: #9a9a9a;">serve pts / serves / efficiency</td>
              <td class="noborder txtc" style="padding: 0px 20px; color: #9a9a9a;">max points in a row</td>
            </tr>
            <tr>
              <td style="padding: 0px 20px; font-size: 20pt; border-right: 1px solid #02252e;">
                Set {{ index }}
              </td>
              <td style="padding: 0px 20px; font-size: 20pt; border-right: 1px solid #02252e;">
                {{ setData.setSummary.durationMinutes }}:{{ setData.setSummary.durationSeconds }}
              </td>
              <td style="padding: 0px 20px; border-right: 1px solid #02252e;">
                <div>{{ homeName }}</div>
                <div>{{ awayName }}</div>
              </td>
              <td style="padding: 0px 20px; border-right: 1px solid #02252e; text-align: center;">
                <div>
                  {{ setData.setSummary.homeServePoints }} <span style="color: #9a9a9a;">/</span> 
                  {{ setData.setSummary.homeServes }} <span style="color: #9a9a9a;">/</span> 
                  {{ setData.setSummary.homeServePointsPerc }}%
                </div>
                <div>
                  {{ setData.setSummary.awayServePoints }} <span style="color: #9a9a9a;">/</span> 
                  {{ setData.setSummary.awayServes }} <span style="color: #9a9a9a;">/</span> 
                  {{ setData.setSummary.awayServePointsPerc }}%
                </div>
              </td>
              <td style="padding: 0px 20px; border-right: 1px solid #02252e; text-align: center;">
                <div>{{ setData.setSummary.homeStreak }}</div>
                <div>{{ setData.setSummary.awayStreak }}</div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div>
      <span class="header-title">
        SPECTATORS
      </span>
    </div>
    <div class="setContainer">
      <div style="margin-top: 30px;" v-if="this.lineChartData.length">
        <GChart type="AreaChart" :data="lineChartData" :options="lineChartOptions" class="chartSpectators" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueGoogleCharts from 'vue-google-charts'

Vue.use(VueGoogleCharts)

export default {
  data () {
    return {
      setsData: [],
      homeName: null,
      awayName: null,
      groupName: null,
      tournamentName: null,
      matchData: null,
      ticks: [],
      lineChartData: null,
      lineChartOptions: {
        vAxis: {
          baselineColor: '#aaa',
          textStyle: {
            color: 'white',
            fontName: 'Nunito',
            fontSize: 16
          },
          minorGridLines: {
            count: 3
          },
          gridlines: {
            count: 2,
            color: '#aaa'
          }
        },
        hAxis: {
          baselineColor: '#aaa',
          textStyle: {
            color: 'white',
            fontName: 'Nunito',
            fontSize: 12
          },
          minorGridLines: {
            count: 3
          },
          gridlines: {
            count: 2,
            color: '#aaa'
          },
          ticks: [],
          slantedTextAngle: 40
        },
        lineWidth: 4,
        pointSize: 10,
        pointShape: 'circle',
        pointsVisible: true,
        legend: {
          position: 'top',
          textStyle: {
            color: 'white', fontSize: 16
          }
        },
        backgroundColor: '#0e3c46',
        curveType: 'function',
        chart: {
          title: 'Spectators'
        },
        chartArea: {
          backgroundColor: '#0e3c46'
        }
      }
    }
  },
  mounted () {
    this.idle = false
    axios.get('/api/matches/' + this.$route.params.id + '/timeline').then((res) => {
      this.setsData = res.data.setsData
      this.homeName = res.data.matchData.homeName
      this.awayName = res.data.matchData.awayName
      this.groupName = res.data.matchData.groupName
      this.tournamentName = res.data.matchData.tournamentName
      this.matchData = res.data.matchData
    })
    axios.get('/api/matches/' + this.$route.params.id + '/spectatortimeline').then((res) => {
      this.lineChartData = res.data.resource
      this.lineChartOptions.hAxis.ticks = res.data.ticks
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
.lastPointsResult {
  min-width: 60px; 
  border: none; 
  font-size: 20pt;
  text-align: center;
  font-weight: 600;
  color: white;
}

.chartSpectators {
    height: 300px;
}

.noborder {
  border: none;
}

.txtc {
  text-align: center;
}

table {
  td {
    border-right: 1px solid #585858;
  }
}

.summaryFacts {
  margin-top: 20px;
  background-color: #105869;
  padding: 10px;
  text-align: left;
  color: white;
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
  width: 95%;
  margin: 30px auto;
}

.playerName {
  text-align: left;
  font-size: 18pt;
  color: white;
  min-width: 250px;
  padding-right: 20px;
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

.header-title-players {
  font-size: 20px;
  color: white;
}

.header-title-group {
  font-size: 40px;
  color: white;
  text-transform: uppercase;
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

/*== start of code for tooltips ==*/
.tool {
    cursor: help;
    position: relative;
    font-size: 20pt;
}


/*== common styles for both parts of tool tip ==*/
.tool::before,
.tool::after {
    left: 50%;
    opacity: 0;
    position: absolute;
    z-index: -100;
}

.tool:hover::before,
.tool:focus::before,
.tool:hover::after,
.tool:focus::after {
    opacity: 1;
    transform: scale(1) translateY(0);
    z-index: 100; 
}


/*== pointer tip ==*/
.tool::before {
    border-style: solid;
    border-width: 1em 0.75em 0 0.75em;
    border-color: #3E474F transparent transparent transparent;
    bottom: 100%;
    content: "";
    margin-left: -0.5em;
    transition: all .65s cubic-bezier(.84,-0.18,.31,1.26), opacity .65s .5s;
    transform:  scale(.6) translateY(-90%);
} 

.tool:hover::before,
.tool:focus::before {
    transition: all .65s cubic-bezier(.84,-0.18,.31,1.26) .2s;
}

/*== speech bubble ==*/
.tool::after {
  font-size: 12pt;
  background: #3E474F;
  border-radius: .25em;
  bottom: 180%;
  color: #EDEFF0;
  content: attr(data-tip);
  margin-left: -8.75em;
  padding: 1em;
  transition: all .65s cubic-bezier(.84,-0.18,.31,1.26) .2s;
  transform:  scale(.6) translateY(50%);  
  width: 17.5em;
}

.tool:hover::after,
.tool:focus::after  {
  transition: all .65s cubic-bezier(.84,-0.18,.31,1.26);
}

@media (max-width: 760px) {
  .tool::after { 
        font-size: .75em;
        margin-left: -5em;
        width: 10em; 
  }
}
</style>
