import Vue from 'vue'
import Router from 'vue-router'
import IndexPage from '@/components/IndexPage'
import PlayerList from '@/components/Player/List'
import PlayerAdd from '@/components/Player/Add'
import TournamentList from '@/components/Tournament/List'
import TournamentAdd from '@/components/Tournament/Add'
import GroupAdd from '@/components/Group/Add'

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', name: 'IndexPage', component: IndexPage },
    { path: '/player/list', name: 'PlayerList', component: PlayerList },
    { path: '/player/add', name: 'PlayerAdd', component: PlayerAdd },
    { path: '/tournament/list', name: 'TournamentList', component: TournamentList },
    { path: '/tournament/add', name: 'TournamentAdd', component: TournamentAdd },
    { path: '/group/add', name: 'GroupAdd', component: GroupAdd }
  ]
})
