<template>
  <div class="mainContainer">
    <div v-for="ladder in this.ladders" v-bind:key="ladder.id">
      <div style="padding: 0px 10px">
        <span class="header-title"> {{ ladder.divisionName }} LADDER </span>
      </div>
      <table style="width: 100%">
        <tr>
          <td v-for="i in stages" v-bind:key="i" class="stageColumn">
            <div class="stageDiv">
              <div v-for="match in ladder.data" v-bind:key="match.id">
                <div
                  v-if="match.stage == i"
                  style="margin-bottom: 20px"
                  class="matchContainer"
                >
                  <table class="ladderTable">
                    <tr>
                      <td rowspan="3" class="matchOrder">
                        <span class="fa-stack">
                          <i class="fas fa-circle fa-stack-2x stack-shield"></i>
                          <i class="fas fa-stack-2x stack-star matchNum">{{
                            match.order
                          }}</i>
                        </span>
                      </td>
                      <td
                        style="padding-left: 20px"
                        class="matchName"
                        colspan="2"
                        v-if="match.winnerId == 0"
                      >
                        <span>{{ match.name }}</span>
                      </td>
                      <td
                        style="padding-left: 20px"
                        class="matchNameDone"
                        colspan="2"
                        v-else
                      >
                        <span>{{ match.name }}</span>
                        <span v-if="match.isWalkover" style="float: right">
                          <i class="fas fa-flag"></i>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td
                        style="padding-left: 10px"
                        v-bind:class="
                          match.winnerId != 0 &&
                          match.winnerId == match.homePlayerId
                            ? 'winner-color'
                            : ''
                        "
                      >
                        <span
                          style="color: #aaa"
                          v-if="match.homePlayerId == 0"
                          >{{ match.homePlayerDisplayName }}</span
                        >
                        <span v-else>{{ match.homePlayerDisplayName }}</span>
                      </td>
                      <td style="text-align: right">
                        <span v-if="match.winnerId != 0">
                          {{ match.homeScoreTotal }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td
                        style="padding-left: 10px"
                        v-bind:class="
                          match.winnerId != 0 &&
                          match.winnerId == match.awayPlayerId
                            ? 'winner-color'
                            : ''
                        "
                      >
                        <span
                          style="color: #aaa"
                          v-if="match.awayPlayerId == 0"
                          >{{ match.awayPlayerDisplayName }}</span
                        >
                        <span v-else>{{ match.awayPlayerDisplayName }}</span>
                      </td>
                      <td style="text-align: right">
                        <span v-if="match.winnerId != 0">
                          {{ match.awayScoreTotal }}
                        </span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PlayoffsMatchList",
  data() {
    return {
      matches: [],
      stages: 4,
      division: null,
      playoffs: false,
      ladders: [],
    };
  },
  mounted() {
    this.getData();
    setInterval(
      function () {
        this.getData();
      }.bind(this),
      10000
    );
  },
  methods: {
    getData() {
      axios
        .get("/api/playoffs/" + this.$route.params.id + "/groups")
        .then((res) => {
          this.ladders = res.data;
        });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainMatchContainer {
  background: #3e3e3e;
  padding: 20px;
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

.w30pc {
  width: 30%;
}

.score + .score:before {
  content: ", ";
}

.stageColumn {
  width: 25%;
  padding: 10px;
  .stageDiv {
    padding: 20px;
    padding-top: 40px;
    // background-color: #151515;
  }
}

.matchOrder {
  background-color: black;
  color: white;
  width: 50px;
  text-align: center;
  border-radius: 5px 0px 5px 5px;
}

.matchName {
  padding-left: 20px;
  background-color: brown;
  border-radius: 0px 5px 5px 0px;
}

.matchNameDone {
  background-color: #6b8e42;
  padding-left: 20px;
  border-radius: 0px 5px 5px 0px;
}

.ladderTable {
  width: 100%;
  color: white;
  td {
    padding: 10px 10px;
  }
}

.matchContainer {
  background-color: #3e3e3e;
  padding: 5px;
  border-radius: 10px;
}

.winner-color {
  color: #40c500;
}

.matchNum {
  color: black;
  font-family: "Roboto", sans-serif;
  font-size: 20px;
  font-weight: 600;
  margin-top: 6px;
}
</style>
