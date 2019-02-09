<?php

namespace App\Controller;

use App\Entity\GameMode;
use App\Entity\MatchMode;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class MatchModeController extends BaseController
{
    /**
     * Get match mode by id
     *
     * @param $id
     * @return Response
     */
    public function getMatchModeById($id)
    {
        $matchMode = $this->getDoctrine()
            ->getRepository(GameMode::class)
            ->find($id);

        if (!$matchMode) {
            throw $this->createNotFoundException(
                'Match mode not found with id: ' . $id
            );
        }

        $data = $this->serializer->serialize($matchMode, 'json');

        return new Response($data);
    }

    /**
     * List of all match modes
     *
     * @return Response
     */
    public function getMatchModes()
    {
        $matchModes = $this->getDoctrine()
            ->getRepository(GameMode::class)
            ->loadAll();

        if (!$matchModes) {
            throw $this->createNotFoundException(
                'No match modes found'
            );
        }

        $data = $this->serializer->serialize($matchModes, 'json');

        return new Response($data);
    }
}
