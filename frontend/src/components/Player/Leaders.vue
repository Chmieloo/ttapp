<template>
  <div class="mainContainer">
    <table style="width: 100%; border-spacing: 30px; border-collapse: separate;">
      <tr>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ALL TIME POINTS SCORED
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in this.leaders['allTimePointsLeaders']" v-bind:key="leader.id" class="row-item">
                <span class="fl">{{ index+1 }}. {{ leader.name }}</span>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ONGOING TOURNAMENT POINTS SCORED
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in this.leaders['currentTournamentPointsLeaders']" v-bind:key="leader.id" class="row-item">
                <span class="fl">{{ index+1 }}. {{ leader.name }}</span>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              LAST WEEK'S POINTS SCORED
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in this.leaders['lastWeekPointsLeaders']" v-bind:key="leader.id" class="row-item">
                <span class="fl">{{ index+1 }}. {{ leader.name }}</span>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'App',
  data () {
    return {
      leaders: []
    }
  },
  mounted () {
    axios.get('/api/leaders').then((res) => {
      this.leaders = res.data
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.fl {
  float: left;
}

.fr {
  float: right;
}

.mainContainer {
  padding: 30px 25px;
}

.container {
  margin-bottom: 20px;
  background: #0e3c46;
  padding: 20px;
  box-shadow: 0px 0px 3px black;
  .row-item{
    height: 35px;
    line-height: 35px;
    border-bottom: 1px solid #ffffff2b;
  }
  :last-child {
    border-bottom: none;
  }
}

.header-icon {
  color: white;
  font-size: 20px;
  padding-right: 15px;
}

.value {
  color: white;
  font-weight: 600;
}

.header-title {
  font-size: 20px;
  margin-bottom: 10px;
}
</style>
