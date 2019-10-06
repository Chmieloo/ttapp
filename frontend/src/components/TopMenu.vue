<template>
  <div class="topMenu">
    <div><router-link to="/">ttapp</router-link></div>
    <div><router-link to="/player/list">players</router-link></div>
    <div><router-link to="/tournament/list">tournaments</router-link></div>
    <div><router-link to="/leaders">leaders</router-link></div>
    <div>
      <div class="dropdown-office"><span @click="toggleDropdown()">OFFICE</span></div>
      <div class="dropdown" v-if="dropdown">
        <div class="dropdown-item" v-for="office in this.offices" v-bind:key="office.id">
          <div @click="setStorageOfficeId(office.id)">
            <i v-if="office.id == officeId" class="far fa-check-square"></i>
            {{ office.name }}
          </div>
        </div>
      </div>
    </div>
    <div style="float: right;">
      <div><router-link to="/updates">updates</router-link></div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import VueLocalStorage from 'vue-localstorage'

Vue.use(VueLocalStorage, {
  name: 'localStorage',
  bind: true
})

export default {
  name: 'TopMenu',
  data () {
    return {
      dropdown: false,
      offices: [],
      officeId: 1
    }
  },
  mounted () {
    axios.get('/api/offices').then((res) => {
      this.offices = res.data
      this.officeId = this.$localStorage.get('ttappOfficeId', 1)
    })
  },
  methods: {
    setStorageOfficeId (officeId) {
      this.$localStorage.set('ttappOfficeId', officeId)
      this.$router.go(0)
    },
    toggleDropdown () {
      this.dropdown = !this.dropdown
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="less" scoped>
.topMenu {
    background-color: #105869;
    clear: both;
    height: 50px;
    line-height: 50px;
    padding-left: 15px;
    font-weight: 600;
    box-shadow: 0px 0px 3px black;
    width: 100%;
    .dropdown {
      position: absolute;
      margin-top: 50px;
      width: 250px;
      background-color: #003e4c;
      box-shadow: 0px 1px 1px 0px black;
      margin-left: -20px;
      div {
        float: none;
        margin: 0px !important;
        padding: 5px 10px;
      }
    }
}

.dropdown-item {
  cursor: pointer;
  color: #fff;
}

.dropdown-item:hover {
  background-color: #105869;
}

.dropdown-office {
  color: white;
  cursor: pointer;
}

.topMenu div {
    float: left;
    margin-right: 40px;
    text-transform: uppercase;
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
