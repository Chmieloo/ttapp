<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
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
     */
    public function loadPlayerById($playerId)
    {
        $sql = 'select p.name, count(g.id) as played, ' .
               'sum(if(p.id = g.winner_id, 1, 0)) as wins, ' .
               'sum(if(g.winner_id = 0, 1, 0)) as draws, ' .
               'sum(if(g.winner_id != 0 and g.winner_id != p.id, 1, 0)) as losses ' .
               'from player p ' .
               'join game g on p.id in (g.home_player_id, g.away_player_id) ' .
               'where p.id = :playerId and g.is_finished = 1 ' .
               'group by p.id';

        $params['playerId'] = $playerId;

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt-> execute($params);

        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        $player['winPercentage'] = number_format(($player['wins'] / $player['played']) * 100, 0);
        $player['notWinPercentage'] = 100 - $player['winPercentage'];

        $player['drawPercentage'] = number_format(($player['draws'] / $player['played']) * 100, 0);
        $player['notDrawPercentage'] = 100 - $player['drawPercentage'];

        $player['lossPercentage'] = number_format(($player['losses'] / $player['played']) * 100, 0);
        $player['notLossPercentage'] = 100 - $player['lossPercentage'];

        return $player;
    }
}
