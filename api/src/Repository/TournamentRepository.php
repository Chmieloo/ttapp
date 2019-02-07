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
        $sql =
            'select t.id, t.name, t.start_time, ' .
            'case when t.is_playoffs = 0 then \'group\' else \'playoffs\' end as phase, ' .
            't.is_finished as finished, count(distinct(g.away_player_id)) as participants, ' .
            'count(g.id) as scheduled, sum(g.is_finished) as finished ' .
            'from tournament t ' .
            'join game g on g.tournament_id = t.id';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
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
            '(SUM(if (g1.winner_id = ptg.player_id, 1, 0)) * 2 + SUM(if (g1.is_finished = 1 AND g1.winner_id = 0, 1, 0))) as points, ' .
            '(SUM(if (g1.home_player_id = ptg.player_id, g1.home_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.away_score, 0))) as setsFor, ' .
            '(SUM(if (g1.home_player_id = ptg.player_id, g1.away_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.home_score, 0))) as setsAgainst, ' .
            'u.ralliesFor, u.ralliesAgainst ' .
            'from player_tournament_group ptg ' .
            'left join game g1 on g1.home_player_id = ptg.player_id or g1.away_player_id = ptg.player_id ' .
            'join player p on p.id = ptg.player_id ' .
            'join tournament_group tg on tg.id = ptg.group_id ' .
            'join ( ' .
            'select player, sum(pointsFor) as ralliesFor, sum(pointsAgainst) as ralliesAgainst from ( ' .
            'SELECT g.id, g.home_player_id   AS player, sum(s.home_points) AS pointsFor, sum(s.away_points) AS pointsAgainst ' .
            'FROM scores s JOIN game g ON g.id = s.game_id ' .
            'GROUP BY g.home_player_id ' .
            'UNION ' .
            'SELECT ' .
            'g.id, g.away_player_id   AS player, sum(s.away_points) AS pointsFor, sum(s.home_points) AS pointsAgainst ' .
            'FROM scores s JOIN game g ON g.id = s.game_id ' .
            'GROUP BY g.away_player_id ' .
            ') u group by player ' .
            ') u on u.player = ptg.player_id ' .
            'where ptg.tournament_id = :tournamentId ' .
            'group by ptg.player_id ' .
            'order by ptg.group_id asc, points desc, setsFor desc, setsAgainst asc ';

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
            $setsFor = $item['setsFor'];
            $setsAgainst = $item['setsAgainst'];
            $setsDiff = $setsFor - $setsAgainst;
            $ralliesFor = $item['ralliesFor'];
            $ralliesAgainst = $item['ralliesAgainst'];
            $ralliesDiff = $ralliesFor - $ralliesAgainst;

            $currentGroup = array_key_exists($groupId, $groups) ?
                $groups[$groupId] :
                $groups[$groupId] = [
                    'groupId' => $groupId,
                    'groupName' => $groupName,
                    'groupAbbreviation' => $groupAbbreviation,
                ];

            if (array_key_exists('players', $currentGroup)) {
                $pos = count($currentGroup['players']) + 1;
            } else {
                $pos = 1;
            }

            $currentGroup['players'][] = [
                'pos' => $pos,
                'playerId' => $playerId,
                'playerName' => $playerName,
                'played' => $played,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'points' => $points,
                'setsFor' => $setsFor,
                'setsAgainst' => $setsAgainst,
                'setsDiff' => $setsDiff > 0 ? '+' . $setsDiff : (string)$setsDiff,
                'ralliesFor' => $ralliesFor,
                'ralliesAgainst' => $ralliesAgainst,
                'ralliesDiff' => $ralliesDiff > 0 ? '+' . $ralliesDiff : (string)$ralliesDiff,
            ];

            $groups[$groupId] = $currentGroup;
        }

        return $groups;
    }
}
