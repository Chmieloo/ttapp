<template>
  <div class="mainContainer">
    <table class="scoreTable">
      <tr>
        <td class="scoreLeft scoreContainer">
        <keep-alive>
          <component :is="homePlayerComponent" v-bind:homePlayerData="homePlayerData"></component>
        </keep-alive>
        </td>
        <td class="columnMid">
          <div class="midInfoHeader">MATCH MODE</div>
          <div class="midInfoValue">best of 4</div>
          <div class="midInfoHeader">SET SCORE</div>
          <div class="midInfoValue">
            <span>{{ match.homeScoreTotal }}</span>
            <span>:</span>
            <span>{{ match.awayScoreTotal }}</span>
          </div>
          <div class="midInfoHeader">RALLIES</div>
          <div class="midInfoValue">11 - 5</div>
          <div class="midInfoValue">2 - 11</div>
          <div class="midInfoValue">11 - 13</div>
        </td>
        <td class="scoreRight scoreContainer">
          <component :is="awayPlayerComponent" v-bind:awayPlayerData="awayPlayerData"></component>
        </td>
      </tr>
    </table>
    <div v-on:click="flipSides()">flip sides</div>
  </div>
</template>

<script>
import axios from 'axios'
import HomePlayer from './HomePlayer.vue'
import AwayPlayer from './AwayPlayer.vue'

export default {
  components: {
    HomePlayer,
    AwayPlayer
  },
  data () {
    return {
      match: [],
      homePlayerComponent: null,
      awayPlayerComponent: null,
      awayPlayerData: null,
      homePlayerData: null
    }
  },
  mounted () {
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.homePlayerComponent = HomePlayer
      this.awayPlayerComponent = AwayPlayer
      this.match = res.data
      this.homePlayerData = {
        'homePlayerName': res.data.homePlayerName
      }
      this.awayPlayerData = {
        'awayPlayerName': res.data.awayPlayerName
      }
    })
  },
  methods: {
    flipSides () {
      this.tempComponent = this.homePlayerComponent
      this.homePlayerComponent = this.awayPlayerComponent
      this.awayPlayerComponent = this.tempComponent
    }
  }
}
</script>

<style lang="less" scoped>
.scoreTable {
  width: 100%;
  margin-top: 30px;
  .columnMid {
    width: 20%;
    text-align: center;
    .midInfoHeader {
      text-transform: uppercase;
      font-size: 30px;
      font-weight: 600;
    }
    .midInfoValue {
      padding-top: 0px;
      font-size: 30px;
      color: #555;
    }
  }
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
</style>
