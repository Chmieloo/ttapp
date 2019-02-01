<template>
  <div id="app">
        <div class="mainContainer">
            <div style="margin-bottom: 20px;">
                <span class="header-title">
                    Standings
                </span>
                <span v-for="group in groups" v-bind:key="group.groupId" class="header-title-abbs">
                    <a href="#" v-scroll-to="'#group-' + group.groupId">{{ group.groupAbbreviation }}</a> .
                </span>
            </div>
            <div v-for="group in groups" v-bind:key="group.groupId" class="group-container" :id="'group-' + group.groupId">
                <div class="group-header">
                    <span>
                        {{ group.groupName }}
                    </span>
                    <span class="anchor-top">
                        <a href="#" v-scroll-to="'#app'">
                            TOP <span class="header-icon"><i class="fas fa-arrow-circle-up"></i></span>
                        </a>
                    </span>
                </div>
                <div class="group-body">
                    <table>
                        <tr class="group-row-header">
                            <th class="txt-left">name</th>
                            <th class="txt-center">played</th>
                            <th class="txt-center">wins</th>
                            <th class="txt-center">draws</th>
                            <th class="txt-center">losses</th>
                            <th class="txt-center">sets</th>
                            <th class="txt-center">+/-</th>
                            <th class="txt-center">rallies</th>
                            <th class="txt-center">+/-</th>
                            <th class="txt-center">points</th>
                        </tr>
                        <tr v-for="player in group.players" v-bind:key="player.playerId" class="grsoup-container">
                            <td>{{ player.playerName }}</td>
                            <td class="txt-center">{{ player.played }}</td>
                            <td class="txt-center">{{ player.wins }}</td>
                            <td class="txt-center">{{ player.draws }}</td>
                            <td class="txt-center">{{ player.losses }}</td>
                            <td class="txt-center">-</td>
                            <td class="txt-center">-</td>
                            <td class="txt-center">-</td>
                            <td class="txt-center">-</td>
                            <td class="txt-center">{{ player.points }}</td>
                        </tr>
                    </table>
                </div>
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
      groups: []
    }
  },
  mounted () {
    axios.get('/api/tournaments/' + this.$route.params.id + '/standings').then((res) => {
      this.groups = res.data
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">
.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 40px;
  margin-right: 40px;
}

.header-title-abbs {
  color: #3e3e3e;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 40px;
  a {
      color: #3e3e3e;
      text-decoration: none;
  }
  a:hover {
      color: white;
  }
}

.anchor-top {
    float: right;
    a {
        color: white;
        text-decoration: none;
    }
    a:hover {
        color: white;
    }
}

.group-container {
    width: 100%;
    margin-bottom: 25px;
}

.group-header {
    background: rgb(41, 137, 136);
    padding: 10px 20px;
    color: white;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 300;
    font-size: 18px;
}

.group-row-header {
    text-transform: uppercase;
    :first-child {
        width: 250px;
    }
}

.group-body {
    color: white;
    background-color: #1a1a1a;
    padding: 10px 20px;
    table {
        width: 100%;
    }
}

.table-player-list {
  border-collapse: collapse;
  margin-top: 20px;
  width: 100%;
}

.player-row {
  height: 40px;
  line-height: 30px;
  border-bottom: 1px solid #2d2d2d;
}
</style>
