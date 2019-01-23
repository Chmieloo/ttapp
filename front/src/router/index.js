import Vue from 'vue'
import Router from 'vue-router'
import IndexPage from '@/components/IndexPage'
import PlayersPage from '@/components/PlayersPage'

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', name: 'IndexPage', component: IndexPage },
    { path: '/players', name: 'PlayersPage', component: PlayersPage }
  ]
})
