<template>
  <div class="mainContainer">
    <div class="halfContainer">
      <span class="header-title">KARIUS</span>
      <div style="font-size: 50px; color: white; height: 100px;">
          <i class="fas fa-table-tennis"></i>
          <i class="fas fa-table-tennis"></i>
      </div>
      <div class="inContainer90">
        8
      </div>
    </div>
    <div class="halfContainer">
      <span class="header-title">BAKTUS</span>
      <div style="font-size: 50px; color: #1f1f1f; height: 100px;">
          <i class="fas fa-table-tennis"></i>
          <i class="fas fa-table-tennis"></i>
      </div>
      <div class="inContainer90">
        10
      </div>
    </div>
    <div style="clear: both;" />
  </div>
</template>

<script>
export default {
  name: 'MatchView',
  data () {
    return {
      msg: "Today's schedule"
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.halfContainer {
  float: left;
  width: 50%;
  margin-top: 40px;
  text-align: center;
}

.inContainer90 {
  width: 95%;
  font-size: 400px;
  font-weight: 900;
  color: white;
  margin-top: -100px;
}

.mainContainer {
  padding: 0px 20px 0px 20px;
}

.header-title {
  color: white;
  font-size: 30px;
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
