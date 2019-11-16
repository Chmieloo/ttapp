import Vue from 'vue'
import Router from 'vue-router'
import VueScrollTo from 'vue-scrollto'
import IndexPage from '@/components/IndexPage'
import PlayerList from '@/components/Player/List'
import Leaders from '@/components/Player/Leaders'
import PlayoffsInfo from '@/components/Tournament/PlayoffsInfo'
import TournamentList from '@/components/Tournament/List'
import TournamentStandings from '@/components/Tournament/Standings'
import TournamentMatchList from '@/components/Tournament/MatchList'
import MatchView from '@/components/Match/View'
import MatchPlayoffView from '@/components/Match/PlayoffView'
import MatchSpectate from '@/components/Match/Spectate'
import MatchSummary from '@/components/Match/Summary'
import MatchNew from '@/components/Match/New'
import Updates from '@/components/Updates'
import TournamentResultsEdit from '@/components/Tournament/ResultsEdit'
import PlayerInfo from '@/components/Player/Info'
import WeekInfo from '@/components/Statistics/WeekInfo'
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
    { path: '/updates', name: 'Updates', component: Updates },
    { path: '/player/list', name: 'PlayerList', component: PlayerList },
    { path: '/leaders', name: 'Leaders', component: Leaders },
    { path: '/tournament/list', name: 'TournamentList', component: TournamentList },
    { path: '/tournament/:id/standings', name: 'TournamentStandings', component: TournamentStandings },
    { path: '/tournament/:id/match/list', name: 'TournamentMatchList', component: TournamentMatchList },
    { path: '/match/new', name: 'MatchNew', component: MatchNew },
    { path: '/tournament/:id/results/edit', name: 'TournamentResultsEdit', component: TournamentResultsEdit },
    { path: '/match/:id/view', name: 'MatchView', component: MatchView },
    { path: '/match/playoff/:id/view', name: 'MatchPlayoffView', component: MatchPlayoffView },
    { path: '/match/:id/spectate', name: 'MatchSpectate', component: MatchSpectate },
    { path: '/match/:id/summary', name: 'MatchSummary', component: MatchSummary },
    { path: '/player/:id/info', name: 'PlayerInfo', component: PlayerInfo },
    { path: '/statistics/week', name: 'WeekInfo', component: WeekInfo },
    { path: '/quickmatches', name: 'QuickMatchList', component: QuickMatchList },
    // playoffs
    { path: '/playoffs/match/list', name: 'PlayoffsMatchList', component: PlayoffsMatchList },
    { path: '/playoffs/group/:id/ladder', name: 'PlayoffsLadder', component: PlayoffsLadder },
    { path: '/playoffs/:id/ladders', name: 'Ladders', component: Ladders },
    { path: '/playoffs/:id/info', name: 'PlayoffsInfo', component: PlayoffsInfo }
  ]
})
