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
 * @method Game[]    findAll()
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
            'p1.id as homePlayerId, p2.id awayPlayerId, gm.max_sets as maxSets, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal, ' .
            's1.home_points as s1hp, s1.away_points s1ap, ' .
            's2.home_points as s2hp, s2.away_points s2ap, ' .
            's3.home_points as s3hp, s3.away_points s3ap, ' .
            's4.home_points as s4hp, s4.away_points s4ap, ' .
            'tg.name as groupName, g.date_of_match as dateOfMatch ' .
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
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getResultsByTournamentId($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'and g.is_finished = 1 ';
        $baseSql .= 'order by g.date_of_match asc';

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
     * @param $id
     * @return array
     */
    public function getByTournamentId($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'order by g.date_of_match asc';

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
     * @return array
     */
    public function getScheduleByTournamentId($id)
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
}
