<?php

namespace App\Repository;

use App\Entity\Office;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Office|null find($id, $lockMode = null, $lockVersion = null)
 * @method Office|null findOneBy(array $criteria, array $orderBy = null)
 * @method Office[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeRepository extends ServiceEntityRepository
{
    /** @var Connection  */
    private $connection;

    public function __construct(RegistryInterface $registry, Connection $connection)
    {
        parent::__construct($registry, Office::class);
        $this->connection = $connection;
    }

    public function loadAll()
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                "id",
                "name"
            )
            ->from("offices", "o")
            ->orderBy("id");

        $result = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
