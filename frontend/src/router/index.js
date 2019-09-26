import Vue from 'vue'
import Router from 'vue-router'
import VueScrollTo from 'vue-scrollto'
import IndexPage from '@/components/IndexPage'
import PlayerList from '@/components/Player/List'
import Leaders from '@/components/Player/Leaders'
import PlayerAdd from '@/components/Player/Add'
import TournamentList from '@/components/Tournament/List'
import TournamentAdd from '@/components/Tournament/Add'
import TournamentStandings from '@/components/Tournament/Standings'
import TournamentMatchList from '@/components/Tournament/MatchList'
import GroupAdd from '@/components/Group/Add'
import MatchAdd from '@/components/Match/Add'
import MatchView from '@/components/Match/View'
import MatchPlayoffView from '@/components/Match/PlayoffView'
import MatchSpectate from '@/components/Match/Spectate'
import MatchSummary from '@/components/Match/Summary'
import MatchNew from '@/components/Match/New'
import TournamentResultsEdit from '@/components/Tournament/ResultsEdit'
import PlayerInfo from '@/components/Player/Info'
// Quick Play
import QuickMatchList from '@/components/QuickMatch/List'
// Playoffs
import PlayoffsMatchList from '@/components/Tournament/PlayoffsMatchList'
import PlayoffsLadder from '@/components/Tournament/PlayoffsLadder'
import Ladders from '@/components/Tournament/Ladders'

Vue.use(Router)
Vue.use(VueScrollTo)

export default new Router({
  routes: [
    { path: '/', name: 'IndexPage', component: IndexPage },
    { path: '/player/list', name: 'PlayerList', component: PlayerList },
    { path: '/leaders', name: 'Leaders', component: Leaders },
    { path: '/player/add', name: 'PlayerAdd', component: PlayerAdd },
    { path: '/tournament/list', name: 'TournamentList', component: TournamentList },
    { path: '/tournament/add', name: 'TournamentAdd', component: TournamentAdd },
    { path: '/tournament/:id/standings', name: 'TournamentStandings', component: TournamentStandings },
    { path: '/tournament/:id/match/list', name: 'TournamentMatchList', component: TournamentMatchList },
    { path: '/group/add', name: 'GroupAdd', component: GroupAdd },
    { path: '/match/add', name: 'MatchAdd', component: MatchAdd },
    { path: '/match/new', name: 'MatchNew', component: MatchNew },
    { path: '/tournament/:id/results/edit', name: 'TournamentResultsEdit', component: TournamentResultsEdit },
    { path: '/match/:id/view', name: 'MatchView', component: MatchView },
    { path: '/match/playoff/:id/view', name: 'MatchPlayoffView', component: MatchPlayoffView },
    { path: '/match/:id/spectate', name: 'MatchSpectate', component: MatchSpectate },
    { path: '/match/:id/summary', name: 'MatchSummary', component: MatchSummary },
    { path: '/player/:id/info', name: 'PlayerInfo', component: PlayerInfo },
    { path: '/quickmatches', name: 'QuickMatchList', component: QuickMatchList },
    // playoffs
    { path: '/playoffs/match/list', name: 'PlayoffsMatchList', component: PlayoffsMatchList },
    { path: '/playoffs/group/:id/ladder', name: 'PlayoffsLadder', component: PlayoffsLadder },
    { path: '/playoffs/:id/ladders', name: 'Ladders', component: Ladders }
  ]
})
