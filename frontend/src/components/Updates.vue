<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-title">Update history</span>
      <div style="margin-top: 20px;">
        <div style="margin-bottom: 10px;">2019-09-30</div>
        <div style="border-left: 3px solid #8a8a8a; padding: 20px;">
          <span style="color: white;">Bug fixes</span>
          <ul>
            <li>Fixed bug when spectate mode shows wrong score</li>
            <li>Match summary link is now visible in 'full tournament results' section <router-link :to="'/tournament/0/match/list'">view</router-link></li>
          </ul>
          <span style="color: white;">New features</span>
          <ul>
            <li>Changed slack message format for started and finished matches</li>
            <li>Various changes to match summary view (scored with gamepad):</li>
            <li style="list-style-type: none;">added more statistics to match summary view: serve stats, point stats,</li>
            <li style="list-style-type: none;">changed set summary data to more readable format,</li>
            <li style="list-style-type: none;">hovering over scored point in summary view shows rally length</li>
            <li style="list-style-type: none;"><router-link :to="'/match/334/summary'">example</router-link></li>
            <li>Added more statistics to leaders page <router-link :to="'/leaders'">view</router-link></li>
            <li>Added sorting by elo, games played and winning percentage to players page <router-link :to="'/player/list'">view</router-link></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.mainContainer {
  padding: 20px 25px;
}

.header-icon {
  color: white;
  font-size: 20px;
  padding-right: 15px;
}

.table-player-list {
  border-collapse: collapse;
  margin-top: 20px;
  width: 100%;
}

.row-header {
  font-size: 20px;;
}

.player-link {
  a {
    color: white;
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
}
</style>
