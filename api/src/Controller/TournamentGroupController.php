<?php

namespace App\Controller;

use App\Entity\TournamentGroup;
use App\Entity\Tournament;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TournamentGroupController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function addTournamentGroup(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['tournament'])) {
            return new JsonResponse([
                'status' => 'error',
                'errorText' => 'Fill the form'
            ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        # Get tournament by id
        /** @var Tournament $tournament */
        $tournament = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->find($data['tournament']);

        $em = $this->getDoctrine()->getManager();
        $group = new TournamentGroup();
        $group->setName($data['name']);
        $group->setTournament($tournament);
        $group->setPriority(0);
        $em->persist($group);
        $em->flush();

        return new Response($group->getId());
    }

    public function getTournamentGroupsByTournamentId($id)
    {
        $data = $this->getDoctrine()
            ->getRepository(TournamentGroup::class)
            ->getTournamentGroupsByTournamentId($id);

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }
}
