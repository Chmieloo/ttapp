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

    /**
     * @param $id
     * @return mixed[]
     */
    public function getTournamentGroupsByTournamentId($id)
    {
        $sql =
            'select p.id as playerId, p.name as playerName, ' .
            'tg.id as groupId, tg.name as groupName, tg.abbreviation as groupAbbreviation ' .
            'from player_tournament_group ptg ' .
            'join player p on p.id = ptg.player_id ' .
            'join tournament_group tg on tg.id = ptg.group_id ' .
            'where tg.tournament_id = :tournamentId';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($result as $data) {
            $groupId = $data['groupId'];
            $groupName = $data['groupName'];
            $groupAbbreviation = $data['groupAbbreviation'];
            $playerId = $data['playerId'];
            $playerName = $data['playerName'];

            $currentGroup = array_key_exists($groupId, $groups) ?
                $groups[$groupId] :
                $groups[$groupId] = [
                    'groupId' => $groupId,
                    'groupName' => $groupName,
                    'groupAbbreviation' => $groupAbbreviation,
                ];

            $currentGroup['players'][] = [
                'playerId' => $playerId,
                'playerName' => $playerName,
            ];

            $groups[$groupId] = $currentGroup;
        }

        return $groups;
    }

    // /**
    //  * @return Group[] Returns an array of Group objects
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
    public function findOneBySomeField($value): ?Group
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
