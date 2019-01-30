<?php

namespace App\Controller;

use App\Entity\Game;

class MatchController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMatches()
    {
        $data = $this->getDoctrine()
            ->getRepository(Game::class)
            ->getAllByTournamentId(1);

        if (!$data) {
            throw $this->createNotFoundException(
                'No data'
            );
        }

        return $this->sendJsonResponse($data);
    }
}
