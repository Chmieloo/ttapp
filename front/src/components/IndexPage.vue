<template>
  <div class="mainContainer">
    <div class="halfContainer">
      <span class="header-title">TODAY'S SCHEDULE</span>
      <div class="inContainer90">
        1
      </div>
    </div>
    <div class="halfContainer">
      <span class="header-title">TODAY'S RESULTS</span>
      <div class="inContainer90">
        <MatchResults />
      </div>
    </div>
    <div style="clear: both;" />
  </div>
</template>

<script>
import MatchResults from './Match/MatchResults.vue'

export default {
  name: 'IndexPage',
  components: {
    MatchResults
  },
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
}

.inContainer90 {
  margin-top: 20px;
  width: 95%;
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
