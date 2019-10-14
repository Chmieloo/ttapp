<template>
  <div class="mainContainer">
    <table style="width: 100%; border-spacing: 30px; border-collapse: separate;">
      <tr>
        <td colspan="3" STYLE="font-size: 25pt;">
          TOTAL POINTS SCORED
        </td>
      </tr>
      <tr>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ALL TIME
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredAllTimePointLeaders" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ONGOING TOURNAMENT
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredCurrentTournamentPointsLeaders" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              LAST WEEK
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredLastWeekPointsLeaders" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ leader.points }}</span>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="3" STYLE="font-size: 25pt;">
          HIGHEST AVERAGE OF WINNER'S POINT ADVANTAGE
        </td>
      </tr>
      <tr>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ALL TIME
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredAvgDiffAllTime" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ parseFloat(leader.avgDiff).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ONGOING TOURNAMENT
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredAvgDiffCurrentTournament" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ parseFloat(leader.avgDiff).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              LAST WEEK
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredAvgDiffLastWeek" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ parseFloat(leader.avgDiff).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="3" STYLE="font-size: 25pt;">
          ELO LEADERS
        </td>
      </tr>
      <tr>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ALL TIME
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredEloLeaders" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value">{{ leader.elo }}</span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ELO INCREASE ONGOING TOURNAMENT
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredEloLeadersTournament" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value"><span v-if="(leader.elodiff > 0)">+</span>{{ leader.elodiff }} <span class="valueTelo">({{ leader.telo }})</span></span>
              </div>
            </div>
          </div>
        </td>
        <td style="width: 33%;">
          <div class="container">
            <span class="header-title">
              <i class="fas fa-trophy header-icon"></i>
              ELO INCREASE LAST WEEK
            </span>
            <div style="margin-top: 10px;">
              <div v-for="(leader, index) in filteredEloLeadersLastWeek" v-bind:key="leader.id" class="row-item">
                <router-link :to="'/player/' + leader.id + '/info'">{{ index+1 }}. {{ leader.name }}</router-link>
                <span class="fr value"><span v-if="(leader.elodiff > 0)">+</span>{{ leader.elodiff }} <span class="valueTelo">({{ leader.telo }})</span></span>
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
import Vue from 'vue'
import VueLocalStorage from 'vue-localstorage'
Vue.use(VueLocalStorage, {
  name: 'localStorage',
  bind: true
})

export default {
  name: 'App',
  data () {
    return {
      leaders: [],
      allTimePointsLeaders: [],
      currentTournamentPointsLeaders: [],
      lastWeekPointsLeaders: [],
      avgDiffAllTime: [],
      avgDiffCurrentTournament: [],
      avgDiffLastWeek: [],
      eloLeaders: [],
      eloLeadersTournament: [],
      eloLeadersLastWeek: [],
      officeId: 1
    }
  },
  mounted () {
    axios.get('/api/leaders').then((res) => {
      this.leaders = res.data
      this.allTimePointsLeaders = res.data['allTimePointsLeaders']
      this.currentTournamentPointsLeaders = res.data['currentTournamentPointsLeaders']
      this.lastWeekPointsLeaders = res.data['lastWeekPointsLeaders']
      this.avgDiffAllTime = res.data['avgDiffAllTime']
      this.avgDiffCurrentTournament = res.data['avgDiffCurrentTournament']
      this.avgDiffLastWeek = res.data['avgDiffLastWeek']
      this.eloLeaders = res.data['eloLeaders']
      this.eloLeadersTournament = res.data['eloLeadersTournament']
      this.eloLeadersLastWeek = res.data['eloLeadersLastWeek']
      this.officeId = parseInt(this.$localStorage.get('ttappOfficeId', 1))
    })
  },
  computed: {
    filteredAllTimePointLeaders: function () {
      return this.allTimePointsLeaders.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredCurrentTournamentPointsLeaders: function () {
      return this.currentTournamentPointsLeaders.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredLastWeekPointsLeaders: function () {
      return this.lastWeekPointsLeaders.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredAvgDiffAllTime: function () {
      return this.avgDiffAllTime.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredAvgDiffCurrentTournament: function () {
      return this.avgDiffCurrentTournament.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredAvgDiffLastWeek: function () {
      return this.avgDiffLastWeek.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredEloLeaders: function () {
      return this.eloLeaders.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredEloLeadersTournament: function () {
      return this.eloLeadersTournament.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    },
    filteredEloLeadersLastWeek: function () {
      return this.eloLeadersLastWeek.filter((leader) => {
        return parseInt(leader.officeId) === this.officeId
      })
    }
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

.valueTelo {
  color: #9a9a9a;
}

.header-title {
  font-size: 20px;
  margin-bottom: 10px;
}
</style>

