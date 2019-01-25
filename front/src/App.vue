<template>
  <div id="app">
    <TopMenu />
    <router-view/>
  </div>
</template>

<script>
import TopMenu from './components/TopMenu.vue'

export default {
  name: 'App',
  components: {
    TopMenu
  }
}
</script>

<style>
body {
  background: #1a1a1a;
  padding: 0;
  margin: 0;
  color: #9e9e9e;
}

#app {
  font-family: 'Roboto', 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
