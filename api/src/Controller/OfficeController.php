<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Office;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\OfficeRepository;
use App\Repository\TournamentRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficeController extends BaseController
{
    /**
     * @return Response
     */
    public function getOffices(): Response
    {
        /** @var OfficeRepository $officeRepository */
        $officeRepository = $this->manager->getRepository(Office::class);

        $offices = $officeRepository->loadAll();

        if (!$offices) {
            throw $this->createNotFoundException(
                'No offices found'
            );
        }

        return $this->sendJsonResponse($offices);
    }
}
