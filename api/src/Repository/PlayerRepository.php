<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    loadAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function loadAllPlayers()
    {
        $sql = 'SELECT p.id, p.name, p.nickname, p.tournament_elo as elo, count(g.id) as gamesPlayed,
                sum(if(g.winner_id = p.id, 1, 0)) as wins,
                sum(if(g.winner_id = 0, 1, 0)) as draws, 
                sum(if(g.winner_id != 0 and g.winner_id != p.id, 1, 0)) as losses 
                from player p
                left join game g on p.id in (g.home_player_id, g.away_player_id) and g.is_finished = 1
                -- where g.is_finished = 1
                group by p.id
                order by p.name';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute();

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($players as &$player) {
            $gamesPlayed = $player['gamesPlayed'] ? :1;
            $player['elo'] = (int) $player['elo'];
            $player['winPercentage'] = number_format($player['wins'] / $gamesPlayed * 100, 2);
        }
        
        return $players;
    }
    
    public function findAllSimple()
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.tournament_elo')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $playerId
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function loadPlayerById($playerId)
    {
        $sql = 'select p.id, p.name, count(g.id) as played, p.profile_pic_url as pic, p.tournament_elo as elo, ' .
               'sum(if(p.id = g.winner_id, 1, 0)) as wins, ' .
               'sum(if(g.winner_id = 0, 1, 0)) as draws, ' .
               'sum(if(g.winner_id != 0 and g.winner_id != p.id, 1, 0)) as losses ' .
               'from player p ' .
               'left join game g on p.id in (g.home_player_id, g.away_player_id) ' .
               'join tournament t on g.tournament_id = t.id ' .
               'where p.id = :playerId  ' .
               'and t.is_official = 1 and g.is_finished = 1 ' .
               'group by p.id';

        $params['playerId'] = $playerId;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);
        $player = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$player) {
            $player['winPercentage'] = 0;
            $player['notWinPercentage'] = 100;
            $player['drawPercentage'] = 0;
            $player['notDrawPercentage'] = 100;
            $player['lossPercentage'] = 0;
            $player['notLossPercentage'] = 100;
            $player['played'] = 0;
            $player['wins'] = 0;
            $player['draws'] = 0;
            $player['losses'] = 0;
        }

        $player['winPercentage'] = $player['played'] > 0 ? number_format(($player['wins'] / $player['played']) * 100, 0) : 0;
        $player['notWinPercentage'] = 100 - $player['winPercentage'];

        $player['drawPercentage'] = $player['played'] > 0 ? number_format(($player['draws'] / $player['played']) * 100, 0) : 0;
        $player['notDrawPercentage'] = 100 - $player['drawPercentage'];

        $player['lossPercentage'] = $player['played'] > 0 ? number_format(($player['losses'] / $player['played']) * 100, 0) : 0;
        $player['notLossPercentage'] = 100 - $player['lossPercentage'];

        $player['played'] = $player['wins'] + $player['draws'] + $player['losses'];

        # Load elo history and more
        $sql = 'select if (p.id = g.home_player_id, g.new_home_elo, g.new_away_elo) as elo,
               g.id, g.home_player_id, g.away_player_id, g.date_of_match, g.winner_id, g.home_score, g.away_score
        from player p
        left join game g on p.id in (g.home_player_id, g.away_player_id)
        join tournament t on g.tournament_id = t.id
        where p.id = :playerId
        and g.is_finished = 1
        and t.is_official = 1
        order by g.date_played asc, g.id asc';

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $eloHistory[] = ['order', 'ELO history'];
        $eloHistory[] = [0, 1500];
        $index = 1;
        foreach ($data as $row) {
            $eloHistory[] = [
                $index,
                (int) $row['elo']
            ];
            $index++;
        }

        $player['eloHistory'] = $eloHistory;
        $player['pieData'] = [
            ['type', 'percentage'],
            ['wins', (int) $player['wins']],
            ['draws', (int) $player['draws']],
            ['losses', (int) $player['losses']],
        ];

        return $player;
    }

    /**
     * Base sql string
     * @return string
     */
    public function baseQuery()
    {
        $sql =
            'select g.id, gm.name, g.winner_id as winnerId, p1.name homePlayerName, p2.name as awayPlayerName, ' .
            'p1.tournament_elo as homeElo, p2.tournament_elo as awayElo, ' .
            'p1.id as homePlayerId, p2.id awayPlayerId, gm.max_sets as maxSets, ' .
            'g.home_score as homeScoreTotal, g.away_score as awayScoreTotal, ' .
            's1.home_points as s1hp, s1.away_points s1ap, ' .
            's2.home_points as s2hp, s2.away_points s2ap, ' .
            's3.home_points as s3hp, s3.away_points s3ap, ' .
            's4.home_points as s4hp, s4.away_points s4ap, ' .
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
     * @param $playerId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function loadPlayerResults($playerId)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where (g.home_player_id = :playerId OR g.away_player_id = :playerId)';
        $baseSql .= 'and g.is_finished = 1 and tg.is_official = 1 ';
        $baseSql .= 'order by g.date_of_match desc';

        $params['playerId'] = $playerId;

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
                'datePlayed' => $match['datePlayed'],
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
     * @param $playerId
     * @return array
     */
    public function loadPlayerSchedule($playerId)
    {
        $matchData = [];

        $baseSql = $this->baseQuery();
        $baseSql .= 'where (g.home_player_id = :playerId OR g.away_player_id = :playerId)';
        $baseSql .= 'and g.is_finished = 0  and tg.is_official = 1 ';
        $baseSql .= 'order by g.date_of_match asc';

        $params['playerId'] = $playerId;

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
                'homeElo' => $match['homeElo'],
                'awayElo' => $match['awayElo'],
                'awayEloDiff' => (int) ($match['awayElo'] - $match['homeElo']),
                'homeEloDiff' => (int) ($match['homeElo'] - $match['awayElo']),
            ];
        }

        return $matchData;
    }
}
