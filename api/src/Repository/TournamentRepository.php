<?php

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
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

    /**
     * @return Tournament|null
     */
    public function findNotFinished(): ?Tournament
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.is_finished = 0')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $tournamentId
     * @return array
     */
    public function getStandingsByTournamentId($tournamentId)
    {
        $sql =
            'select p.id as playerId, p.name as playerName, ptg.group_id as groupId, tg.name as groupName, ' .
            'SUM(if (g1.is_finished = 1, 1, 0)) as played, tg.abbreviation as groupAbbreviation, ' .
            'SUM(if (g1.winner_id = ptg.player_id, 1, 0)) as wins, ' .
            'SUM(if (g1.is_finished = 1 AND g1.winner_id = 0, 1, 0)) as draws, ' .
            'SUM(if (g1.is_finished = 1 AND g1.winner_id != 0 AND g1.winner_id != ptg.player_id, 1, 0)) as losses, ' .
            '(SUM(if (g1.winner_id = ptg.player_id, 1, 0)) * 2 + SUM(if (g1.is_finished = 1 AND g1.winner_id = 0, 1, 0))) as points ' .
            'from player_tournament_group ptg ' .
            'left join game g1 on g1.home_player_id = ptg.player_id or g1.away_player_id = ptg.player_id ' .
            'join player p on p.id = ptg.player_id ' .
            'join tournament_group tg on tg.id = ptg.group_id ' .
            'where ptg.tournament_id = :tournamentId ' .
            'group by ptg.player_id ' .
            'order by ptg.group_id asc, points desc ';

        $params['tournamentId'] = $tournamentId;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($result as $item) {
            $groupId = $item['groupId'];
            $groupName = $item['groupName'];
            $groupAbbreviation = $item['groupAbbreviation'];
            $playerId = $item['playerId'];
            $playerName = $item['playerName'];

            $played = $item['played'];
            $wins = $item['wins'];
            $draws = $item['draws'];
            $losses = $item['losses'];
            $points = $item['points'];

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
                'played' => $played,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'points' => $points,
            ];

            $groups[$groupId] = $currentGroup;
        }

        return $groups;
    }
}
