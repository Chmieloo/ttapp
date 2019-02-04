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
                <div class="lab">WINS</div>
                <div class="val">{{ player.played }}</div>
            </div>
            <div class="infoCard marl20">
                <div class="lab">DRAWS</div>
                <div class="val">{{ player.played }}</div>
            </div>
            <div class="infoCard marl20">
                <div class="lab">LOSSES</div>
                <div class="val">
                    <dough-nut v-if="loaded" :chart-data="downloads"></dough-nut>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import DoughNut from '../Charts/DoughNut'

export default {
  name: 'App',
  components: {
    DoughNut
  },
  data () {
    return {
      player: null
    }
  },
  mounted () {
    axios.get('/api/players/' + this.$route.params.id).then((res) => {
      this.player = res.data
      this.downloads = [res.data.winPercentage, res.data.notWinPercentage]
      this.loaded = true
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
    }
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
