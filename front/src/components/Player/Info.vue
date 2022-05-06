<template>
  <div id="app">
    <div class="mainProfileContainer">
      <table>
        <tr>
          <td>
            <div class="pictureContainer">
              <table v-if="this.player">
                <tr>
                  <td style="padding-top: 20px">
                    <div class="cutoutPic">
                      <img :src="playerPicUrl" class="pic" />
                    </div>
                  </td>
                  <td class="padl20">
                    <div class="playerInfoName">
                      <span class="playerName">{{ player.name }}</span>
                    </div>
                    <div class="playerInfo">
                      <div>
                        <div style="float: left">
                          <div>ELO<br />RATING</div>
                          <div class="elofont">{{ player.elo }}</div>
                        </div>
                        <div style="float: left; margin-left: 20px">
                          <div>MATCHES<br />PLAYED</div>
                          <div class="elofont">{{ player.played }}</div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </td>
          <td>
            <div class="pieContainer">
              <GChart
                type="PieChart"
                :data="pieChartData"
                :options="pieChartOptions"
                class="chartPercentage"
              />
            </div>
          </td>
        </tr>
      </table>
      <div class="dataContainer">
        <GChart
          type="AreaChart"
          :data="lineChartData"
          :options="lineChartOptions"
          class="chartElo"
        />
      </div>
      <div class="dataContainer">
        <div style="padding: 10px 0px; font-size: 20px; color: white">
          LAST TOURNAMENT MATCHES
        </div>
        <table style="width: 100%">
          <tr v-for="result in results" v-bind:key="result.id" class="row-data">
            <td>
              {{ result.datePlayed }}
            </td>
            <td class="w30pc">
              <span
                v-bind:class="
                  result.winnerId == result.homePlayerId ? 'winner-color' : ''
                "
              >
                {{ result.homePlayerName }}
              </span>
            </td>
            <td class="w30pc">
              <span
                v-bind:class="
                  result.winnerId == result.awayPlayerId ? 'winner-color' : ''
                "
              >
                {{ result.awayPlayerName }}
              </span>
            </td>
            <td>
              {{ result.homeScoreTotal }}
            </td>
            <td>-</td>
            <td>
              {{ result.awayScoreTotal }}
            </td>
            <td style="text-align: right">
              <span v-if="result.isWalkover == '1'"> walkover </span>
              <span v-else class="setScores">
                (
                <span
                  v-for="score in result.scores"
                  v-bind:key="score.set"
                  class="score"
                >
                  {{ score.home }} - {{ score.away }}
                </span>
                )
              </span>
            </td>
          </tr>
        </table>
      </div>
      <div class="dataContainer">
        <div style="padding: 10px 0px; font-size: 20px; color: white">
          UPCOMING TOURNAMENT MATCHES
        </div>
        <table style="width: 100%">
          <tr v-for="event in schedule" v-bind:key="event.id" class="row-data">
            <td style="width: 200px">
              {{ event.dateOfMatch }}
            </td>
            <td v-if="event.homePlayerId == player.id">
              {{ event.awayPlayerName }}
            </td>
            <td v-else>
              {{ event.homePlayerName }}
            </td>
            <td v-if="event.homePlayerId == player.id">
              ELO rating <span style="color: white">{{ event.awayElo }}</span>
            </td>
            <td v-else>
              ELO rating <span style="color: white">{{ event.homeElo }}</span>
            </td>
            <td v-if="event.homePlayerId == player.id">
              <span v-if="event.awayEloDiff < 0">
                <i class="fas fa-chevron-circle-down"></i>
                {{ event.awayEloDiff }}
              </span>
              <span v-else>
                <i class="fas fa-chevron-circle-up"></i> {{ event.awayEloDiff }}
              </span>
            </td>
            <td v-else>
              <span v-if="event.homeEloDiff < 0">
                <i class="fas fa-chevron-circle-down"></i>
                {{ event.homeEloDiff }}
              </span>
              <span v-else>
                <i class="fas fa-chevron-circle-up"></i> {{ event.homeEloDiff }}
              </span>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { GChart } from "vue-google-charts";

export default {
  name: "App",
  components: { GChart },
  data() {
    return {
      player: null,
      playerPicUrl: null,
      winPercentage: 0,
      drawPercentage: 0,
      lossPercentage: 0,
      strokeDashArrayWins: "0 100",
      strokeDashArrayDraws: "0 100",
      strokeDashArrayLosses: "0 100",
      pieChartData: null,
      pieChartOptions: {
        backgroundColor: "#0e3c46",
        chartArea: {
          backgroundColor: "#0e3c46",
          height: "100%",
        },
        legend: {
          alignment: "center",
          position: "right",
          textStyle: {
            color: "white",
            fontName: "Nunito",
            fontSize: 16,
          },
        },
        pieSliceTextStyle: {
          color: "white",
          fontName: "Nunito",
          fontSize: 16,
        },
        slices: [
          { color: "#2dd7ff" },
          { color: "black" },
          { color: "#105869" },
        ],
      },
      lineChartData: null,
      lineChartOptions: {
        vAxis: {
          baselineColor: "#aaa",
          textStyle: {
            color: "white",
            fontName: "Nunito",
            fontSize: 16,
          },
          minorGridLines: {
            count: 3,
          },
          gridlines: {
            count: 2,
            color: "#aaa",
          },
        },
        hAxis: {
          baselineColor: "#aaa",
          textStyle: {
            color: "white",
            fontName: "Nunito",
            fontSize: 16,
          },
          minorGridLines: {
            count: 3,
          },
          gridlines: {
            count: 2,
            color: "#aaa",
          },
        },
        lineWidth: 4,
        pointSize: 10,
        pointShape: "circle",
        pointsVisible: true,
        legend: {
          position: "top",
          textStyle: {
            color: "white",
            fontSize: 16,
          },
        },
        backgroundColor: "#0e3c46",
        curveType: "function",
        chart: {
          title: "Player's ELO history",
        },
        chartArea: {
          backgroundColor: "#0e3c46",
        },
      },
    };
  },
  mounted() {
    axios
      .all([
        axios.get("/api/players/" + this.$route.params.id),
        axios.get("/api/players/" + this.$route.params.id + "/results"),
        axios.get("/api/players/" + this.$route.params.id + "/schedule"),
      ])
      .then(
        axios.spread((player, results, schedule) => {
          console.log(player.data);
          this.player = player.data;
          this.strokeDashArrayWins =
            player.data.winPercentage + " " + player.data.notWinPercentage;
          this.winPercentage = player.data.winPercentage;
          this.strokeDashArrayDraws =
            player.data.drawPercentage + " " + player.data.notDrawPercentage;
          this.drawPercentage = player.data.drawPercentage;
          this.strokeDashArrayLosses =
            player.data.lossPercentage + " " + player.data.notLossPercentage;
          this.lossPercentage = player.data.lossPercentage;
          this.playerPicUrl = player.data.pic;
          this.lineChartData = player.data.eloHistory;
          this.pieChartData = player.data.pieData;
          this.results = results.data;
          this.schedule = schedule.data;
          console.log(this.chartData);
        })
      )
      .catch((error) => {
        console.log("Error when getting data for matches " + error);
      });
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.elofont {
  font-size: 25pt;
  color: white;
}

.chartElo {
  height: 300px;
}

.playerInfoName {
  margin-bottom: 20px;
}

.playerInfo {
  color: #a9a9a9;
  font-size: 18px;
}

.padl20 {
  padding-left: 20px;
}

.mainProfileContainer {
  margin: 0 auto;
  width: 1000px;
}

.container {
  margin-bottom: 20px;
  background: #0e3c46;
  padding: 20px;
  box-shadow: 0px 0px 3px black;
}

.pictureContainer {
  .container;
  margin-top: 30px;
  width: 500px;
  height: 200px;
  padding-top: 20px;
}

.dataContainer {
  .container;
  margin-top: 20px;
}

.pieContainer {
  .container;
  margin-top: 30px;
  height: 200px;
  width: 380px;
  margin-left: 32px;
}

.pic {
  margin: 0 auto;
  width: 150px;
  margin-top: -20px;
}

div.cutoutPic {
  width: 154px;
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
  box-shadow: 0px 200px 0px 300px #0e3c46;
}

.playerName {
  font-size: 25px;
  color: white;
}

.infoCard {
  border: 1px solid #252525;
  padding: 20px;
  float: left;
  width: 131px;
  min-height: 150px;
  text-align: center;
  .val {
    margin-top: 20px;
    color: white;
    font-size: 70px;
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    max-height: 100px;
  }
}

.score + .score:before {
  content: ", ";
}

table.playerData {
  width: 100%;
  td {
    padding: 8px;
    text-align: center;
    .innerData {
      height: 180px;
      border: 1px solid #252525;
    }
    .lab {
      font-size: 15px;
      padding: 10px 0px 20px 0px;
    }
    .bigVal {
      font-size: 60px;
      font-family: "Poppins", "Avenir", Helvetica, Arial, sans-serif;
      color: white;
      font-weight: 600;
    }
  }
}

.percentage {
  position: relative;
  font-size: 20px;
  top: -125px;
}

.winner-color {
  color: #40c500;
}

.marl20 {
  margin-left: 20px;
}

.mainContainer {
  max-width: 800px;
  margin: 40px auto;
  background: #2d2c2d;
  padding: 0px;
  .playerCardHeader {
    padding: 20px;
    background: #383738;
    box-shadow: 0px 1px 10px 0px black;
  }
  .playerCardPic {
    padding: 20px;
    text-align: center;
  }
  .playerCardInfo {
    padding: 23px;
    background: #383738;
    box-shadow: 0px -1px 5px 0px black;
  }
}
</style>
