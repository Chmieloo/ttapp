<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        return $this->sendJsonResponse($player);
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
            ->findAllSimple();

        if (!$players) {
            throw $this->createNotFoundException(
                'No players found'
            );
        }

        return $this->sendJsonResponse($players);
    }

    public function addPlayer(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (!empty($data['name'])) {
            $em = $this->getDoctrine()->getManager();
            $player = new Player();
            $player->setName($data['name']);
            $em->persist($player);
            $em->flush();

            return new Response($player->getId());
        }

        return new JsonResponse([
            'status' => 'error',
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}