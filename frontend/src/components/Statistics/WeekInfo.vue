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
                            <td style="font-size: 80px; color: white;">{{ data.gamesCount }}</td>
                            <td style="font-size: 20px;">
                                <div>games</div>
                                <div>played</div>
                            </td>
                            <td style="font-size: 80px; color: white; padding-left: 20px;">{{ data.playersCount }}</td>
                            <td style="font-size: 20px;">
                                <div>unique</div>
                                <div>players</div>
                            </td>
                            <td style="font-size: 80px; color: white; padding-left: 20px;">{{ data.setsCount }}</td>
                            <td style="font-size: 20px;">
                                <div>sets</div>
                                <div>played</div>
                            </td>
                        </tr>
                        <tr>
                          <td colspan="6" style="font-size: 20px;">
                            with total <span style="color: white; font-size: 150px;">{{ data.totalPoints }}</span> points !!!
                          </td>
                        </tr>
                    </table>
                    <div style="text-align: center; padding: 40px 0px; font-size: 25px;">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                </td>
                <td style="padding-left: 20px;">
                  <table>
                    <tr>
                      <td>
                        <GChart type="PieChart" :data="pieChartData" :options="pieChartOptions" style="width: 300px; height: 300px;" />
                      </td>
                      <td>
                        <div><span style="color: white; font-size: 40px;">{{ this.data.score30 }}</span> 3:0 scores</div>
                        <div><span style="color: white; font-size: 40px;">{{ this.data.score31 }}</span> 3:1 scores</div>
                        <div><span style="color: white; font-size: 40px;">{{ this.data.score22 }}</span> 2:2 scores</div>
                      </td>
                    </tr>
                  </table>
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
import Vue from 'vue'
import VueGoogleCharts from 'vue-google-charts'
import VueLocalStorage from 'vue-localstorage'

Vue.use(VueGoogleCharts)
Vue.use(VueLocalStorage, {
  name: 'localStorage',
  bind: true
})

export default {
  name: 'App',
  data () {
    return {
      data: [],
      officeId: 1,
      pieChartData: null,
      pieChartOptions: {
        backgroundColor: '#02252e',
        chartArea: {
          backgroundColor: '#0e3c46',
          height: '300px;',
          width: '500px;'
        },
        width: '500px',
        legend: 'none',
        pieSliceText: 'label',
        pieSliceBorderColor: '#02252e',
        pieStartAngle: 45,
        pieSliceTextStyle: {
          color: 'white',
          fontName: 'Notable',
          fontSize: 18
        },
        slices: [
          {
            color: '#105869',
            offset: 0
          },
          {
            color: '#355f69',
            offset: 0
          },
          {
            color: '#0a3944',
            offset: 0
          }
        ],
        pieHole: 0.4
      }
    }
  },
  mounted () {
    axios.get('/api/tournaments/statistics/week').then((res) => {
      this.officeId = parseInt(this.$localStorage.get('ttappOfficeId', 1))
      this.data = res.data[this.officeId]
      this.pieChartData = [
        ['type', 'count'],
        ['3:0', this.data.score30],
        ['3:1', this.data.score31],
        ['2:2', this.data.score22]
      ]
    })
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
