<?php

namespace App\Repository;

use App\Entity\Points;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Points|null find($id, $lockMode = null, $lockVersion = null)
 * @method Points|null findOneBy(array $criteria, array $orderBy = null)
 * @method Points[]    findAll()
 * @method Points[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Points::class);
    }

    /**
     * @param $home
     * @param $away
     * @param $matchId
     * @return bool
     * @throws DBALException
     */
    public function removeLastPoint($home, $away, $matchId)
    {
        $selectSql =
            'select max(p.id) as pointId
            from points p
            join scores s on s.id = p.score_id
            where p.is_home_point = :homePoint and p.is_away_point = :awayPoint and s.game_id = :gameId';

        $params = [
            'gameId' => $matchId,
            'homePoint' => $home,
            'awayPoint' => $away,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($selectSql);
        $stmt-> execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $pointId = (int)$result['pointId'];

        if ($pointId) {
            $deleteSql = 'delete from points p where p.id = :pointId';

            $params = [
                'pointId' => $pointId,
            ];

            $em = $this->getEntityManager();
            $stmt = $em->getConnection()->prepare($deleteSql);
            $stmt-> execute($params);

            return true;
        } else {
            return false;
        }
    }
}
