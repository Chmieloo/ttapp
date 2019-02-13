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
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $home = $data['home'];
        $away = $data['away'];
        $matchId = $data['matchId'];

        $setNumber = $this->getCurrentMatchSetNumber($matchId);

        $scoreId = $this->getDoctrine()
            ->getRepository(Scores::class)
            ->getScoreIdByMatchIdAndSetNumber($matchId, $setNumber);

        var_dump($scoreId);

        /** @var Scores $score */
        $score = $doctrine->getRepository(Scores::class)
            ->find($scoreId);

        $now = new \DateTime(null, new \DateTimeZone('Europe/Berlin'));
        $now->format('Y-m-d H:i:s');

        $point = new Points();
        $point->setIsAwayPoint($away);
        $point->setIsHomePoint($home);
        $point->setScore($score);
        $point->setTime($now);
        $em->persist($point);
        $em->flush();

        return new JsonResponse([
            'status'    => 'done',
            'errorText' => 'Point added'
        ],
            JsonResponse::HTTP_OK
        );
    }

    private function getCurrentMatchSetNumber($matchId)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        /** @var Game $match */
        $match = $this->getDoctrine()->getRepository(Game::class)->find($matchId);

        $scoreId = null;

        if (!$match->getScores()->count()) {
            $score = new Scores();
            $score->setGame($match);
            $score->setSetNumber(1);
            $score->setHomePoints(0);
            $score->setAwayPoints(0);
            $em->persist($score);
            $em->flush();

            $setId = 1;
        } else {
            $setId = $match->getScores()->count();
        }

        return $setId;
    }
}
