<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use Doctrine\DBAL\DBALException;
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
        $tournaments = $tournamentId ?
            [$tournamentRepository->find($tournamentId)] :
            $tournamentRepository->loadCurrentTournaments();

        $ids = [];
        foreach ($tournaments as $tournament) {
            $ids[] = $tournament->getId();
        }

        $data = $gameRepository->loadLastResultsByTournamentIds($ids, $numberOfResults);

        if (!$data) {
            $data = [];
        }

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @param $numberOfFixtures
     * @return Response
     * @throws DBALException
     */
    public function getTournamentOverdueSchedule($tournamentId, $numberOfFixtures)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournaments = $tournamentId ?
            [$tournamentRepository->find($tournamentId)] :
            $tournamentRepository->loadCurrentTournaments();

        $ids = [];
        foreach ($tournaments as $tournament) {
            $ids[] = $tournament->getId();
        }

        $data = $gameRepository->loadOverdueFixturesByTournamentIds($ids, $numberOfFixtures);

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @param $numberOfFixtures
     * @return Response
     * @throws DBALException
     */
    public function getTournamentSchedule($tournamentId, $numberOfFixtures)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournaments = $tournamentId ?
            [$tournamentRepository->find($tournamentId)] :
            $tournamentRepository->loadCurrentTournaments();

        $ids = [];
        foreach ($tournaments as $tournament) {
            $ids[] = $tournament->getId();
        }

        $data = $gameRepository->loadUpcomingFixturesByTournamentIds($ids, $numberOfFixtures);

        return $this->sendJsonResponse($data);
    }

    /**
     * @param $tournamentId
     * @return Response
     * @throws DBALException
     */
    public function getTodaysFixtures($tournamentId)
    {
        /** @var TournamentRepository $tournamentRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);

        # If empty, load current
        $tournament = $tournamentId ?
            $tournamentRepository->find($tournamentId) :
            $tournamentRepository->loadCurrentTournaments();

        $message = '';

        # Load overdue first
        $data1 = $gameRepository->loadOverdueForToday($tournament->getId());

        if ($data1) {
            $message .= "Table tennis league overdue matches :clock12:\n";
            foreach ($data1 as $match) {
                $message .= "> *" . $match['groupName'] . "*: " . "(" . $match['dateOfMatch'] . "," . $match['timeOfMatch'] . ") " . $match['homeSlackName'] . " *vs* " . $match['awaySlackName'] . "\n";
            }
        }

        # Load today's schedule
        $data2 = $gameRepository->loadScheduleForToday($tournament->getId());

        if ($data2) {
            $message .= "Table tennis today's matches :table_tennis_paddle_and_ball:\n";
            foreach ($data2 as $match) {
                $message .= "> *" . $match['groupName'] . "*: " . "(" . $match['timeOfMatch'] . ") " . $match['homeSlackName'] . " *vs* " . $match['awaySlackName'] . "\n";
            }
        }

        if ($message) {
            $payload = [
                'text' => $message,
                'method' => 'post',
                'contentType' => 'application/json',
                'muteHttpExceptions' => true,
                'link_names' => 1,
                'username' => 'tabletennisbot',
                'icon_emoji' => ':table_tennis_paddle_and_ball:'
            ];

            $this->postScheduler($payload);
        }

        return $this->sendJsonResponse([]);
    }

    /**
     * @param $data
     * @param int $officeId
     * @return bool|string
     */
    private function postScheduler($data, $officeId = 1)
    {
        $this->getDoctrine()->getManager();

        if (isset($this->slackKey[$officeId])) {
            if ($this->slackKey) {
                $data_string = json_encode($data);

                $ch = curl_init($this->slackKey[$officeId]);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                );

                return curl_exec($ch);
            }
        }
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
            $tournamentRepository->loadCurrentTournaments();

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
     * @throws DBALException
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
        //var_dump($tournamentGroups);

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

    /**
     * @param $data
     * @return bool|string
     */
    private function postSlackMessage($data)
    {
        if ($this->slackKey) {
            $data_string = json_encode($data);

            $ch = curl_init($this->slackKey);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );

            return curl_exec($ch);
        }
    }

    public function leaders()
    {
        /** @var TournamentRepository $gameRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        $currentTournaments = $tournamentRepository->loadCurrentTournaments();

        $ids = [];
        foreach ($currentTournaments as $tournament) {
            $ids[] = $tournament->getId();
        }
        $data = $tournamentRepository->loadLeaders($ids);

        return $this->sendJsonResponse($data);
    }

    /**
     * @return Response
     */
    public function weekStatistics()
    {
        /** @var TournamentRepository $gameRepository */
        $tournamentRepository = $this->getDoctrine()->getRepository(Tournament::class);
        $currentTournaments = $tournamentRepository->loadCurrentTournaments();

        $ids = [];
        foreach ($currentTournaments as $tournament) {
            $ids[] = $tournament->getId();
        }
        $data = $tournamentRepository->loadWeekStats($ids);

        return $this->sendJsonResponse($data);
    }
}
