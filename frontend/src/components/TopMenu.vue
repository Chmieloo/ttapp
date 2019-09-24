<template>
  <div class="topMenu">
    <div><router-link to="/">ttapp</router-link></div>
    <div><router-link to="/player/list">players</router-link></div>
    <div><router-link to="/tournament/list">tournaments</router-link></div>
    <div><router-link to="/leaders">leaders</router-link></div>
  </div>
</template>

<script>
export default {
  name: 'TopMenu'
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.topMenu {
    background-color: #105869;
    clear: both;
    height: 50px;
    line-height: 50px;
    padding-left: 15px;
    font-weight: 600;
    box-shadow: 0px 0px 3px black;

}

.topMenu div {
    float: left;
    margin-right: 40px;
      text-transform: uppercase
}

.topMenu div:first-child a {
    color: white;
}

a {
  color: #fff;
  text-decoration: none;
}

a:hover {
    color: #818181;
}
</style>
