<template>
  <div class="mainContainer">
    <span style="margin-top: 40px;">&nbsp;</span>
    <table class="tbl" v-for="match in this.data" v-bind:key="match.id">
      <tr class="playoffsRow">
        <td style="padding: 0px 20px; width: 50px;">
          <span class="fa-stack">
            <i class="fas fa-circle fa-stack-2x stack-shield"></i>
            <i class="fas fa-stack-2x stack-star matchNum">{{ match.order }}</i>
          </span>
        </td>
        <td v-bind:class="match.winnerId != 0 && match.winnerId == match.homePlayerId ? 'module winner-class' : 'module normal-class'" style="text-align: right; padding-right: 30px;">
          <span v-if="parseInt(match.homePlayerId) > 0" style="color: white;">
            {{ match.homePlayerDisplayName }}
          </span>
          <span v-else>
            {{ match.homePlayerDisplayName }}
          </span>
        </td>
        <td style="width: 40px; text-align: center; font-weight: 900; font-size: 20px; max-width: 100px;">
          {{ match.homeScoreTotal }}
        </td>
        <td class="module" style="background-color: #105869; width: 30px;">&nbsp;</td>
        <td style="width: 40px; text-align: center; font-weight: 900; font-size: 20px;">
          {{ match.awayScoreTotal }}
        </td>
        <td v-bind:class="match.winnerId != 0 && match.winnerId == match.awayPlayerId ? 'module winner-class' : 'module normal-class'" style="text-align: left; padding-left: 30px;">
          <span v-if="parseInt(match.awayPlayerId) > 0" style="color: white;">
            {{ match.awayPlayerDisplayName }}
          </span>
          <span v-else>
            {{ match.awayPlayerDisplayName }}
          </span>          
        </td>
        <td style="padding-left: 30px;">{{ match.division }}, {{ match.name }}</td>
        <td style="text-align: left; width: 120px;">
          <span v-if="match.status == 1">
            finished
          </span>
          <span v-else-if="match.status == 2" class="blinking">
            live
          </span>
          <span v-else>
            not started
          </span>
        </td>
        <td style="width: 100px; text-align: left;">
          <span v-if="match.isFinished == 0">
            <router-link :to="'/match/' + match.matchId + '/spectate'">watch</router-link>
          </span>
          <span v-else>
            <router-link :to="'/match/' + match.matchId + '/summary'">summary</router-link>
          </span>
        </td>
        <td style="padding-right: 20px; width: 70px;">
          <span v-if="match.status == 3">
            <router-link :to="'/match/playoff/' + match.matchId + '/view'">play</router-link>
          </span>
        </td>
      </tr>
      <tr v-if="match.isFinished" style="background-color: #022d38; border: 1px solid #0e3f4a;">
        <td colspan="6">&nbsp;</td>
        <td colspan="4" style="padding: 15px;">
          {{ match.outcome }}
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PlayoffsInfo',
  data () {
    return {
      data: [],
      matches: [],
      stages: 4,
      division: null,
      playoffs: false,
      ladders: []
    }
  },
  mounted () {
    this.getData()
    setInterval(function () {
      this.getData()
    }.bind(this), 10000)
  },
  methods: {
    getData () {
      axios.get('/api/playoffs/' + this.$route.params.id).then((res) => {
        this.data = res.data
      })
    }
  }
}
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

.blinking{
	animation:blinkingText 3s infinite;
}

@keyframes blinkingText{
	0%{		color: #fff;	}
	50%{	color: transparent;	}
	100%{	color: #fff;	}
}

.winner-class {
  background: #6b8e42;
  width: 250px;
}

.normal-class {
  background-color: #105869;
  width: 250px;
}

.tbl {
  border-collapse: collapse; 
  width: 90%; 
  margin: 10px auto;
}

.module {
  --notchSize: 20px;  
  clip-path: 
    polygon(
      0% 100%, 
      var(--notchSize) 0%, 
      100% 0%, 
      calc(100% - var(--notchSize)) 100%, 
      100% calc(100% - var(--notchSize)), 
      calc(100% - var(--notchSize)) 100%, 
      0% 100%, 
      0% 0%
    );
}

.playoffsRow {
  height: 50px;
  line-height: 50px;
  background-color: #0e3f4a;
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

.stack-shield {
  color: #02252e;
}

.matchNum {
  color: white;
  font-family: 'Nunito', sans-serif;
  font-size: 16px;
  font-weight: 600;
  margin-top: 9px;
}
</style>
