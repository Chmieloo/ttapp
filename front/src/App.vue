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

button {
  margin-top: 20px;
  font-size: 16px;
  padding: 10px 20px;
  text-transform: lowercase;
  font-weight: 600;
  background: #9e9e9e;
  color: #fff;
  border: none;
  border-radius: 8px;
}

input {
  border: none;
  border-radius: 3px;
  font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;
  font-size: 16px;
  padding: 8px;
  font-weight: 400;
  color: #000;
}

.mainContainer {
  padding: 20px 25px;
}

.header-title {
  color: white;
  font-size: 30px;
  font-weight: 600;
  text-transform: uppercase;
}

.header-icon {
  color: white;
  font-size: 20px;
  padding-right: 15px;
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
.padt10 {padding-top: 10px;}

.mart10 {margin-top: 10px;}

#app {
  font-family: 'Poppins', 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
