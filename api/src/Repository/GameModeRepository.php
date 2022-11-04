<?php

namespace App\Repository;

use App\Entity\GameMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameMode[]    findAll()
 * @method GameMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMode::class);
    }

    /**
     * @return mixed[]
     */
    public function loadAll()
    {
        $sql = 'select * from game_mode';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->executeQuery();

        $result = $stmt->fetchAllAssociative();

        return $result;
    }
}
