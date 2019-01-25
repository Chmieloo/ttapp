<template>
 <form class="" method="post" @submit.prevent="postNow">
 <input type="text" name="" value="" v-model="name">
 <button type="submit" name="button">Submit</button>
 </form>
</template>

<script>
import axios from 'axios'

export default {
  name: 'formPost',
  data () {
    return {
      name: '',
      show: false
    }
  },
  methods: {
    postNow () {
      axios.post('/api/players/add', {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        },
        name: this.name
      })
    }
  }
}
</script>
