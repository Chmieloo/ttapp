<?php

namespace App\Repository;

use App\Entity\Points;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Points|null find($id, $lockMode = null, $lockVersion = null)
 * @method Points|null findOneBy(array $criteria, array $orderBy = null)
 * @method Points[]    findAll()
 * @method Points[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Points::class);
    }

    /**
     * @param $home
     * @param $away
     * @param $matchId
     * @return bool
     */
    public function removeLastPoint($home, $away, $matchId)
    {
        $baseSql = 'delete from points 
                    where id IN (
                    select max(p.id) from (select * from points) p 
                    join scores s on s.id = p.score_id
                    join game g on g.id = s.game_id
                    where p.is_home_point = :homePoint and p.is_away_point = :awayPoint and g.id = :gameId
                    )';

        $params = [
            'gameId' => $matchId,
            'homePoint' => $home,
            'awayPoint' => $away,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);

        return true;
    }
}
