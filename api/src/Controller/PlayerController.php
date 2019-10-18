<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class PlayerController extends BaseController
{
    const STARTING_ELO = 1500;

    /**
     * @param $id
     * @return Response
     * @throws DBALException
     */
    public function getPlayerById($id)
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);
        $player = $playerRepository->loadPlayerById($id);

        if (!$player) {
            throw $this->createNotFoundException(
                'Player not found with id: ' . $id
            );
        }

        return $this->sendJsonResponse($player);
    }

    /**
     * @param $id
     * @return Response
     * @throws DBALException
     */
    public function getPlayerResults($id)
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);
        $player = $playerRepository->loadPlayerResults($id);

        if (!$player) {
            $player = [];
        }

        return $this->sendJsonResponse($player);
    }

    public function getPlayerSchedule($id)
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);

        $player = $playerRepository->loadPlayerSchedule($id);

        return $this->sendJsonResponse($player);
    }

    /**
     * List of all players
     *
     * @return Response
     */
    public function getPlayers()
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);
        $players = $playerRepository->loadAllPlayers();

        if (!$players) {
            throw $this->createNotFoundException(
                'No players found'
            );
        }

        return $this->sendJsonResponse($players);
    }
}