<template>
  <div class="mainContainer">
    <div class="greyContainer">
      <div class="title">Today's schedule</div>
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
      <hr />
      <div class="title">Today's results</div>
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
    
    <h1>{{ msg }}</h1>
    <h2>Essential Links</h2>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mainContainer {
  padding: 25px 20px;
}

.greyContainer {
  max-width: 700px;
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

.schedule tr td:first-child {min-width: 50px;}
.schedule tr td:nth-child(2) {min-width: 140px;}
.schedule tr td:nth-child(3) {min-width: 200px;}
.schedule tr td:nth-child(4) {min-width: 30px; text-align: center;}
.schedule tr td:nth-child(5) {min-width: 200px;}

.results tr td:nth-child(4) {padding-left: 30px;}

</style>
