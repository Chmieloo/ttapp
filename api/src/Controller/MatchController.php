<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameMode;
use App\Entity\Player;
use App\Entity\Tournament;
use App\Entity\TournamentGroup;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MatchController extends BaseController
{
    public function getAllMatches()
    {
        /** @var Tournament $activeTournament */
        $activeTournament = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->findNotFinished();

        $data = $this->getDoctrine()
            ->getRepository(Game::class)
            ->getByTournamentId($activeTournament->getId());

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCurrentTournamentResults()
    {
        /** @var Tournament $activeTournament */
        $activeTournament = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->findNotFinished();

        $data = $this->getDoctrine()
            ->getRepository(Game::class)
            ->getResultsByTournamentId($activeTournament->getId());

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCurrentTournamentSchedule()
    {
        /** @var Tournament $activeTournament */
        $activeTournament = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->findNotFinished();

        $data = $this->getDoctrine()
            ->getRepository(Game::class)
            ->getScheduleByTournamentId($activeTournament->getId());

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addMatch(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $tournamentId = $data['tournament'];
        $modeId = $data['mode'];
        $homePlayerId = $data['homePlayer'];
        $awayPlayerId = $data['awayPlayer'];

        $dateTime = $data['datetime'];
        $timestamp = strtotime($dateTime);
        $datetimeFormat = 'Y-m-d H:i:s';

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Berlin'));
        $date->setTimestamp($timestamp);
        $date->format($datetimeFormat);

        if (empty($dateTime)) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Choose date and time'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (empty($tournamentId) || empty($modeId)) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Choose tournament and match mode'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (empty($homePlayerId) || empty($awayPlayerId)) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Choose players'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if ($homePlayerId == $awayPlayerId) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Playing with oneself, huh?'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        /*
         * Check player groups
         * Get the tournament groups
         */

        /** @var Tournament $tournament */
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($tournamentId);

        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);
        $groupId = $gameRepository->validateTournamentGroupPlayer(
            $tournament->getId(),
            $homePlayerId,
            $awayPlayerId
        );

        if (!$groupId) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Those players are not in the same group'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        # All good
        $doctrine = $this->getDoctrine();

        /** @var GameMode $gameMode */
        $gameMode = $doctrine->getRepository(GameMode::class)->find($modeId);
        /** @var Player $homePlayer */
        $homePlayer = $doctrine->getRepository(Player::class)->find($homePlayerId);
        /** @var Player $awayPlayer */
        $awayPlayer = $doctrine->getRepository(Player::class)->find($awayPlayerId);
        /** @var TournamentGroup $group */
        $group = $doctrine->getRepository(TournamentGroup::class)->find($groupId);

        $em = $doctrine->getManager();

        $game = new Game();
        $game->setGameMode($gameMode);
        $game->setHomePlayer($homePlayer);
        $game->setAwayPlayer($awayPlayer);
        $game->setTournament($tournament);
        $game->setIsFinished(0);
        $game->setIsAbandoned(0);
        $game->setIsWalkover(0);
        $game->setTournamentGroup($group);
        $game->setWinnerId(0);
        $game->setHomeScore(0);
        $game->setAwayScore(0);
        $game->setDateOfMatch($date);

        $em->persist($game);
        $em->flush();

        return new JsonResponse([
            'status' => 'done',
            'errorText' => 'Match added'
        ],
            JsonResponse::HTTP_OK
        );
    }
}
