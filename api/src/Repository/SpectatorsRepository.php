<?php

namespace App\Repository;

use App\Entity\Spectators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PDO;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spectators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spectators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spectators[]    findAll()
 * @method Spectators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpectatorsRepository extends ServiceEntityRepository
{

    protected $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Spectators::class);
        $this->connection = $connection;
    }

    /**
     * @param $value
     * @return mixed
     * @throws DBALException
     */
    public function loadByTimestamp($value)
    {

        $baseSql = "select id from spectators s where s.pit = :value";

        $params = [
            'value' => $value,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $result = $stmt->executeQuery($params)->fetchAllAssociative();

        return $result;
    }

    /**
     * @param $gameId
     * @return bool
     * @throws DBALException
     */
    public function initGame($gameId)
    {
        $query = "delete from spectators s where s.game_id = :gameId";
        $params = ['gameId' => $gameId];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->executeQuery($params);

        return true;
    }

    public function finalizeGame($gameId)
    {
        $query = "select s.id, s.spectators, UNIX_TIMESTAMP(s.pit) as ts from spectators s
                    where s.game_id = :gameId
                    order by s.pit asc";
        $params = ['gameId' => $gameId];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->executeQuery($params);

        $result = $stmt->fetchAllAssociative();

        $invalidIds = [];
        $timestamps = [];
        foreach ($result as $item) {
            $id = $item['id'];
            $spectators = $item['spectators'];
            $ts = $item['ts'];

            if (array_key_exists($ts, $timestamps)) {
                if ($timestamps[$ts]['spectators'] <= $spectators) {
                    $timestamps[$ts]['spectators'] = $spectators;
                    $invalidIds[] = $timestamps[$ts]['id'];
                    $timestamps[$ts]['id'] = $id;
                } else {
                    $invalidIds[] = $id;
                }
            } else {
                $timestamps[$ts]['spectators'] = $spectators;
                $timestamps[$ts]['id'] = $id;
            }
        }

        $this->connection->createQueryBuilder()
            ->delete('spectators', 's')
            ->where('s.id in (:invalidIds)')
            ->setParameter('invalidIds', $invalidIds, Connection::PARAM_INT_ARRAY)
            ->executeQuery();

        return true;
    }
}
