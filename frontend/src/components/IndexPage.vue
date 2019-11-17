<template>
  <div class="mainContainer">
    <div v-if="filteredPlayoffs">
      <div v-for="playoffsTournament in filteredPlayoffs" v-bind:key="playoffsTournament.tournamentId">
        <div class="poBox" style="overflow: hidden;">
          <table style="width: 100%;">
            <tr>
              <td style="font-size: 50px; font-family: Poppins; font-weight: 900; color: white;">
                <span class="fa-stack" style="font-size: 30px;">
                  <i class="fas fa-circle fa-stack-2x stack-shield"></i>
                  <i class="fas fa-stack-2x fa-medal stack-medal" style="color: black;"></i>
                </span>
                {{ playoffsTournament.tournamentName }}
              </td>
              <td style="vertical-align: top; text-align: right;" rowspan="2">
                <div v-if="filteredLiveMatches.length > 0">
                  <div style="margin-bottom: 15px;">
                    <span style="padding: 5px 8px; background-color: #2399b5; color: white; font-weight: 600; border-radius: 5px;" class="blinking">
                      LIVE
                    </span>
                  </div>
                  <div v-for="filteredLiveMatch in filteredLiveMatches" v-bind:key="filteredLiveMatch.matchId">
                    <div style="margin-bottom: 20px;">
                      <span style="color: white;">{{ filteredLiveMatch.homePlayerName }}</span> vs 
                      <span style="color: white;">{{ filteredLiveMatch.awayPlayerName }}</span>
                    </div>
                    <span style="padding: 5px 8px; background-color: #105869; color: white; font-weight: 600; border-radius: 5px;">
                      <router-link :to="'/match/' + filteredLiveMatch.matchId + '/spectate'">spectate</router-link>
                    </span>                                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <router-link :to="'/playoffs/' + playoffsTournament.tournamentId + '/ladders'">ladders</router-link> | 
                <router-link :to="'/playoffs/' + playoffsTournament.tournamentId + '/info'">schedule</router-link>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="halfContainerSmaller">
      <span class="header-title">
        <i class="fas fa-clock marr20"></i>
        OVERDUE MATCHES
        </span>
      <div class="inContainer90">
        <MatchScheduleOverdue />
      </div>
      <span class="header-title">
        <i class="fas fa-clipboard-list marr20"></i>
        TOURNAMENT SCHEDULE
      </span>
      <div class="inContainer90">
        <MatchSchedule />
      </div>
    </div>
    <div class="halfContainerBigger">
      <span class="header-title">
        <i class="fas fa-trophy marr20"></i>
        LAST 20 TOURNAMENT RESULTS
      </span>
      <div class="inContainer90">
        <MatchResults />
      </div>
    </div>
    <div style="clear: both;" />
  </div>
</template>

<script>
import MatchResults from './Match/MatchResults.vue'
import MatchSchedule from './Match/MatchSchedule.vue'
import MatchScheduleOverdue from './Match/MatchScheduleOverdue.vue'
import axios from 'axios'

export default {
  name: 'IndexPage',
  components: {
    MatchResults,
    MatchSchedule,
    MatchScheduleOverdue
  },
  data () {
    return {
      playoffs: [],
      officeId: 1,
      liveMatches: []
    }
  },
  mounted () {
    axios.get('/api/tournaments/info').then((res) => {
      this.playoffs = res.data
      this.officeId = parseInt(this.$localStorage.get('ttappOfficeId', 1))
    })
    this.getLiveMatches()
    setInterval(function () {
      this.getLiveMatches()
    }.bind(this), 10000)
  },
  methods: {
    getLiveMatches () {
      axios.get('/api/matches/live').then((res) => {
        this.liveMatches = res.data
      })
    }
  },
  computed: {
    filteredPlayoffs: function () {
      return this.playoffs.filter((playoff) => {
        return playoff.officeId === this.officeId
      })
    },
    filteredLiveMatches: function () {
      return this.liveMatches.filter((match) => {
        return match.officeId === this.officeId
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.halfContainer {
  float: left;
  width: 50%;
  margin-top: 40px;
}

.blinking{
  animation:blinkingText 3s infinite;
  background-color: #2399b5;
  padding: 3px 8px;
  border-radius: 3px;
  width: 100px !important;
}

@keyframes blinkingText{
	0%{		background-color: #2399b5;	}
	50%{	background-color: transparent;	}
	100%{	background-color: #2399b5;	}
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

.poBox {
  width: 95%; margin-top: 30px; padding: 20px;
  background: #0e3c46;
  padding: 20px;
  -webkit-box-shadow: 0px 0px 3px black;
  box-shadow: 0px 0px 3px black;
  height: 110px;
  background: linear-gradient(100deg, #556187 30%, #333e6c 60%);
}

.halfContainerSmaller {
  float: left;
  width: 45%;
  margin-top: 40px;
}

.halfContainerBigger {
  float: left;
  width: 55%;
  margin-top: 40px;
}

.inContainer90 {
  margin-top: 20px;
  width: 95%;
}

.mainContainer {
  padding: 0px 20px 0px 20px;
  width: 100%;
}

.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 40px;
  margin-right: 40px;
  a {
    color: #fff;
    text-decoration: none;
  }
  a:hover {
    text-decoration: none;
    color: #177990;
  }
}

.mainTable {
  width: 100%;
  margin-top: 20px;
}

.greyContainer {
  background: #3e3e3e;
  padding: 20px;
  border-radius: 10px 10px;
  box-shadow: 2px 2px 4px 0px black;
}

.greyContainer .title {
  color: white;
  font-weight: 600;
}

hr{
  border: none;
  border-bottom: 1px solid #b3b3b3;
  height: 1px;
}

.marr20 {
  margin-right: 20px;
}

.playoffBanner {
   margin-top: 40px;
   padding: 20px;
   width: 95%;
   overflow: hidden;
   height: 75px;
   background-color: #3e3e3e;
}

.playoffBanner:hover {
  background-color: #9cbed2;
  cursor: pointer;
}

.bannerImg {
  margin-left: 700px;
  font-size: 150px;
  margin-top: -180px;
}

.stackCircle {
  font-size: 75px;
  color: #ffffff;
}

.stackPaddle {
  color: #000;
  font-size: 50px;
  margin-top: 10px;
}

.tableImg {
  float: right;
  background: linear-gradient(100deg, #556187 30%, #333e6c 60%);
  height: 300px;
  width: 600px;
  border: 10px solid white;
  transform: rotate(10deg);
  margin-right: 50px;
  margin-top: -50px;
}

.stack-medal {
  color: #556181 !important;
  font-size: 47px;
}
</style>
