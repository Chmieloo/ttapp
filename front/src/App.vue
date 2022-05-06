<template>
  <div id="app">
    <TopMenu />
    <router-view :key="$route.name + ($route.params.id || '')" />
  </div>
</template>

<script>
import TopMenu from "@/components/TopMenu.vue";

export default {
  name: "App",
  components: {
    TopMenu,
  },
  mounted() {
    if (localStorage.getItem("ttappOfficeId") === null) {
      localStorage.setItem("ttappOfficeId", "1");
    }
  },
};
</script>

<style lang="less">
body {
  background: #02252e;
  padding: 0;
  margin: 0;
  color: #9e9e9e;
}

a {
  color: white;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.cell-options {
  a {
    text-decoration: none;
    color: #9e9e9e;
  }

  a:hover {
    color: white;
  }
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

.row-header {
  font-size: 20px;
  color: #dedede;
  height: 35px;
  line-height: 35px;
}

.row-data {
  height: 35px;
  line-height: 35px;
  border-top: 1px solid #2d2d2d;
}

input {
  border: none;
  border-radius: 3px;
  font-family: "Montserrat", sans-serif;
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

.txt-left {
  text-align: left;
}

.txt-right {
  text-align: right;
}

.txt-center {
  text-align: center;
}

.mw-100 {
  min-width: 100px;
}

.w50pc {
  width: 50%;
  vertical-align: top;
}

.padr10 {
  padding-right: 10px;
}

.padt10 {
  padding-top: 10px;
}

.mart10 {
  margin-top: 10px;
}

#app {
  font-family: "Nunito", "Poppins", "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  overflow: hidden;
}
</style>
