<template>
  <div class="mainContainer">
    <table class="mainTable">
      <tr>
        <td class="w50pc padr10">
          <h1>Today's schedule</h1>
          <div class="greyContainer">
            <table class="schedule">
              <tr>
                <td>09:30</td>
                <td>Conference D</td>
                <td>John Doe</td>
                <td>-</td>
                <td>Jim Beam</td>
              </tr>
              <tr>
                <td>10:00</td>
                <td>Premier League</td>
                <td>Mike Johnson</td>
                <td>-</td>
                <td>Billy Bob</td>
              </tr>
            </table>
          </div>
        </td>
        <td class="w50pc padr10">
          <h1>Today's results</h1>
          <div class="greyContainer">
            <table class="results">
              <tr>
                <td>Mike Doe</td>
                <td>-</td>
                <td>Jerry Jackson</td>
                <td>3 - 1 (11-5, 11-4, 3-11, 13-11)</td>
              </tr>
              <tr>
                <td>Bobby Brown</td>
                <td>-</td>
                <td>Tom Black</td>
                <td>2 - 2 (11-5, 11-4, 3-11, 3-11)</td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',
  data () {
    return {
      msg: "Today's schedule"
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mainContainer {
  padding: 0px 20px 0px 20px;
}

.mainTable {
  width: 100%;
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
