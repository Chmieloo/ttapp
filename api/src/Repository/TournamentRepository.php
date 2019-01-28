<?php

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    /**
     * @return mixed
     */
    public function loadList()
    {
        return $this->createQueryBuilder('t')
            ->select(
                't.id',
                't.name',
                't.start_time',
                'case when t.is_playoffs = 0 then \'group\' else \'playoffs\' end as phase',
                't.is_finished as finished'
            )
            ->orderBy('t.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
