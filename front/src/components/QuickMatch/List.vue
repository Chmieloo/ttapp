<template>
  <div id="app">
    <div class="mainContainer">
      <span class="header-icon"><i class="fas fa-list"></i></span>
      <span class="header-title">Quick Play</span>
      <div></div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "App",
  data() {
    return {
      tournaments: null,
    };
  },
  mounted() {
    axios.get("/api/quickmatches").then((res) => {
      this.tournaments = res.data;
    });
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
}

.table-player-list {
  border-collapse: collapse;
  margin-top: 20px;
  width: 100%;
}

.cell-options {
  a {
    color: white;
  }
}
</style>
