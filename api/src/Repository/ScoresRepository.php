<?php

namespace App\Repository;

use App\Entity\Scores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use PDO;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scores|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scores|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scores[]    loadAll()
 * @method Scores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scores::class);
    }

    /**
     * @param $matchId
     * @param $setNumber
     * @return mixed
     * @throws Exception
     */
    public function getScoreIdByMatchIdAndSetNumber($matchId, $setNumber)
    {
        $baseSql = 'select id from scores where game_id = :matchId and set_number = :setNumber';
        $params = [
            'matchId' => $matchId,
            'setNumber' => $setNumber
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $result = $stmt->executeQuery($params)->fetchAllAssociative();

        return $result['id'];
    }
}
