import Vue from 'vue'
import Router from 'vue-router'
import VueScrollTo from 'vue-scrollto'
import IndexPage from '@/components/IndexPage'
import PlayerList from '@/components/Player/List'
import PlayerAdd from '@/components/Player/Add'
import TournamentList from '@/components/Tournament/List'
import TournamentAdd from '@/components/Tournament/Add'
import TournamentStandings from '@/components/Tournament/Standings'
import GroupAdd from '@/components/Group/Add'
import MatchAdd from '@/components/Match/Add'
import MatchEdit from '@/components/Match/Edit'

Vue.use(Router)
Vue.use(VueScrollTo)

export default new Router({
  routes: [
    { path: '/', name: 'IndexPage', component: IndexPage },
    { path: '/player/list', name: 'PlayerList', component: PlayerList },
    { path: '/player/add', name: 'PlayerAdd', component: PlayerAdd },
    { path: '/tournament/list', name: 'TournamentList', component: TournamentList },
    { path: '/tournament/add', name: 'TournamentAdd', component: TournamentAdd },
    { path: '/tournament/:id/standings', name: 'TournamentStandings', component: TournamentStandings },
    { path: '/group/add', name: 'GroupAdd', component: GroupAdd },
    { path: '/match/add', name: 'MatchAdd', component: MatchAdd },
    { path: '/match/edit', name: 'MatchEdit', component: MatchEdit }
  ]
})
