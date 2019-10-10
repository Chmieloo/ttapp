<template>
  <div id="app">
    <div class="mainContainer">
        <table class="top-banner">
            <tr>
                <td class="top-banner-left">
                    Last week's
                </td>
                <td class="top-banner-right">
                    summary
                </td>
            </tr>
        </table>
        <table style="width: 90%; margin: 30px auto;">
            <tr>
                <td style="border-right: 5px dotted white; width: 50%; height: 200px; vertical-align: top;">
                    <table>
                        <tr>
                            <td style="font-size: 80px; color: white;">28</td>
                            <td style="font-size: 20px;">
                                <div>games</div>
                                <div>played</div>
                            </td>
                            <td style="font-size: 80px; color: white; padding-left: 20px;">12</td>
                            <td style="font-size: 20px;">
                                <div>unique</div>
                                <div>players</div>
                            </td>
                            <td style="font-size: 80px; color: white; padding-left: 20px;">57</td>
                            <td style="font-size: 20px;">
                                <div>sets</div>
                                <div>played</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding-left: 20px;">
                    <table>
                        <tr>
                            <td>
                                <span style="font-size: 100px; color: white;" class="fa-stack">
                                    <i class="fas fa-rocket fa-stack-1x"></i>
                                    <i class="far fa-comment fa-stack-1x"></i>
                                </span>
                            </td>
                            <td style="padding-left: 20px;">
                                <div style="font-size: 60px;">+286 ELO</div>
                                <div style="font-size: 30px;">JOHN JOHNSON</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import _ from 'lodash'

export default {
  name: 'App',
  data () {
    return {
      players: [],
      eloOrder: 'asc',
      eloChangeOrder: 'asc',
      nameOrder: 'desc',
      gamesPlayedOrder: 'asc',
      winOrder: 'asc',
      officeCookieId: 1
    }
  },
  mounted () {
    axios.get('/api/players').then((res) => {
      this.players = res.data
      this.eloSort()
      this.officeCookieId = parseInt(this.$cookie.get('officeId'))
    })
  },
  computed: {
    filteredPlayers: function () {
      return this.players.filter((player) => {
        return parseInt(player.officeId) === this.officeCookieId
      })
    }
  },
  methods: {
    eloChangeSort () {
      if (this.eloChangeOrder === 'desc') {
        this.eloChangeOrder = 'asc'
      } else {
        this.eloChangeOrder = 'desc'
      }
      this.players = _.orderBy(this.players, 'eloChange', this.eloChangeOrder)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainContainer {
  padding: 20px 25px;
  font-family: 'Notable', sans-serif;
}

.top-banner {
    font-size: 70px;
    width: 90%;
    margin: 0px auto;
    text-align: center;
    margin-top: 50px !important;
}

.top-banner-left {
    width: 50%;
    text-transform: uppercase;
    text-align: right;
    padding: 20px;
    background-color: #105869;
    color: #02252e;
    padding-bottom: 30px;
}

.top-banner-right {
    width: 50%;
    text-transform: uppercase;
    text-align: left;
    padding: 20px;
    color: #fff;
    padding-bottom: 30px;
}
</style>
