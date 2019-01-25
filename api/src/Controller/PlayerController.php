<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class PlayerController extends BaseController
{
    /**
     * Get player by id
     *
     * @param $id
     * @return Response
     */
    public function getPlayerById($id)
    {
        $player = $this->getDoctrine()
            ->getRepository(Player::class)
            ->find($id);

        if (!$player) {
            throw $this->createNotFoundException(
                'Player not found with id: ' . $id
            );
        }

        $data = $this->serializer->serialize($player, 'json');

        return new Response($data);
    }

    /**
     * List of all players
     *
     * @return Response
     */
    public function getPlayers()
    {
        $players = $this->getDoctrine()
            ->getRepository(Player::class)
            ->findAll();

        if (!$players) {
            throw $this->createNotFoundException(
                'No players found'
            );
        }

        $data = $this->serializer->serialize($players, 'json');

        $response = new Response();
        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}