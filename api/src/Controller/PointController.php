<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameMode;
use App\Entity\Player;
use App\Entity\Points;
use App\Entity\Scores;
use App\Entity\Tournament;
use App\Entity\TournamentGroup;
use App\Repository\GameRepository;
use App\Repository\PointsRepository;
use App\Repository\ScoresRepository;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PointController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addPoint(Request $request)
    {
        $em = $this->manager;
        $data = json_decode($request->getContent(), true);

        var_dump($data);

        /** @var GameRepository $matchRepository */
        $matchRepository = $this->manager->getRepository(Game::class);
        /** @var ScoresRepository $scoreRepository */
        $scoreRepository = $this->manager->getRepository(Scores::class);

        $home = $data['home'];
        $away = $data['away'];
        $matchId = $data['matchId'];

        $match = $matchRepository->find($matchId);

        if ($match->getIsFinished()) {
            return new JsonResponse([
                'status' => 'Match finished.'
            ],
                JsonResponse::HTTP_OK
            );
        }

        # Get current set number, if it is not set, add another score object to match
        # and point object to that score
        if (!count($match->getScores())) {
            $score = new Scores();
            $score->setGame($match);
            $score->setSetNumber(1);
            $score->setHomePoints(0);
            $score->setAwayPoints(0);
            $em->persist($score);
            $em->flush();

            $match->setCurrentSet(1);
            $em->persist($match);
            $em->flush();
        }

        $scoreId = $scoreRepository->getScoreIdByMatchIdAndSetNumber($matchId, $match->getCurrentSet());

        /** @var Scores $score */
        $score = $em->getRepository(Scores::class)->find($scoreId);

        $now = new \DateTime(null, new \DateTimeZone('Europe/Berlin'));
        $now->format('Y-m-d H:i:s');

        $point = new Points();
        $point->setIsAwayPoint($away);
        $point->setIsHomePoint($home);
        $point->setScore($score);
        $point->setTime($now);
        $em->persist($point);
        $em->flush();

        $serverData = $em->getRepository(Game::class)->getCurrentServerData($matchId);
        if ($serverData) {
            $currentServer = $serverData['currentServerId'];
            $numServes = $serverData['numServes'];
        }

        return new JsonResponse([
            'text' => 'Point added',
            'currentSet' => $match->getCurrentSet(),
            'currentServerId' => $currentServer ?? 0,
            'currentNumServes' => $numServes ?? 0,
        ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deletePoint(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        /** @var PointsRepository $pointRepository */
        $pointRepository = $this->manager->getRepository(Points::class);

        $home = $data['home'];
        $away = $data['away'];
        $matchId = $data['matchId'];

        # Delete last point (home / away) for given match id
        $pointRepository->removeLastPoint($home, $away, $matchId);

        $manager = $this->manager;
        $serverData = $manager->getRepository(Game::class)->getCurrentServerData($matchId);
        if ($serverData) {
            $currentServer = $serverData['currentServerId'];
            $numServes = $serverData['numServes'];
        }

        return new JsonResponse([
            'status'    => 'done',
            'errorText' => 'Point removed',
            'currentServerId' => $currentServer ?? 0,
            'currentNumServes' => $numServes ?? 0,
        ],
            JsonResponse::HTTP_OK
        );
    }

}
