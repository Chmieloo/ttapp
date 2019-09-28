<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\TournamentGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
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
    public function basePlayoffsQuery()
    {
        $sql =
            'select g.home_player_id as hpid, g.away_player_id as apid, ' .
            'g.play_order as playOrder, g.name as matchName, g.id, gm.name, g.winner_id as winnerId, p1.name homePlayerName, p2.name as awayPlayerName, ' .
            'p1.id as homePlayerId, p2.id awayPlayerId, gm.max_sets as maxSets, g.is_finished as isFinished, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal, g.is_walkover as isWalkover, ' .
            's1.home_points as s1hp, s1.away_points s1ap, ' .
            's2.home_points as s2hp, s2.away_points s2ap, ' .
            's3.home_points as s3hp, s3.away_points s3ap, ' .
            's4.home_points as s4hp, s4.away_points s4ap, ' .
            's5.home_points as s5hp, s5.away_points s5ap, ' .
            's6.home_points as s6hp, s6.away_points s6ap, ' .
            's7.home_points as s7hp, s7.away_points s7ap, ' .
            'p1.display_name as homePlayerDisplayName, ' .
            'g.tournament_id as tournamentId, ' .
            'p2.display_name as awayPlayerDisplayName, g.server_id as serverId, current_set as currentSet, ' .
            'p1.slack_name as homeSlackName, p2.slack_name as awaySlackName, ' .
            'tg.name as groupName, g.date_of_match as dateOfMatch, g.date_played as datePlayed, g.is_finished as isFinished, ' .
            'sum(pp.is_home_point) as currentHomePoints, sum(pp.is_away_point) as currentAwayPoints, ' .
            'if (g.home_player_id, p1.name, g.playoff_home_player_id) as homePlayerDisplayName, ' .
            'if (g.away_player_id, p2.name, g.playoff_away_player_id) as awayPlayerDisplayName ' .
            'from game g ' .
            'join game_mode gm on gm.id = g.game_mode_id ' .
            'left join player p1 on p1.id = g.home_player_id ' .
            'left join player p2 on p2.id = g.away_player_id ' .
            'left join tournament_group tg on tg.id = g.tournament_group_id ' .
            'left join scores s1 on s1.game_id = g.id and s1.set_number = 1 ' .
            'left join scores s2 on s2.game_id = g.id and s2.set_number = 2 ' .
            'left join scores s3 on s3.game_id = g.id and s3.set_number = 3 ' .
            'left join scores s4 on s4.game_id = g.id and s4.set_number = 4 ' .
            'left join scores s5 on s5.game_id = g.id and s5.set_number = 5 ' .
            'left join scores s6 on s6.game_id = g.id and s6.set_number = 6 ' .
            'left join scores s7 on s7.game_id = g.id and s7.set_number = 7 ' .
            'left join scores ss on ss.game_id = g.id and g.current_set = ss.set_number ' .
            'left join points pp on pp.score_id = ss.id ';

        return $sql;
    }

    /**
     * Base sql string
     * @return string
     */
    public function baseQuery()
    {
        $sql =
            'select g.play_order as playOrder, g.name as matchName, g.id, gm.name, g.winner_id as winnerId, p1.name homePlayerName, p2.name as awayPlayerName, ' .
            'g.old_home_elo ohElo, g.new_home_elo nhElo, g.old_away_elo oaElo, g.new_away_elo naElo, ' .
            'p1.id as homePlayerId, p2.id awayPlayerId, gm.max_sets as maxSets, g.is_finished as isFinished, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal, g.is_walkover as isWalkover, ' .
            'g.home_player_id as hpid, g.away_player_id as apid, ' .
            's1.home_points as s1hp, s1.away_points s1ap, ' .
            's2.home_points as s2hp, s2.away_points s2ap, ' .
            's3.home_points as s3hp, s3.away_points s3ap, ' .
            's4.home_points as s4hp, s4.away_points s4ap, ' .
            's5.home_points as s5hp, s5.away_points s5ap, ' .
            's6.home_points as s6hp, s6.away_points s6ap, ' .
            's7.home_points as s7hp, s7.away_points s7ap, ' .
            'g.tournament_id as tournamentId, ' .
            'g.server_id as serverId, current_set as currentSet, ' .
            'p1.slack_name as homeSlackName, p2.slack_name as awaySlackName, ' .
            'tg.name as groupName, g.date_of_match as dateOfMatch, g.date_played as datePlayed, g.is_finished as isFinished, ' .
            'sum(pp.is_home_point) as currentHomePoints, sum(pp.is_away_point) as currentAwayPoints, ' .
            'if(count(p2s.id) > 0, 1, 0) as pts, ' .
            'if (g.home_player_id, p1.name, g.playoff_home_player_id) as homePlayerDisplayName, ' .
            'if (g.away_player_id, p2.name, g.playoff_away_player_id) as awayPlayerDisplayName ' .
            'from game g ' .
            'join game_mode gm on gm.id = g.game_mode_id ' .
            'left join player p1 on p1.id = g.home_player_id ' .
            'left join player p2 on p2.id = g.away_player_id ' .
            'left join tournament_group tg on tg.id = g.tournament_group_id ' .
            'left join scores s1 on s1.game_id = g.id and s1.set_number = 1 ' .
            'left join scores s2 on s2.game_id = g.id and s2.set_number = 2 ' .
            'left join scores s3 on s3.game_id = g.id and s3.set_number = 3 ' .
            'left join scores s4 on s4.game_id = g.id and s4.set_number = 4 ' .
            'left join scores s5 on s5.game_id = g.id and s5.set_number = 5 ' .
            'left join scores s6 on s6.game_id = g.id and s6.set_number = 6 ' .
            'left join scores s7 on s7.game_id = g.id and s7.set_number = 7 ' .
            'left join scores ss on ss.game_id = g.id and g.current_set = ss.set_number ' .
            'left join points pp on pp.score_id = ss.id ' .
            'left join scores s2p on s2p.game_id = g.id ' .
            'left join points p2s on p2s.score_id = s2p.id ';

        return $sql;
    }

    /**
     * @param $id
     * @param null $limit
     * @return array
     */
    public function loadOverdueFixturesByTournamentId($id, $limit = null)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentid ';
        $baseSql .= 'and g.is_finished = 0 and g.date_of_match < now() ';
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match, g.id asc ';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_numeric($limit) && $limit) {
            $result = array_slice($result, 0, $limit);
        }

        foreach ($result as $match) {
            $matchId = $match['id'];

            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'homePlayerDisplayName' => $match['homePlayerDisplayName'] ?: $match['homePlayerName'],
                'awayPlayerDisplayName' => $match['awayPlayerDisplayName'] ?: $match['awayPlayerName'],
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
     * @param int $limit
     * @return array
     */
    public function loadLastResultsByTournamentId($id, $limit = null)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'and g.is_finished = 1 ';
        $baseSql .= 'group by g.id ';
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
            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'homePlayerDisplayName' => $match['homePlayerDisplayName'] ?: $match['homePlayerName'],
                'awayPlayerDisplayName' => $match['awayPlayerDisplayName'] ?: $match['awayPlayerName'],
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'numberOfSets' => $numberOfSets,
                'scores' => $setScores,
                'pts' => $match['pts'],
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
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match desc';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $match) {
            $matchId = $match['id'];
            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'isFinished' => $match['isFinished'],
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
    public function loadPlayoffsFixturesByTournamentId($id, $limit = null)
    {
        $matchData = [];

        $baseSql =
            'select g.home_player_id as hpid, g.away_player_id as apid, ' .
            'if (g.home_player_id, p1.name, g.playoff_home_player_id) as homePlayerDisplayName, ' .
            'if (g.away_player_id, p2.name, g.playoff_away_player_id) as awayPlayerDisplayName, ' .
            'g.date_of_match as dateOfMatch, g.id, g.play_order as matchNumber, l.id as groupId, l.name as division, g.name, ' .
            'g.playoff_home_player_id as homePlayer, g.playoff_away_player_id as awayPlayer, g.winner_id as winnerId, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal ' .
            'from game g ' .
            'left join player p1 on p1.id = g.home_player_id ' .
            'left join player p2 on p2.id = g.away_player_id ' .
            'join game_mode gm on gm.id = g.game_mode_id ' .
            'join tournament t on t.id = g.tournament_id ' .
            'join tournament_group l on l.id = g.tournament_group_id ' .
            'where t.is_finished = 0 and t.id = :tournamentid ' .
            'order by g.play_order';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_numeric($limit) && $limit) {
            $result = array_slice($result, 0, $limit);
        }

        /** @var TournamentGroupRepository $tournamentGroupRepository */
        $tournamentGroupRepository = $this->getEntityManager()->getRepository(TournamentGroup::class);

        foreach ($result as $match) {
            $matchId = $match['id'];

            $homePlayerString = '';
            $awayPlayerString = '';

            if (!$match['hpid']) {
                if (strpos($match['homePlayerDisplayName'], '.') !== false) {
                    $homePlayerData = explode('.', $match['homePlayerDisplayName']);
                    if ($homePlayerData[0] == 'W') {
                        $homePlayerString .= 'Winner, #' . $homePlayerData[1];
                    } elseif ($homePlayerData[0] == 'L') {
                        $homePlayerString .= 'Loser, #' . $homePlayerData[1];
                    } else {
                        $homePlayerString = $tournamentGroupRepository->find((int)$homePlayerData[0])->getName() .
                            ', pos. ' . $homePlayerData[1];
                    }
                }
            } else {
                $homePlayerString = $match['homePlayerDisplayName'];
            }

            if (!$match['apid']) {
                if (strpos($match['awayPlayerDisplayName'], '.') !== false) {
                    $awayPlayerData = explode('.', $match['awayPlayerDisplayName']);
                    if ($awayPlayerData[0] == 'W') {
                        $awayPlayerString .= 'Winner, #' . $awayPlayerData[1];
                    } elseif ($awayPlayerData[0] == 'L') {
                        $awayPlayerString .= 'Loser, #' . $awayPlayerData[1];
                    } else {
                        $awayPlayerString = $tournamentGroupRepository->find((int)$awayPlayerData[0])->getName() .
                            ', pos. ' . $awayPlayerData[1];
                    }
                }
            } else {
                $awayPlayerString = $match['awayPlayerDisplayName'];
            }

            $matchData[] = [
                'order' => $match['matchNumber'],
                'matchId' => $matchId,
                'division' => $match['division'],
                'name' => $match['name'],
                'dateOfMatch' => date("D M j", strtotime($match['dateOfMatch'])),
                'homePlayerId' => $match['hpid'],
                'awayPlayerId' => $match['apid'],
                'homePlayerDisplayName' => $homePlayerString ?: 'TBD',
                'awayPlayerDisplayName' => $awayPlayerString ?: 'TBD',
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @param $divisionId
     * @return array
     * @throws DBALException
     */
    public function loadPlayoffsFixturesByTournamentIdAndDivision($id, $divisionId)
    {
        $matchData = [];

        $baseSql =
            'select g.home_player_id as hpid, g.away_player_id as apid, g.stage, g.is_walkover as isWalkover, ' .
            'if (g.home_player_id, p1.name, g.playoff_home_player_id) as homePlayerDisplayName, ' .
            'if (g.away_player_id, p2.name, g.playoff_away_player_id) as awayPlayerDisplayName, ' .
            'g.date_of_match as dateOfMatch, g.id, g.play_order as matchNumber, l.id as groupId, l.name as division, g.name, ' .
            'g.playoff_home_player_id as homePlayer, g.playoff_away_player_id as awayPlayer, g.winner_id as winnerId, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal ' .
            'from game g ' .
            'left join player p1 on p1.id = g.home_player_id ' .
            'left join player p2 on p2.id = g.away_player_id ' .
            'join game_mode gm on gm.id = g.game_mode_id ' .
            'join tournament t on t.id = g.tournament_id ' .
            'join tournament_group l on l.id = g.tournament_group_id ' .
            'where t.id = :tournamentid and l.id = :groupId ' .
            'order by g.stage asc, g.play_order desc';

        $params = [
            'tournamentid' => $id,
            'groupId' => $divisionId,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** @var TournamentGroupRepository $tournamentGroupRepository */
        $tournamentGroupRepository = $this->getEntityManager()->getRepository(TournamentGroup::class);

        foreach ($result as $match) {
            $matchId = $match['id'];

            $homePlayerString = '';
            $awayPlayerString = '';

            if (!$match['hpid']) {
                if (strpos($match['homePlayerDisplayName'], '.') !== false) {
                    $homePlayerData = explode('.', $match['homePlayerDisplayName']);
                    if ($homePlayerData[0] == 'W') {
                        $homePlayerString .= 'Winner, #' . $homePlayerData[1];
                    } elseif ($homePlayerData[0] == 'L') {
                        $homePlayerString .= 'Loser, #' . $homePlayerData[1];
                    } else {
                        $homePlayerString = $tournamentGroupRepository->find((int)$homePlayerData[0])->getName() .
                            ', pos. ' . $homePlayerData[1];
                    }
                }
            } else {
                $homePlayerString = $match['homePlayerDisplayName'];
            }

            if (!$match['apid']) {
                if (strpos($match['awayPlayerDisplayName'], '.') !== false) {
                    $awayPlayerData = explode('.', $match['awayPlayerDisplayName']);
                    if ($awayPlayerData[0] == 'W') {
                        $awayPlayerString .= 'Winner, #' . $awayPlayerData[1];
                    } elseif ($awayPlayerData[0] == 'L') {
                        $awayPlayerString .= 'Loser, #' . $awayPlayerData[1];
                    } else {
                        $awayPlayerString = $tournamentGroupRepository->find((int)$awayPlayerData[0])->getName() .
                            ', pos. ' . $awayPlayerData[1];
                    }
                }
            } else {
                $awayPlayerString = $match['awayPlayerDisplayName'];
            }

            $matchData[] = [
                'order' => $match['matchNumber'],
                'matchId' => $matchId,
                'division' => $match['division'],
                'name' => $match['name'],
                'stage' => $match['stage'],
                'maxStage' => 4,
                'dateOfMatch' => date("D M j", strtotime($match['dateOfMatch'])),
                'homePlayerId' => $match['hpid'],
                'awayPlayerId' => $match['apid'],
                'homePlayerDisplayName' => $homePlayerString ?: 'TBD',
                'awayPlayerDisplayName' => $awayPlayerString ?: 'TBD',
                'winnerId' => $match['winnerId'] ?: 0,
                'homeScoreTotal' => $match['homeScoreTotal'],
                'awayScoreTotal' => $match['awayScoreTotal'],
                'isWalkover' => (int)$match['isWalkover'],
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
        $baseSql .= 'and g.is_finished = 0 and g.date_of_match >= now() ';
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match, g.id asc ';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_numeric($limit) && $limit) {
            $result = array_slice($result, 0, $limit);
        }

        foreach ($result as $match) {
            $matchId = $match['id'];
            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'tournamentId' => $id,
                'matchId' => $matchId,
                'groupName' => $match['groupName'],
                'timeOfMatch' => date("H:i", strtotime($match['dateOfMatch'])),
                'dateOfMatch' => date("M j", strtotime($match['dateOfMatch'])),
                'homePlayerId' => $match['homePlayerId'],
                'awayPlayerId' => $match['awayPlayerId'],
                'homePlayerName' => $match['homePlayerName'],
                'awayPlayerName' => $match['awayPlayerName'],
                'homePlayerDisplayName' => $match['homePlayerDisplayName'] ?: $match['homePlayerName'],
                'awayPlayerDisplayName' => $match['awayPlayerDisplayName'] ?: $match['awayPlayerName'],
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
     * @throws DBALException
     */
    public function loadOverdueForToday($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentid ';
        $baseSql .= 'and g.is_finished = 0 and DATE(g.date_of_match) < DATE(now()) ';
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match, g.id asc ';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        foreach ($result as $match) {
            $matchId = $match['id'];

            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'timeOfMatch' => date("H:i", strtotime($match['dateOfMatch'])),
                'homeSlackName' => $match['homeSlackName'] ?: $match['homePlayerName'],
                'awaySlackName' => $match['awaySlackName'] ?: $match['awayPlayerName'],
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @return array
     * @throws DBALException
     */
    public function loadScheduleForToday($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentid ';
        $baseSql .= 'and g.is_finished = 0 and DATE(g.date_of_match) = DATE(CURDATE()) ';
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match, g.id asc ';

        $params = [
            'tournamentid' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $match) {
            $matchId = $match['id'];
            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
                'timeOfMatch' => date("H:i", strtotime($match['dateOfMatch'])),
                'homeSlackName' => $match['homeSlackName'] ?: $match['homePlayerName'],
                'awaySlackName' => $match['awaySlackName'] ?: $match['awayPlayerName'],
            ];
        }

        return $matchData;
    }

    /**
     * @param $id
     * @return array
     * @throws DBALException
     */
    public function getFullScheduleByTournamentId($id)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.tournament_id = :tournamentId ';
        $baseSql .= 'and g.is_finished = 0 ';
        $baseSql .= 'group by g.id ';
        $baseSql .= 'order by g.date_of_match, g.id asc';

        $params['tournamentId'] = $id;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $match) {
            $matchId = $match['id'];
            $numberOfSets = $match['isFinished'] == 1 ?
                $match['homeScoreTotal'] + $match['awayScoreTotal'] :
                $match['currentSet'];

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
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['p1groupId'] != $result['p2groupId']) {
            $group = null;
        } else {
            $group = $result['p1groupId'];
        }

        return $group;
    }

    public function updatePlayoffs($matchId)
    {
        /** @var Game $match */
        $match = $this->loadById($matchId);

        if ($match['winnerId']) {
            $homePlayerId = $match['homePlayerId'];
            $awayPlayerId = $match['awayPlayerId'];

            if ($match['winnerId'] == $homePlayerId) {
                $winnerId = $homePlayerId;
                $loserId = $awayPlayerId;
            } else {
                $winnerId = $awayPlayerId;
                $loserId = $homePlayerId;
            }

            $tournamentId = $match['tournamentId'];
            $matchNumber = $match['playOrder'];

            $em = $this->getEntityManager();

            // Update next matches in current tournament

            // 1. Winner as home
            $query = 'update game g set g.home_player_id = :winnerId where g.playoff_home_player_id = :code and g.tournament_id = :tournamentId';
            $params = [
                'winnerId' => $winnerId,
                'code' => 'W.' . $matchNumber,
                'tournamentId' => $tournamentId
            ];
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute($params);

            // 2. Winner as away
            $query = 'update game g set g.away_player_id = :winnerId where g.playoff_away_player_id = :code and g.tournament_id = :tournamentId';
            $params = [
                'winnerId' => $winnerId,
                'code' => 'W.' . $matchNumber,
                'tournamentId' => $tournamentId
            ];
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute($params);

            // 3. Loser as home
            $query = 'update game g set g.home_player_id = :loserId where g.playoff_home_player_id = :code and g.tournament_id = :tournamentId';
            $params = [
                'loserId' => $loserId,
                'code' => 'L.' . $matchNumber,
                'tournamentId' => $tournamentId
            ];
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute($params);

            $query = 'update game g set g.away_player_id = :loserId where g.playoff_away_player_id = :code and g.tournament_id = :tournamentId';
            $params = [
                'loserId' => $loserId,
                'code' => 'L.' . $matchNumber,
                'tournamentId' => $tournamentId
            ];
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute($params);
        }
    }

    public function loadAllOrdered()
    {
        $sql = "select 
                g.id,
                home_player_id,
                away_player_id,
                tournament_id,
                winner_id,
                home_score,
                away_score,
                old_home_elo,
                old_away_elo,
                new_home_elo,
                new_away_elo
            from game g join tournament t on g.tournament_id = t.id and t.is_official = 1
            where g.is_finished = 1 
            order by g.tournament_id, g.date_played, g.date_of_match, g.id asc";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function loadById($id)
    {
        /** @var TournamentGroupRepository $tournamentGroupRepository */
        $tournamentGroupRepository = $this->getEntityManager()->getRepository(TournamentGroup::class);

        $baseSql = $this->baseQuery();
        $baseSql .= 'where g.id = :gameId ';

        $params = [
            'gameId' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $prettyScore =
            $result['homeScoreTotal'] . ' - ' . $result['awayScoreTotal'] . ' (';


        $matchId = $result['id'];

        $numberOfSets = $result['isFinished'] == 1 ?
            $result['homeScoreTotal'] + $result['awayScoreTotal'] :
            $result['currentSet'];

        $setScores = [];
        for ($i = 1; $i <= $numberOfSets; $i++) {
            $homeScoreVar = 's' . $i . 'hp';
            $awayScoreVar = 's' . $i . 'ap';
            $setScores[] = [
                'set' => $i,
                'home' => $result[$homeScoreVar],
                'away' => $result[$awayScoreVar],
            ];

            $prettyScore .= $result[$homeScoreVar] . '-' . $result[$awayScoreVar] . ', ';
        }

        $prettyScore = trim($prettyScore, ', ') . ')';

        $nextMatchId = null;
        $nextMatchName = '';
        $nextMatchHomePlayer = '';
        $nextMatchAwayPlayer = '';
        if ($result['playOrder']) {
            $nextInOrder = $result['playOrder'] + 1;
            $tournamentId = $result['tournamentId'];
            // Load next match for this tournament
            $nextMatch = $this->loadNextInPlayoffs($nextInOrder, $tournamentId);

            if ($nextMatch) {
                $nextMatchId = $nextMatch['nextMatchId'];
                $nextMatchName = $nextMatch['matchName'];
                $nextMatchHomePlayer = $nextMatch['nextMatchHomePlayer'];
                $nextMatchAwayPlayer = $nextMatch['nextMatchAwayPlayer'];
            }
        }

        $homePlayerString = '';
        $awayPlayerString = '';

        if (!$result['hpid']) {
            if (strpos($result['homePlayerDisplayName'], '.') !== false) {
                $homePlayerData = explode('.', $result['homePlayerDisplayName']);
                if ($homePlayerData[0] == 'W') {
                    $homePlayerString .= 'Winner, #' . $homePlayerData[1];
                } elseif ($homePlayerData[0] == 'L') {
                    $homePlayerString .= 'Loser, #' . $homePlayerData[1];
                } else {
                    $homePlayerString = $tournamentGroupRepository->find((int)$homePlayerData[0])->getName() .
                        ', pos. ' . $homePlayerData[1];
                }
            }
        } else {
            $homePlayerString = $result['homePlayerDisplayName'];
        }

        if (!$result['apid']) {
            if (strpos($result['awayPlayerDisplayName'], '.') !== false) {
                $awayPlayerData = explode('.', $result['awayPlayerDisplayName']);
                if ($awayPlayerData[0] == 'W') {
                    $awayPlayerString .= 'Winner, #' . $awayPlayerData[1];
                } elseif ($awayPlayerData[0] == 'L') {
                    $awayPlayerString .= 'Loser, #' . $awayPlayerData[1];
                } else {
                    $awayPlayerString = $tournamentGroupRepository->find((int)$awayPlayerData[0])->getName() .
                        ', pos. ' . $awayPlayerData[1];
                }
            }
        } else {
            $awayPlayerString = $result['awayPlayerDisplayName'];
        }

        $homeElo = $result['nhElo'] - $result['ohElo'];
        $awayElo = $result['naElo'] - $result['oaElo'];

        $matchData = [
            'matchId' => $matchId,
            'tournamentId' => $result['tournamentId'],
            'playOrder' => $result['playOrder'],
            'modeName' => $result['name'],
            'matchName' => $result['matchName'],
            'serverId' => $result['serverId'],
            'maxSets' => $result['maxSets'],
            'currentSet' => $result['currentSet'],
            'groupName' => $result['groupName'],
            'dateOfMatch' => date("D M j", strtotime($result['dateOfMatch'])),
            'homePlayerId' => $result['homePlayerId'],
            'awayPlayerId' => $result['awayPlayerId'],
            'homePlayerName' => $result['homePlayerName'],
            'awayPlayerName' => $result['awayPlayerName'],
            'homePlayerDisplayName' => $homePlayerString ?? $result['homePlayerDisplayName'] ?? $result['homePlayerName'],
            'awayPlayerDisplayName' => $awayPlayerString ?? $result['awayPlayerDisplayName'] ?? $result['awayPlayerName'],
            'winnerId' => $result['winnerId'] ?: 0,
            'isFinished' => (int)$result['isFinished'] ?: 0,
            'homeScoreTotal' => $result['homeScoreTotal'],
            'awayScoreTotal' => $result['awayScoreTotal'],
            'numberOfSets' => $numberOfSets,
            'scores' => $setScores,
            'prettyScore' => $prettyScore,
            'homeSlackName' => $result['homeSlackName'],
            'awaySlackName' => $result['awaySlackName'],
            'currentHomePoints' => $result['currentHomePoints'],
            'currentAwayPoints' => $result['currentAwayPoints'],
            'nextMatchName' => $nextMatchName,
            'nextMatchId' => $nextMatchId,
            'nextMatchHomePlayer' => $nextMatchHomePlayer,
            'nextMatchAwayPlayer' => $nextMatchAwayPlayer,
            'oldHomeElo' => $result['ohElo'],
            'newHomeElo' => $result['nhElo'],
            'oldAwayElo' => $result['oaElo'],
            'newAwayElo' => $result['naElo'],
            'homeElo' => $homeElo >= 0 ? '+' . $homeElo : $homeElo,
            'awayElo' => $awayElo >= 0 ? '+' . $awayElo : $awayElo,
        ];


        return $matchData;
    }

    /**
     * @param $order
     * @param $tournamentId
     * @return mixed
     */
    public function loadNextInPlayoffs($order, $tournamentId)
    {
        /** @var TournamentGroupRepository $tournamentGroupRepository */
        $tournamentGroupRepository = $this->getEntityManager()->getRepository(TournamentGroup::class);

        $baseSql = $this->basePlayoffsQuery();
        $baseSql .= 'where g.play_order = :order and g.tournament_id = :tournamentId ';

        $params = [
            'order' => $order,
            'tournamentId' => $tournamentId
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);
        $homePlayerString = '';
        $awayPlayerString = '';

        if (!$match['hpid']) {
            if (strpos($match['homePlayerDisplayName'], '.') !== false) {
                $homePlayerData = explode('.', $match['homePlayerDisplayName']);
                if ($homePlayerData[0] == 'W') {
                    $homePlayerString .= 'Winner, #' . $homePlayerData[1];
                } elseif ($homePlayerData[0] == 'L') {
                    $homePlayerString .= 'Loser, #' . $homePlayerData[1];
                } else {
                    $homePlayerString = $tournamentGroupRepository->find((int)$homePlayerData[0])->getName() .
                        ', pos. ' . $homePlayerData[1];
                }
            }
        } else {
            $homePlayerString = $match['homePlayerDisplayName'];
        }

        if (!$match['apid']) {
            if (strpos($match['awayPlayerDisplayName'], '.') !== false) {
                $awayPlayerData = explode('.', $match['awayPlayerDisplayName']);
                if ($awayPlayerData[0] == 'W') {
                    $awayPlayerString .= 'Winner, #' . $awayPlayerData[1];
                } elseif ($awayPlayerData[0] == 'L') {
                    $awayPlayerString .= 'Loser, #' . $awayPlayerData[1];
                } else {
                    $awayPlayerString = $tournamentGroupRepository->find((int)$awayPlayerData[0])->getName() .
                        ', pos. ' . $awayPlayerData[1];
                }
            }
        } else {
            $awayPlayerString = $match['awayPlayerDisplayName'];
        }

        $match['nextMatchId'] = $match['id'];
        $match['nextMatchHomePlayer'] = $homePlayerString;
        $match['nextMatchAwayPlayer'] = $awayPlayerString;

        return $match;
    }

    /**
     * @param $scoreId
     * @return bool
     */
    public function updateScores($scoreId)
    {
        $baseSql = 'UPDATE scores s
                    INNER JOIN
                    (
                       SELECT score_id as scoreId, SUM(p.is_home_point) as homePoints, SUM(p.is_away_point) as awayPoints
                       FROM points p
                       GROUP BY score_id
                    ) i ON s.id = i.scoreId
                    SET s.home_points = i.homePoints, s.away_points = i.awayPoints
                    where s.id = :scoreId';

        $params = [
            'scoreId' => $scoreId
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        return true;
    }

    public function updateServer($matchId)
    {
        $query = 'select home_player_id, away_player_id, server_id from game g where g.id = :matchId';
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute([
            'matchId' => $matchId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $newServer = $result['server_id'] == $result['home_player_id'] ?
            $result['away_player_id'] :
            $result['home_player_id'];

        $query = 'UPDATE game g
                    SET g.server_id = :serverId
                    where g.id = :matchId';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute([
            'matchId' => $matchId,
            'serverId' => $newServer
        ]);

        $match = $this->loadById($matchId);

        return $match;
    }

    /**
     * @param $gameId
     * @param $oldHomeElo
     * @param $oldAwayElo
     * @param $newHomeElo
     * @param $newAwayElo
     * @return bool
     * @throws DBALException
     */
    public function updateGameElo($gameId, $oldHomeElo, $oldAwayElo, $newHomeElo, $newAwayElo)
    {
        $baseSql = 'UPDATE game g
                    SET 
                    g.old_home_elo = :oldHomeElo, 
                    g.old_away_elo = :oldAwayElo, 
                    g.new_home_elo = :newHomeElo, 
                    g.new_away_elo = :newAwayElo
                    where g.id = :gameId';

        $params = [
            'gameId' => $gameId,
            'oldHomeElo' => $oldHomeElo,
            'oldAwayElo' => $oldAwayElo,
            'newHomeElo' => $newHomeElo,
            'newAwayElo' => $newAwayElo,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        return true;
    }

    public function updatePlayerElo($playerId, $elo)
    {
        $baseSql = 'UPDATE player p
                    SET  
                    p.tournament_elo = :elo
                    where p.id = :playerId';

        $params = [
            'playerId' => $playerId,
            'elo' => $elo,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($baseSql);
        $stmt->execute($params);

        return true;
    }

    public function loadTimelineById($id)
    {
        $sql = "select g.server_id as serverId, g.home_player_id as homePlayerId, g.away_player_id as awayPlayerId, g.home_score as homeTotalScore, g.away_score as awayTotalScore,
                unix_timestamp(p.time) as timestamp, 
                p1.name homeName, p2.name as awayName, s.set_number as setNumber, s.home_points as homePoints, s.away_points as awayPoints, 
                p.is_home_point as isHomePoint, p.is_away_point as isAwayPoint, tg.name as groupName, t.name as tournamentName
                from points p
                join scores s on p.score_id = s.id
                join game g on s.game_id = g.id
                join tournament_group tg on g.tournament_group_id = tg.id
                join tournament t on g.tournament_id = t.id
                join player p1 on g.home_player_id = p1.id
                join player p2 on g.away_player_id = p2.id
                where g.id = :matchId
                order by p.id";

        $params = [
            'matchId' => $id,
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $setsData = [];
        $currentSet = 1;
        $homePointsScored = 0;
        $awayPointsScored = 0;
        $currentServer = null;
        $homeStreak = '';
        $awayStreak = '';
        $lastPoint = null;

        $homeName = '';
        $awayName = '';
        $groupName = '';
        $tournamentName = '';

        $homeTotalScore = 0;
        $awayTotalScore = 0;

        $homeTotalPoints = 0;
        $awayTotalPoints = 0;

        $homeServes = $homeServesTotal = $homeOwnServePoints = $homeOwnServePointsTotal = 0;
        $awayServes = $awayServesTotal = $awayOwnServePoints = $awayOwnServePointsTotal = 0;

        $previousRallySeconds = 0;

        foreach ($data as $item) {
            $setNumber = $item['setNumber'];

            $homeTotalScore = $item['homeTotalScore'];
            $awayTotalScore = $item['awayTotalScore'];

            if ($setNumber != $currentSet) {
                # we moved to new set
                $currentSet = $setNumber;
                $homePointsScored = 0;
                $awayPointsScored = 0;

                $homeOwnServePoints = 0;
                $awayOwnServePoints = 0;

                $homeServes = 0;
                $awayServes = 0;

                $homeStreak = '';
                $awayStreak = '';
            }

            $homePlayerId = $item['homePlayerId'];
            $awayPlayerId = $item['awayPlayerId'];
            $homeName = $item['homeName'];
            $awayName = $item['awayName'];
            $groupName = $item['groupName'];
            $tournamentName = $item['tournamentName'];

            $homePoints = $item['homePoints'];
            $awayPoints = $item['awayPoints'];

            $isHomePoint = $item['isHomePoint'];
            $isAwayPoint = $item['isAwayPoint'];
            $serverId = $item['serverId'];
            $otherServerId = ($serverId == $homePlayerId) ? $awayPlayerId : $homePlayerId;

            $homeStreak .= $isHomePoint;
            $awayStreak .= $isAwayPoint;

            $homePointsScored += $isHomePoint;
            $awayPointsScored += $isAwayPoint;

            $servers = [$serverId, $otherServerId];

            $pointsScored = $homePointsScored + $awayPointsScored;

            # check who starts serving in current set
            if ($pointsScored <= 20) {
                $currentServerIndex = (ceil($pointsScored / 2) + ($setNumber % 2)) % 2;
                $setStartingServer = $servers[($currentSet + 1) % 2];
                $currentServer = $servers[$currentServerIndex];
            } else {
                $currentServerIndex = ($pointsScored + ($setNumber % 2)) % 2;
                $setStartingServer = $servers[($currentSet + 1) % 2];
                $currentServer = $servers[$currentServerIndex];
            }

            if ($currentServer == $homePlayerId) {
                $homeServesTotal++;
                $homeServes++;
                if ($isHomePoint) {
                    $homeOwnServePointsTotal++;
                    $homeOwnServePoints++;
                }
            }

            if ($currentServer == $awayPlayerId) {
                $awayServesTotal++;
                $awayServes++;
                if ($isAwayPoint) {
                    $awayOwnServePointsTotal++;
                    $awayOwnServePoints++;
                }
            }

            # Calculate length of rally in seconds
            if ($previousRallySeconds == 0) {
                $rallySeconds = 0;
                $previousRallySeconds = $item['timestamp'];
            } else {
                $rallySeconds = $item['timestamp'] - $previousRallySeconds;
                $previousRallySeconds = $item['timestamp'];
            }

            $setsData[$currentSet]['events'][] = [
                'gameStartingServerId' => (int)$serverId,
                'currentSetStartingServer' => (int)$setStartingServer,
                'currentServer' => (int)$currentServer,
                'isHomePoint' => (int)$isHomePoint,
                'isAwayPoint' => (int)$isAwayPoint,
                'homePointsScored' => $homePointsScored,
                'awayPointsScored' => $awayPointsScored,
                'homePlayerId' => (int)$homePlayerId,
                'awayPlayerId' => (int)$awayPlayerId,
                'timestamp' => $item['timestamp'],
                'rallySeconds' => $rallySeconds,
            ];

            $setsData[$currentSet]['setSummary'] = [
                'homePoints' => (int) $homePoints,
                'awayPoints' => (int) $awayPoints,
                'homeServes' => (int) $homeServes,
                'awayServes' => (int) $awayServes,
                'homeServePoints' => $homeOwnServePoints,
                'awayServePoints' => $awayOwnServePoints,
                'homeStreak' => $homeStreak,
                'awayStreak' => $awayStreak,
            ];
        }

        foreach ($setsData as &$setData) {
            $events = $setData['events'];
            $firstEventTimestamp = current($events)['timestamp'];
            $lastEventTimestamp = end($events)['timestamp'];

            $duration = $lastEventTimestamp - $firstEventTimestamp;
            $setData['setSummary']['durationMinutes'] = (int)($duration / 60);
            $setData['setSummary']['durationSeconds'] = ($duration % 60);
            $setData['setSummary']['homeStreak'] = $this->getLongestSequence($setData['setSummary']['homeStreak'], '1');
            $setData['setSummary']['awayStreak'] = $this->getLongestSequence($setData['setSummary']['awayStreak'], '1');
            $setData['setSummary']['homeServePointsPerc'] = number_format(($setData['setSummary']['homeServePoints'] / ($setData['setSummary']['homeServes']) * 100), 2);
            $setData['setSummary']['awayServePointsPerc'] = number_format(($setData['setSummary']['awayServePoints'] / ($setData['setSummary']['awayServes']) * 100), 2);

            $homeTotalPoints += $setData['setSummary']['homePoints'];
            $awayTotalPoints += $setData['setSummary']['awayPoints'];
        }

        return [
            'matchData' => [
                'homeName' => $homeName,
                'awayName' => $awayName,
                'groupName' => $groupName,
                'tournamentName' => $tournamentName,
                'homeTotalScore' => $homeTotalScore,
                'awayTotalScore' => $awayTotalScore,
                'homeTotalPoints' => $homeTotalPoints,
                'awayTotalPoints' => $awayTotalPoints,
                'homePointsPerc' => number_format(($homeTotalPoints / ($homeTotalPoints + $awayTotalPoints) * 100), 2),
                'awayPointsPerc' => number_format(($awayTotalPoints / ($homeTotalPoints + $awayTotalPoints) * 100), 2),
                'homeServesTotal' => $homeServesTotal,
                'awayServesTotal' => $awayServesTotal,
                'homeOwnServePointsTotal' => $homeOwnServePointsTotal,
                'awayOwnServePointsTotal' => $awayOwnServePointsTotal,
                'homeServePointsPerc' => number_format(($homeOwnServePointsTotal / ($homeServesTotal) * 100), 2),
                'awayServePointsPerc' => number_format(($awayOwnServePointsTotal / ($awayServesTotal) * 100), 2),
            ],
            'setsData' => $setsData,
        ];
    }

    private function getLongestSequence($str, $c)
    {
        $len = strlen($str);
        $maximum = 0;
        $count = 0;
        for ($i = 0; $i < $len; $i++) {
            if (substr($str, $i, 1) == $c) {
                $count++;
                if ($count > $maximum) $maximum = $count;
            } else $count = 0;
        }
        return $maximum;
    }
}
