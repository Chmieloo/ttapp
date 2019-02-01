<?php

namespace App\Controller;

use App\Entity\Tournament;
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
        $tournaments = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->loadList();

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
        $standings = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->getStandingsByTournamentId($id);

        return $this->sendJsonResponse($standings);
    }
}
