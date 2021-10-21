<?php

namespace App\Repository;

use App\Entity\GameCup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GameCup|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameCup|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameCup[]    findAll()
 * @method GameCup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameCupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameCup::class);
    }
}
