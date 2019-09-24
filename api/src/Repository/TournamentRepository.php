<?php

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    loadAll()
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
            'select t.id, t.name, t.start_time, t.is_playoffs as isPlayoffs, ' .
            'case when t.is_playoffs = 0 then \'group\' else \'playoffs\' end as phase, ' .
            't.is_finished as isFinished, count(distinct(g.home_player_id)) as participants, ' .
            'count(g.id) as scheduled, if (sum(g.is_finished) is null, 0, sum(g.is_finished)) as finished ' .
            'from tournament t ' .
            'left join game g on g.tournament_id = t.id ' .
            'where t.is_official = 1 ' .
            'group by t.id';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @return Tournament|null
     */
    public function loadCurrentTournament(): ?Tournament
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.is_finished = 0 and t.is_playoffs = 0')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Tournament|null
     */
    public function loadCurrentPlayoffsTournament(): ?Tournament
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.is_finished = 0 and t.is_playoffs = 1')
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
            'select p.id as playerId, p.name as playerName, ptg.group_id as groupId, tg.name as groupName, tg.color_template as colorTemplate, ' .
            'SUM(if (g1.is_finished = 1, 1, 0)) as played, tg.abbreviation as groupAbbreviation, ' .
            'SUM(if (g1.winner_id = ptg.player_id, 1, 0)) as wins, ' .
            'SUM(if (g1.is_finished = 1 AND g1.winner_id = 0, 1, 0)) as draws, ' .
            'SUM(if (g1.is_finished = 1 AND g1.winner_id != 0 AND g1.winner_id != ptg.player_id, 1, 0)) as losses, ' .
            '(SUM(if (g1.winner_id = ptg.player_id, 1, 0)) * 2 + SUM(if (g1.is_finished = 1 AND g1.winner_id = 0, 1, 0))) as points, ' .
            '(SUM(if (g1.home_player_id = ptg.player_id, g1.home_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.away_score, 0))) as setsFor, ' .
            '(SUM(if (g1.home_player_id = ptg.player_id, g1.away_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.home_score, 0))) as setsAgainst, ' .
            '((SUM(if (g1.home_player_id = ptg.player_id, g1.home_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.away_score, 0))) - ' .
            '(SUM(if (g1.home_player_id = ptg.player_id, g1.away_score, 0)) + SUM(if (g1.away_player_id = ptg.player_id, g1.home_score, 0)))) as setDf, ' .
            'u.ralliesFor, u.ralliesAgainst, u.df ' .
            'from player_tournament_group ptg ' .
            'left join game g1 on (g1.home_player_id = ptg.player_id or g1.away_player_id = ptg.player_id) and g1.tournament_id = :tournamentId ' .
            'left join player p on p.id = ptg.player_id ' .
            'left join tournament_group tg on tg.id = ptg.group_id ' .
            'left join ( ' .
            'select player, sum(pointsFor) as ralliesFor, sum(pointsAgainst) as ralliesAgainst, (sum(pointsFor) - sum(pointsAgainst)) as df from ( ' .
            'SELECT g.id, g.home_player_id   AS player, sum(s.home_points) AS pointsFor, sum(s.away_points) AS pointsAgainst ' .
            'FROM scores s JOIN game g ON g.id = s.game_id ' .
            'JOIN tournament t1 on t1.id = g.tournament_id WHERE t1.id = :tournamentId ' .
            'GROUP BY g.home_player_id ' .
            'UNION ' .
            'SELECT ' .
            'g.id, g.away_player_id   AS player, sum(s.away_points) AS pointsFor, sum(s.home_points) AS pointsAgainst ' .
            'FROM scores s JOIN game g ON g.id = s.game_id ' .
            'JOIN tournament t2 on t2.id = g.tournament_id WHERE t2.id = :tournamentId ' .
            'GROUP BY g.away_player_id ' .
            ') u group by player ' .
            ') u on u.player = ptg.player_id ' .
            'where ptg.tournament_id = :tournamentId ' .
            'group by ptg.player_id ' .
            'order by ptg.group_id asc, points desc, setDf desc, u.df desc ';

        $params['tournamentId'] = $tournamentId;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($result as $item) {
            $groupId = $item['groupId'];
            $groupName = $item['groupName'];
            $colorTemplate = explode('.', $item['colorTemplate']);
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

            $playerPositionColor = $colorTemplate[$pos - 1];

            $currentGroup['players'][] = [
                'pos' => $pos,
                'posColor' => $playerPositionColor,
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

    public function loadGroupsByPlayoffsId($tournamentId)
    {
        $query = 'select id, name from tournament_group tg where tg.tournament_id = :tournamentId';
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function loadLeaders($tournamentId)
    {
        $result = [];

        $sql = "select p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points, count(s.id) as sets,
                (sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) / count(s.id)) as avgps
                from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                join tournament t on g.tournament_id = t.id
                where t.is_official = 1
                and g.is_walkover = 0
                and g.is_finished = 1
                group by p.id
                order by points desc, p.name asc
                limit 0, 5";

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result['allTimePointsLeaders'] = $data;

        $sql = "select p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points, count(s.id) as sets,
                (sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) / count(s.id)) as avgps
                from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                join tournament t on g.tournament_id = t.id
                where t.id = :tournamentId
                and g.is_walkover = 0
                and g.is_finished = 1
                group by p.id
                order by points desc, p.name asc
                limit 0, 5";

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result['currentTournamentPointsLeaders'] = $data;

        $sql = "select p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points, count(s.id) as sets,
                (sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) / count(s.id)) as avgps
                from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                join tournament t on g.tournament_id = t.id
                where t.id = :tournamentId
                and g.date_played between subdate(curdate(),dayofweek(curdate())+5) and subdate(curdate(),dayofweek(curdate())-1)
                and g.is_walkover = 0
                and g.is_finished = 1
                group by p.id
                order by points desc, p.name asc
                limit 0, 5";

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result['lastWeekPointsLeaders'] = $data;

        /*
        $sql = "select p.slack_name as slackName, p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points, count(s.id), 
                (sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) / count(s.id)) as avgPoints from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                where g.tournament_id = :tournamentId
                and g.is_walkover = 0
                and g.is_finished = 1
                and g.date_played between subdate(curdate(),dayofweek(curdate())+5)
                and subdate(curdate(),dayofweek(curdate())-1)
                group by p.id
                order by avgPoints desc, p.name asc
                limit 0, 5";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($data as $item) {
            $result['weekAvgLeaders'][] = [
                'name' => $item['slackName'],
                'avgPoints' => number_format($item['avgPoints'], 2),
            ];
        }

        # all time
        $sql = "select p.slack_name as slackName, p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points, count(s.id), 
                (sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) / count(s.id)) as avgPoints from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                where g.tournament_id = :tournamentId
                and g.is_walkover = 0
                and g.is_finished = 1
                group by p.id
                order by avgPoints desc, p.name asc
                limit 0, 5";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $item) {
            $result['seasonAvgLeaders'][] = [
                'name' => $item['slackName'],
                'avgPoints' => number_format($item['avgPoints'], 2),
            ];
        }

        $sql = "select p.slack_name as slackName, p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points
                from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                where g.tournament_id = :tournamentId
                and g.is_walkover = 0
                and g.is_finished = 1
                and g.date_played between subdate(curdate(),dayofweek(curdate())+5)
                and subdate(curdate(),dayofweek(curdate())-1)
                group by p.id
                order by points desc, p.name asc
                limit 0, 5";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $item) {
            $result['weekPointsLeaders'][] = [
                'name' => $item['slackName'],
                'points' => $item['points'],
            ];
        }

        # all time
        $sql = "select p.slack_name as slackName, p.name, sum(if(p.id = g.home_player_id, s.home_points, s.away_points)) as points
                from scores s
                join game g on s.game_id = g.id
                join player p on p.id in (g.home_player_id, g.away_player_id)
                where g.tournament_id = :tournamentId
                and g.is_walkover = 0
                and g.is_finished = 1
                group by p.id
                order by points desc, p.name asc
                limit 0, 5";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $item) {
            $result['seasonPointsLeaders'][] = [
                'name' => $item['slackName'],
                'points' => $item['points'],
            ];
        }

        # general info
        $sql = "select g.id, g.home_player_id as homePlayerId, g.away_player_id as awayPlayerId, sum(s.home_points + s.away_points) as matchPoints, count(s.id) as matchSets,
                if (g.home_score = 0 or g.away_score = 0, 1, 0) as score30,
                if (g.home_score = 1 or g.away_score = 1, 1, 0) as score31,
                if (g.home_score = 2 or g.away_score = 2, 1, 0) as score22
                from game g
                join scores s on g.id = s.game_id
                where g.date_played between subdate(curdate(),dayofweek(curdate())+5)
                and subdate(curdate(),dayofweek(curdate())-1)
                and tournament_id = :tournamentId
                group by s.game_id";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute([
            'tournamentId' => $tournamentId
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $playerCache = [];
        $pointsTotal = 0;
        $setsTotal = 0;
        $score30 = 0;
        $score31 = 0;
        $score22 = 0;
        foreach ($data as $item) {
            $playerCache[] = $item['homePlayerId'];
            $playerCache[] = $item['awayPlayerId'];
            $pointsTotal += $item['matchPoints'];
            $setsTotal += $item['matchSets'];
            $score30 += $item['score30'];
            $score31 += $item['score31'];
            $score22 += $item['score22'];
        }

        $playerCache = array_unique($playerCache);
        $result['generalInfo']['uniquePlayers'] = count($playerCache);
        $result['generalInfo']['matchesPlayed'] = count($data);
        $result['generalInfo']['matchPoints'] = $pointsTotal;
        $result['generalInfo']['matchPointsAvg'] = number_format(($pointsTotal / count($data)), 2);
        $result['generalInfo']['setPointsAvg'] = number_format(($pointsTotal / $setsTotal), 2);
        $result['generalInfo']['score30'] = $score30;
        $result['generalInfo']['score31'] = $score31;
        $result['generalInfo']['score22'] = $score22;

        */

        return $result;
    }
}
