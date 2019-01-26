<?php

namespace App\Controller;

use App\Entity\Tournament;

class TournamentController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTournaments()
    {
        $tournaments = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->findAll();

        if (!$tournaments) {
            throw $this->createNotFoundException(
                'No tournaments found'
            );
        }

        return $this->sendJsonResponse($tournaments);
    }
}
