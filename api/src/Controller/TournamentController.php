<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TournamentController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTournaments()
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);

        $tournaments = $tournamentRepository->loadList();

        if (!$tournaments) {
            throw $this->createNotFoundException(
                'No tournaments found'
            );
        }

        return $this->sendJsonResponse($tournaments);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function addTournament(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Fill the form'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!empty($data['name'])) {
            $em = $this->getDoctrine()->getManager();
            $tournament = new Tournament();
            $tournament->setName($data['name']);
            $tournament->setStartTime(\DateTime::createFromFormat('Y-m-d', $data['date']));
            $tournament->setIsFinished(0);
            $tournament->setIsPlayoffs(0);
            $em->persist($tournament);
            $em->flush();

            return new Response($tournament->getId());
        }
    }

    public function getStandings($id)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        $standings = $tournamentRepository->getStandingsByTournamentId($id);

        return $this->sendJsonResponse($standings);
    }

    /**
     * Gets results from active tournament
     * @param $tournamentId
     * @param $numberOfResults
     * @return Response
     */
    public function getTournamentResults($tournamentId, $numberOfResults)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentTournament();

        $data = $gameRepository->loadLastResultsByTournamentId($tournament->getId(), $numberOfResults);

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @param $numberOfFixtures
     * @return Response
     */
    public function getTournamentOverdueSchedule($tournamentId, $numberOfFixtures)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentTournament();

        $data = $gameRepository->loadOverdueFixturesByTournamentId($tournament->getId(), $numberOfFixtures);

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @param $numberOfFixtures
     * @return Response
     */
    public function getTournamentSchedule($tournamentId, $numberOfFixtures)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentTournament();

        $data = $gameRepository->loadUpcomingFixturesByTournamentId($tournament->getId(), $numberOfFixtures);

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @return Response
     */
    public function getTournamentMatchesFullfeed($tournamentId)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentTournament();

        $data = $gameRepository->loadByTournamentId($tournament->getId());

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @return Response
     * @internal param $numberOfFixtures
     */
    public function getTournamentPlayoffsData($tournamentId)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentPlayoffsTournament();

        $data = $gameRepository->loadPlayoffsFixturesByTournamentId($tournament->getId());

        return $this->sendJsonResponse($data);
    }

    /**
 * @param $tournamentId
 * @param $groupId
 * @return Response
 * @internal param $numberOfFixtures
 */
    public function getTournamentPlayoffsDivisionData($tournamentId, $groupId)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentPlayoffsTournament();

        $data = $gameRepository->loadPlayoffsFixturesByTournamentIdAndDivision($tournament->getId(), $groupId);

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @return Response
     * @internal param $numberOfFixtures
     */
    public function getTournamentPlayoffsDivisionsData($tournamentId)
    {
        $data = [];

        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentPlayoffsTournament();

        $tournamentGroups = $tournamentRepository->loadGroupsByPlayoffsId($tournament->getId());

        foreach ($tournamentGroups as $group) {
            $divisionId = $group['id'];
            $divisionName = $group['name'];
            $data[] = [
                'divisionId' => $divisionId,
                'divisionName' => $divisionName,
                'data' => $gameRepository->loadPlayoffsFixturesByTournamentIdAndDivision($tournament->getId(), $divisionId)
            ];
        }

        return $this->sendJsonResponse($data);
    }
}
