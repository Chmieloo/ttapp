<template>
  <div class="mainContainer">
    <div v-bind:class="flipped ? 'container-fr' : 'container-fl'">
      <div>
        <span class="header-title">{{ match.homePlayerDisplayName }}</span>
        <div class="rallyScore">
          {{ homeScore }}
        </div>
      </div>
    </div>
    <div v-bind:class="flipped ? 'container-fl' : 'container-fr'">
      <div>
        <span class="header-title">{{ match.awayPlayerDisplayName }}</span>
        <div class="rallyScore">
          {{ awayScore }}
        </div>
      </div>
    </div>
    <div class="container-mid">
      <div class="midInfoHeader">MATCH MODE</div>
      <div class="midInfoValue">best of 4</div>
      <div class="midInfoHeader">SET SCORE</div>
      <div class="midInfoValue">
        <span>{{ match.homeScoreTotal }}</span>
        <span>:</span>
        <span>{{ match.awayScoreTotal }}</span>
      </div>
    </div>
    <div style="clear: both;"></div>
    <div style="display: none;">
      <button v-gamepad:button-y="addPointLeft">Press me!</button>
      <button v-gamepad:button-a="addPointRight">Press me!</button>
      <button v-gamepad:button-x="subPointLeft">Press me!</button>
      <button v-gamepad:button-b="subPointRight">Press me!</button>
      <button v-gamepad:shoulder-left="flipSides">Press me!</button>
      <button v-gamepad:shoulder-right="test">Press me!</button>
    </div>
    <div v-if="isConnected()">
      <i class="fas fa-gamepad pad"></i>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueGamepad from 'vue-gamepad'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'

// import CustomView from './Custom/CustomDialog.vue';

// const VIEW_NAME = 'custom-comonent';
// Vue.dialog.registerComponent(VIEW_NAME, CustomView);

Vue.use(VuejsDialog)
Vue.use(VueGamepad)

export default {
  components: {
    VueGamepad
  },
  data () {
    return {
      match: [],
      flipped: 0,
      gamepadConnected: 0,
      homeScore: 0,
      awayScore: 0
    }
  },
  mounted () {
    axios.get('/api/matches/' + this.$route.params.id).then((res) => {
      this.match = res.data
    })
  },
  created: function () {
    // window.addEventListener('gamepadconnected', this.handleGamepadConnect)
    // window.addEventListener('gamepaddisconnected', this.handleGamepadDisconnect)
  },
  methods: {
    // logic to check if set is fninished
    isFinishedSet () {
      if (this.homeScore >= 11 || this.awayScore >= 11) {
        if (Math.abs(this.homeScore - this.awayScore) < 2) {
          return false
        } else {
          return true
        }
      } else {
        return false
      }
    },
    flipSides () {
      this.flipped = (this.flipped + 1) % 2
    },
    test () {
      this.$dialog
        .confirm('Please confirm to continue')
        .then(function (dialog) {
          console.log('Clicked on proceed')
        })
        .catch(function () {
          console.log('Clicked on cancel')
        })
    },
    addPointLeft () {
      if (this.flipped) {
        this.awayScore++
      } else {
        this.homeScore++
      }
      if (this.isFinishedSet()) {
        this.flipSides()
        this.homeScore = 0
        this.awayScore = 0
      }
    },
    subPointLeft () {
      if (this.flipped) {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
      } else {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
      }
    },
    addPointRight () {
      if (this.flipped) {
        this.homeScore++
      } else {
        this.awayScore++
      }
      if (this.isFinishedSet()) {
        this.flipSides()
        this.homeScore = 0
        this.awayScore = 0
      }
    },
    subPointRight () {
      if (this.flipped) {
        this.homeScore = (this.homeScore - 1) < 0 ? this.homeScore : --this.homeScore
      } else {
        this.awayScore = (this.awayScore - 1) < 0 ? this.awayScore : --this.awayScore
      }
    },
    isConnected () {
      var body = document.getElementsByTagName('body')
      return body[0].classList.contains('gamepad-connected')
    }
  }
}
</script>

<style lang="less" scoped>
.mainContainer {
  margin-top: 30px;
}

.infoPanel {
  width: 100%;
  padding: 5px 20px;
  background-color: #222;
}

.pad {
  color: #666;
  font-size: 40px;
  position: absolute;
  top: 0px;
  right: 10px;
  line-height: 50px;
}

.container-fl {
  float: left;
  width: 40%;
  text-align: center;
  border-right: 1px solid #222;
}

.container-fr {
  float: right;
  width: 40%;
  text-align: center;
  border-left: 1px solid #222;
}

.container-mid {
  margin-left: auto;
  margin-right: auto;
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

.rallyScore {
  width: 95%;
  font-size: 400px;
  line-height: 400px;
  font-weight: 900;
  color: white;
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
