<?php

namespace App\Repository;

use App\Entity\TournamentGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TournamentGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentGroup[]    findAll()
 * @method TournamentGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TournamentGroup::class);
    }
}
