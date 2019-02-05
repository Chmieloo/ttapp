<template>
  <div id="app">
    <div class="mainContainer">
        <div class="playerCardHeader">
            <span class="header-title">player info</span>
        </div>
        <div class="playerCardPic">
            <div class="cutoutPic">
                <img src="https://upload.wikimedia.org/wikipedia/en/1/17/Batman-BenAffleck.jpg" class="pic" />
            </div>
            <span class="playerName">{{ player.name }}</span>
        </div>
        <div class="playerCardInfo">
            <div class="infoCard">
                <div class="lab">MATCHES PLAYED</div>
                <div class="val">{{ player.played }}</div>
            </div>
            <div class="infoCard marl20">
                <div class="lab">WINS : {{ player.wins }}</div>
                <div class="val">
                    <svg width="100%" height="100px" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#383738"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="20" fill="transparent" stroke="#424242" stroke-width="2"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d00000" stroke-width="2" :stroke-dasharray="strokeDashArrayWins" stroke-dashoffset="60%"></circle>
                        <text x="14px" y="23px" style="font-size: 8px;" fill="#fff">{{ winPercentage }}%</text>
                    </svg>
                </div>
            </div>
            <div class="infoCard marl20">
                <div class="lab">DRAWS : {{ player.draws }}</div>
                <div class="val">
                    <svg width="100%" height="100px" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#383738"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="20" fill="transparent" stroke="#424242" stroke-width="2"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d00000" stroke-width="2" :stroke-dasharray="strokeDashArrayDraws" stroke-dashoffset="60%"></circle>
                        <text x="14px" y="23px" style="font-size: 8px;" fill="#fff">{{ drawPercentage }}%</text>
                    </svg>
                </div>
            </div>
            <div class="infoCard marl20">
                <div class="lab">LOSSES : {{ player.losses }}</div>
                <div class="val">
                    <svg width="100%" height="100px" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#383738"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="20" fill="transparent" stroke="#424242" stroke-width="2"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#d00000" stroke-width="2" :stroke-dasharray="strokeDashArrayLosses" stroke-dashoffset="60%"></circle>
                        <text x="14px" y="23px" style="font-size: 8px;" fill="#fff">{{ lossPercentage }}%</text>
                    </svg>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'App',
  data () {
    return {
      player: null,
      winPercentage: 0,
      drawPercentage: 0,
      lossPercentage: 0,
      strokeDashArrayWins: '0 100',
      strokeDashArrayDraws: '0 100',
      strokeDashArrayLosses: '0 100'
    }
  },
  mounted () {
    axios.get('/api/players/' + this.$route.params.id).then((res) => {
      this.player = res.data
      this.strokeDashArrayWins = res.data.winPercentage + ' ' + res.data.notWinPercentage
      this.winPercentage = res.data.winPercentage
      this.strokeDashArrayDraws = res.data.drawPercentage + ' ' + res.data.notDrawPercentage
      this.drawPercentage = res.data.drawPercentage
      this.strokeDashArrayLosses = res.data.lossPercentage + ' ' + res.data.notLossPercentage
      this.lossPercentage = res.data.lossPercentage
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.infoCard {
    border: 1px solid #252525;
    padding: 20px;
    float: left;
    width: 131px;
    min-height: 150px;
    text-align: center;
    .lab {
        font-size: 15px;
    }
    .val {
        margin-top: 20px;
        color: white;
        font-size: 70px;
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        max-height: 100px;
    }
}

.percentage {
    position: relative;
    font-size: 20px;
    top: -125px;
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

.playerName {
    font-size: 40px;
    text-transform: uppercase;
    color: white;
}

.pic {
    margin: 0 auto;
    width: 150px;
    height: 150px;
}

div.cutoutPic {
    width: 150px;
    height: 150px;
    position:relative;
    overflow:hidden;
    margin: 0 auto;
}

div.cutoutPic:before{
    content:'';
    position: absolute;
    bottom: 0;
    width: 146px;
    height: 146px;
    border-radius: 100%;
    border: 2px solid white;
    border-radius: 100%;
    box-shadow: 0px 200px 0px 300px #2d2c2d;
}
</style>
