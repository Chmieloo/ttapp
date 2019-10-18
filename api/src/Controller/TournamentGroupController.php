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
     * @param $id
     * @return Response
     */
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
