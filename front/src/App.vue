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

h1 {
  color: white;
}

.txt-left {text-align: left;}
.txt-right {text-align: right;}
.txt-center {text-align: center;}

.mw-100 {min-width: 100px;}

.w50pc {
  width: 50%;
  vertical-align: top;
}

.padr10 {padding-right: 10px;}

#app {
  font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
