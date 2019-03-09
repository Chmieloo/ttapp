<?php

namespace App\Repository;

use App\Entity\GameCup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameCup|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameCup|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameCup[]    findAll()
 * @method GameCup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameCupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameCup::class);
    }

    // /**
    //  * @return GameCup[] Returns an array of GameCup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameCup
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
