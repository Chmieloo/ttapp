<template>
  <div>
    <component :is="homePlayerComponent"></component>
    <component :is="awayPlayerComponent" v-bind:testProp="testProp"></component>
    <div class=”ToDoItem-Delete” @click="deleteItem()">-</div>
  </div>
</template>

<script>
import HomePlayer from './HomePlayer.vue'
import AwayPlayer from './AwayPlayer.vue'

export default {
  components: {
    HomePlayer,
    AwayPlayer
  },
  data () {
    return {
      homePlayerComponent: null,
      awayPlayerComponent: null,
      tempComponent: null,
      testProp: 12355333
    }
  },
  mounted () {
    this.homePlayerComponent = HomePlayer
    this.awayPlayerComponent = AwayPlayer
  },
  methods: {
    deleteItem () {
      this.tempComponent = this.homePlayerComponent
      this.homePlayerComponent = this.awayPlayerComponent
      this.awayPlayerComponent = this.tempComponent
    }
  }
}
</script>
