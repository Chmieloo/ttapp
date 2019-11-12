<template>
  <div class="mainContainer">
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
<style lang="less" scoped>
.halfContainer {
  float: left;
  width: 50%;
  margin-top: 40px;
}

.cutoff {
    font-size: 25pt;
    padding: 10px;
    background: #105869;
    -webkit-clip-path: polygon(0 0, 0 100%, 100% 100%, 100% 25%, 75% 0);
    clip-path: polygon(0 0, 0 100%, 96% 100%, 100% 0%, 100% 0);
    width: 700px;
    padding-left: 20px;
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

.lastWeekBox {
  margin-top: 30px;
  width: 90%;
  padding: 10px;
  border-bottom: 1px solid #105869;
}
</style>
