<template>
  <div class="mainContainer">
    <router-link to="/playoffs/match/list" tag="div" class="playoffBanner">
      <div style="width: 100%;">
        <span class="header-title">
          <span class="fa-stack">
            <i class="fas fa-stack-2x fa-circle stackCircle"></i>
            <i class="fas fa-stack-2x fa-table-tennis stackPaddle"></i>
          </span>
          <span>PLAYOFFS WINTER 2019 !!!</span>
        </span>
      </div>
    </router-link>
    <div class="halfContainer">
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
    <div class="halfContainer">
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

export default {
  name: 'IndexPage',
  components: {
    MatchResults,
    MatchSchedule,
    MatchScheduleOverdue
  },
  data () {
    return {
      msg: "Today's schedule"
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.halfContainer {
  float: left;
  width: 50%;
  margin-top: 40px;
}

.inContainer90 {
  margin-top: 20px;
  width: 95%;
}

.mainContainer {
  padding: 0px 20px 0px 20px;
}

.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 40px;
  margin-right: 40px;
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
   background: linear-gradient(150deg, #3d9a56 30%, #3e3e3e 60%);
   padding: 20px;
   width: 95%;
   overflow: hidden;
   height: 75px;
}

.insetshadow {
  color: #202020;
  background-color: #2d2d2d;
  letter-spacing: .1em;
  text-shadow: 
    -1px -1px 1px #111, 
    2px 2px 1px #363636;
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
  color: #459a4c;
  font-size: 50px;
  margin-top: 10px;
}
</style>
