import { createRouter, createWebHistory } from "vue-router";

import IndexPage from "@/components/IndexPage.vue";
import PlayerList from "@/components/Player/List.vue";
import Leaders from "@/components/Player/Leaders.vue";
import PlayoffsInfo from "@/components/Tournament/PlayoffsInfo.vue";
import TournamentList from "@/components/Tournament/List.vue";
import TournamentStandings from "@/components/Tournament/Standings.vue";
import TournamentMatchList from "@/components/Tournament/MatchList.vue";
import MatchView from "@/components/Match/TheView.vue";
import MatchPlayoffView from "@/components/Match/PlayoffView.vue";
import MatchSpectate from "@/components/Match/TheSpectate.vue";
import MatchSummary from "@/components/Match/TheSummary.vue";
import TheUpdates from "@/components/TheUpdates.vue";
import TournamentResultsEdit from "@/components/Tournament/ResultsEdit.vue";
import PlayerInfo from "@/components/Player/Info.vue";
import WeekInfo from "@/components/Statistics/WeekInfo.vue";
// Quick Play
import QuickMatchList from "@/components/QuickMatch/List.vue";
// Playoffs
import PlayoffsMatchList from "@/components/Tournament/PlayoffsMatchList.vue";
import PlayoffsLadder from "@/components/Tournament/PlayoffsLadder.vue";
import Ladders from "@/components/Tournament/Ladders.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: "/", name: "IndexPage", component: IndexPage },
    { path: "/updates", name: "TheUpdates", component: TheUpdates },
    { path: "/player/list", name: "PlayerList", component: PlayerList },
    { path: "/leaders", name: "Leaders", component: Leaders },
    {
      path: "/tournament/list",
      name: "TournamentList",
      component: TournamentList,
    },
    {
      path: "/tournament/:id/standings",
      name: "TournamentStandings",
      component: TournamentStandings,
    },
    {
      path: "/tournament/:id/match/list",
      name: "TournamentMatchList",
      component: TournamentMatchList,
    },
    {
      path: "/tournament/:id/results/edit",
      name: "TournamentResultsEdit",
      component: TournamentResultsEdit,
    },
    { path: "/match/:id/view", name: "MatchView", component: MatchView },
    {
      path: "/match/playoff/:id/view",
      name: "MatchPlayoffView",
      component: MatchPlayoffView,
    },
    {
      path: "/match/:id/spectate",
      name: "MatchSpectate",
      component: MatchSpectate,
    },
    {
      path: "/match/:id/summary",
      name: "MatchSummary",
      component: MatchSummary,
    },
    { path: "/player/:id/info", name: "PlayerInfo", component: PlayerInfo },
    { path: "/statistics/week", name: "WeekInfo", component: WeekInfo },
    {
      path: "/quickmatches",
      name: "QuickMatchList",
      component: QuickMatchList,
    },
    // playoffs
    {
      path: "/playoffs/match/list",
      name: "PlayoffsMatchList",
      component: PlayoffsMatchList,
    },
    {
      path: "/playoffs/group/:id/ladder",
      name: "PlayoffsLadder",
      component: PlayoffsLadder,
    },
    { path: "/playoffs/:id/ladders", name: "Ladders", component: Ladders },
    {
      path: "/playoffs/:id/info",
      name: "PlayoffsInfo",
      component: PlayoffsInfo,
    },
  ],
});

export default router;
