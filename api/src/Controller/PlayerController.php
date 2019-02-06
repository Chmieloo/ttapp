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
    const STARTING_ELO = 1500;

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
            ->loadPlayerById($id);

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
            ->loadAll();

        if (!$players) {
            throw $this->createNotFoundException(
                'No players found'
            );
        }

        return $this->sendJsonResponse($players);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function addPlayer(Request $request)
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
            $player = new Player();
            $player->setName($data['name']);
            $player->setNickname($data['nickname']);
            $player->setTournamentElo(self::STARTING_ELO);
            $player->setCurrentElo(self::STARTING_ELO);
            $em->persist($player);
            $em->flush();

            return new Response($player->getId());
        }
    }
}