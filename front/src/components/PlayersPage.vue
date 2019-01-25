<template>
  <div id="app">
    <pre>{{ res }}</pre>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'App',
  data () {
    return {
      res: null
    }
  },
  mounted () {
    axios.get('/api/players').then((res) => {
      this.res = res
    })
  }
}
</script>
