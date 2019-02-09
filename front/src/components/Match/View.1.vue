<template>
  <div class="mainContainer">
    <table class="scoreTable">
      <tr>
        <td class="scoreLeft scoreContainer">
          <span class="header-title">{{ match.homePlayerDisplayName }}</span>
          <div class="inContainer90">
            8
          </div>
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
          <span class="header-title">{{ match.awayPlayerDisplayName }}</span>
          <div class="inContainer90">
            3
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MatchView',
  data () {
    return {
      match: []
    }
  },
  mounted () {
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.match = res.data
      this.leftScoreTotal = 100
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
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

.halfContainer {
  float: left;
  width: 50%;
  margin-top: 40px;
  text-align: center;
}

.inContainer90 {
  width: 95%;
  font-size: 400px;
  line-height: 400px;
  font-weight: 900;
  color: white;
}

.mainContainer {
  padding: 0px 20px 0px 20px;
}

.header-title {
  color: white;
  font-size: 40px;
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

</style>
