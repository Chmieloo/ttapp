<?php

namespace App\Repository;

use App\Entity\Office;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Office|null find($id, $lockMode = null, $lockVersion = null)
 * @method Office|null findOneBy(array $criteria, array $orderBy = null)
 * @method Office[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeRepository extends ServiceEntityRepository
{
    /** @var Connection  */
    private $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Office::class);
        $this->connection = $connection;
    }

    public function loadAll()
    {
        $query = $this->connection->createQueryBuilder()
            ->select(
                "id",
                "name",
                "is_default as isDefault"
            )
            ->from("office", "o")
            ->orderBy("id");

        $result = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as &$item) {
            $item['isDefault'] = (int)$item['isDefault'];
        }

        return $result;
    }
}
