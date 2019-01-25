import Vue from 'vue'
import Router from 'vue-router'
import IndexPage from '@/components/IndexPage'
import PlayerList from '@/components/Player/List'
import PlayerAdd from '@/components/Player/Add'

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', name: 'IndexPage', component: IndexPage },
    { path: '/player/list', name: 'PlayerList', component: PlayerList },
    { path: '/player/add', name: 'PlayerAdd', component: PlayerAdd }
  ]
})
