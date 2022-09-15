<?php

namespace App\Repository;

use App\Entity\TournamentGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TournamentGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentGroup[]    loadAll()
 * @method TournamentGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentGroup::class);
    }

    public function loadByTournamentId(int $id): array
    {
        $baseSql = 'select id, name, priority, abbreviation, is_official, color_template 
                    from tournament_group g 
                    where g.tournament_id = :tid';
        $params = ['tid' => $id];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        return $stmt->executeQuery($params)->fetchAllAssociative();
    }
}
