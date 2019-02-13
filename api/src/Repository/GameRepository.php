<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\TournamentGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    loadAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Base sql string
     * @return string
     */
    public function baseQuery()
    {
        $sql =
            'select g.id, gm.name, g.winner_id as winnerId, p1.name homePlayerName, p2.name as awayPlayerName, ' .
            'p1.id as homePlayerId, p2.id awayPlayerId, gm.max_sets as maxSets, g.is_finished as isFinished, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal, g.is_walkover as isWalkover, ' .
            's1.home_points as s1hp, s1.away_points s1ap, ' .
            's2.home_points as s2hp, s2.away_points s2ap, ' .
            's3.home_points as s3hp, s3.away_points s3ap, ' .
            's4.home_points as s4hp, s4.away_points s4ap, ' .
            'p1.display_name as homePlayerDisplayName, ' .
            'p2.display_name as awayPlayerDisplayName, ' .
            'tg.name as groupName, g.date_of_match as dateOfMatch, g.date_played as datePlayed ' .
            'from game g ' .
            'join game_mode gm on gm.id = g.game_mode_id ' .
            'join player p1 on p1.id = g.home_player_id ' .
            'join player p2 on p2.id = g.away_player_id ' .
            'join tournament_group tg on tg.id = g.tournament_group_id ' .
            'left join scores s1 on s1.game_id = g.id and s1.set_number = 1 ' .
            'left join scores s2 on s2.game_id = g.id and s2.set_number = 2 ' .
            'left join scores s3 on s3.game_id = g.id and s3.set_number = 3 ' .
            'left join scores s4 on s4.game_id = g.id and s4.set_number = 4 ';

        return $sql;
    }

    /**
     * @param $id
     * @param int $limit
     * @return array
     */
    public function loadLastResultsByTournamentId($id, $limit = null)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'and g.is_finished = 1 ';
        $baseSql .= 'order by g.date_played desc ';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (is_numeric($limit) && $limit) {
            $result = array_slice($result, 0, $limit);
        }

        foreach ($result as $match) {
            $matchId = $match['id'];

            $setPoints = [
                $match['s1hp'],
                $match['s1ap'],
                $match['s2hp'],
                $match['s2ap'],
                $match['s3hp'],
                $match['s3ap'],
                $match['s4hp'],
                $match['s4ap'],
            ];
            $setPoints = array_filter($setPoints, function ($element) {
                return is_numeric($element);
            });
            $numberOfSets = (int)(count($setPoints) / 2);

            $setScores = [];
            for ($i = 1; $i <= $numberOfSets; $i++) {
                $homeScoreVar = 's' . $i . 'hp';
                $awayScoreVar = 's' . $i . 'ap';
                $setScores[] = [
                    'set' => $i,
                    'home' => $match[$homeScoreVar],
                    'away' => $match[$awayScoreVar],
                ];
            }

            $matchData[] = [
                'matchId' => $matchId,
                'isWalkover' => $match['isWalkover'],
                'groupName' => $match['groupName'],
                'dateOfMatch' => date("Y-m-d", strtotime($match['dateOfMatch'])),
                'datePlayed' => date("Y-m-d", strtotime($match['datePlayed'])),
                'homePlayerId' => $match['homePlayerId'],
                'awayPlayerId' => $match['awayPlayerId'],
                'homePlayerName' => $match['homePlayerName'],
                'awayPlayerName' => $match['awayPlayerName'],
                'homePlayerDisplayName' => $match['homePlayerDisplayName'] ? : $match['homePlayerName'],
                'awayPlayerDisplayName' => $match['awayPlayerDisplayName'] ? : $match['awayPlayerName'],
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'numberOfSets' => $numberOfSets,
                'scores' => $setScores,
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @return array
     */
    public function loadByTournamentId($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'order by g.date_of_match desc';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $match) {
            $matchId = $match['id'];

            $setPoints = [
                $match['s1hp'],
                $match['s1ap'],
                $match['s2hp'],
                $match['s2ap'],
                $match['s3hp'],
                $match['s3ap'],
                $match['s4hp'],
                $match['s4ap'],
            ];
            $setPoints = array_filter($setPoints, function ($element) {
                return is_numeric($element);
            });
            $numberOfSets = (int)(count($setPoints) / 2);

            $setScores = [];
            for ($i = 1; $i <= $numberOfSets; $i++) {
                $homeScoreVar = 's' . $i . 'hp';
                $awayScoreVar = 's' . $i . 'ap';
                $setScores[] = [
                    'set' => $i,
                    'home' => $match[$homeScoreVar],
                    'away' => $match[$awayScoreVar],
                ];
            }

            $matchData[] = [
                'matchId' => $matchId,
                'groupName' => $match['groupName'],
                'dateOfMatch' => $match['dateOfMatch'],
                'homePlayerId' => $match['homePlayerId'],
                'awayPlayerId' => $match['awayPlayerId'],
                'homePlayerName' => $match['homePlayerName'],
                'awayPlayerName' => $match['awayPlayerName'],
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'numberOfSets' => $numberOfSets,
                'maxSets' => $match['maxSets'],
                'scores' => $setScores,
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @param null $limit
     * @return array
     */
    public function loadUpcomingFixturesByTournamentId($id, $limit = null)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentid ';
        $baseSql .= 'and g.is_finished = 0 ';
        $baseSql .= 'order by g.date_of_match, g.id asc ';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_numeric($limit) && $limit) {
            $result = array_slice($result, 0, $limit);
        }

        foreach ($result as $match) {
            $matchId = $match['id'];

            $setPoints = [
                $match['s1hp'],
                $match['s1ap'],
                $match['s2hp'],
                $match['s2ap'],
                $match['s3hp'],
                $match['s3ap'],
                $match['s4hp'],
                $match['s4ap'],
            ];
            $setPoints = array_filter($setPoints, function ($element) {
                return is_numeric($element);
            });
            $numberOfSets = (int)(count($setPoints) / 2);

            $setScores = [];
            for ($i = 1; $i <= $numberOfSets; $i++) {
                $homeScoreVar = 's' . $i . 'hp';
                $awayScoreVar = 's' . $i . 'ap';
                $setScores[] = [
                    'set' => $i,
                    'home' => $match[$homeScoreVar],
                    'away' => $match[$awayScoreVar],
                ];
            }

            $matchData[] = [
                'matchId' => $matchId,
                'groupName' => $match['groupName'],
                'dateOfMatch' => date("D M j", strtotime($match['dateOfMatch'])),
                'homePlayerId' => $match['homePlayerId'],
                'awayPlayerId' => $match['awayPlayerId'],
                'homePlayerName' => $match['homePlayerName'],
                'awayPlayerName' => $match['awayPlayerName'],
                'homePlayerDisplayName' => $match['homePlayerDisplayName'] ? : $match['homePlayerName'],
                'awayPlayerDisplayName' => $match['awayPlayerDisplayName'] ? : $match['awayPlayerName'],
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'numberOfSets' => $numberOfSets,
                'scores' => $setScores,
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @return array
     */
    public function getFullScheduleByTournamentId($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'and g.is_finished = 0 ';
        $baseSql .= 'order by g.date_of_match, g.id asc';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $match) {
            $matchId = $match['id'];

            $setPoints = [
                $match['s1hp'],
                $match['s1ap'],
                $match['s2hp'],
                $match['s2ap'],
                $match['s3hp'],
                $match['s3ap'],
                $match['s4hp'],
                $match['s4ap'],
            ];
            $setPoints = array_filter($setPoints, function ($element) {
                return is_numeric($element);
            });
            $numberOfSets = (int)(count($setPoints) / 2);

            $setScores = [];
            for ($i = 1; $i <= $numberOfSets; $i++) {
                $homeScoreVar = 's' . $i . 'hp';
                $awayScoreVar = 's' . $i . 'ap';
                $setScores[] = [
                    'set' => $i,
                    'home' => $match[$homeScoreVar],
                    'away' => $match[$awayScoreVar],
                ];
            }

            $matchData[] = [
                'matchId' => $matchId,
                'groupName' => $match['groupName'],
                'dateOfMatch' => $match['dateOfMatch'],
                'homePlayerId' => $match['homePlayerId'],
                'awayPlayerId' => $match['awayPlayerId'],
                'homePlayerName' => $match['homePlayerName'],
                'awayPlayerName' => $match['awayPlayerName'],
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'numberOfSets' => $numberOfSets,
                'scores' => $setScores,
            ];
        }

        return $matchData;
    }


    /**
     * @param $tournamentId
     * @param $player1Id
     * @param $player2Id
     * @return bool
     */
    public function validateTournamentGroupPlayer($tournamentId, $player1Id, $player2Id)
    {
        $sql = 'select ptg1.group_id as p1groupId, ptg2.group_id as p2groupId ' .
            'from player_tournament_group ptg1 ' .
            'join player_tournament_group ptg2 on ptg2.tournament_id = ptg1.tournament_id ' .
            'where ptg1.tournament_id = :tournamentId ' .
            'and ptg1.player_id = :player1 ' .
            'and ptg2.player_id = :player2';

        $params['tournamentId'] = $tournamentId;
        $params['player1'] = $player1Id;
        $params['player2'] = $player2Id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['p1groupId'] != $result['p2groupId']) {
            $group = null;
        } else {
            $group = $result['p1groupId'];
        }

        return $group;
    }

    /**
     * @param $id
     * @return array
     */
    public function loadById($id)
    {
        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.id = :gameId ';

        $params = [
            'gameId' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $matchId = $result['id'];
        $setPoints = [
            $result['s1hp'],
            $result['s1ap'],
            $result['s2hp'],
            $result['s2ap'],
            $result['s3hp'],
            $result['s3ap'],
            $result['s4hp'],
            $result['s4ap'],
        ];
        $setPoints = array_filter($setPoints, function ($element) {
            return is_numeric($element);
        });
        $numberOfSets = (int)(count($setPoints) / 2);

        $setScores = [];
        for ($i = 1; $i <= $numberOfSets; $i++) {
            $homeScoreVar = 's' . $i . 'hp';
            $awayScoreVar = 's' . $i . 'ap';
            $setScores[] = [
                'set' => $i,
                'home' => $result[$homeScoreVar],
                'away' => $result[$awayScoreVar],
            ];
        }

        $matchData = [
            'matchId' => $matchId,
            'groupName' => $result['groupName'],
            'dateOfMatch' => date("D M j", strtotime($result['dateOfMatch'])),
            'homePlayerId' => $result['homePlayerId'],
            'awayPlayerId' => $result['awayPlayerId'],
            'homePlayerName' => $result['homePlayerName'],
            'awayPlayerName' => $result['awayPlayerName'],
            'homePlayerDisplayName' => $result['homePlayerDisplayName'] ? : $result['homePlayerName'],
            'awayPlayerDisplayName' => $result['awayPlayerDisplayName'] ? : $result['awayPlayerName'],
            'winnerId' => $result['winnerId'] ?: 0,
            'isFinished' => $result['isFinished'] ?: 0,
            'homeScoreTotal' => $result['homeScoreTotal'],
            'awayScoreTotal' => $result['awayScoreTotal'],
            'numberOfSets' => $numberOfSets,
            'scores' => $setScores,
        ];


        return $matchData;
    }

    public function updateScores($matchId, $setNumber)
    {
        $baseSql = 'update scores s ' .
                   'set ' .
                   's.home_points = (select sum(p.is_home_point) as homePoints from points p where p.score_id = s.id), ' .
                   's.away_points = (select sum(p.is_away_point) as awayPoints from points p where p.score_id = s.id) ' .
                   'where s.set_number = :setNumber and s.game_id = :gameId';

        $params = [
            'gameId' => $matchId,
            'setNumber' => $setNumber,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt-> execute($params);
    }
}
