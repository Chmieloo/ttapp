<?php

namespace App\Repository;

use App\Entity\MatchMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MatchMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchMode[]    findAll()
 * @method MatchMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchModeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MatchMode::class);
    }
}
