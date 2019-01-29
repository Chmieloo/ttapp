<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameMode", inversedBy="games")
     */
    private $game_mode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_finished;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_abandoned;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_walkover;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="home_games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $home_player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="away_games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $away_player;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_of_match;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="games")
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentGroup", inversedBy="games")
     */
    private $tournament_group;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameMode(): ?GameMode
    {
        return $this->game_mode;
    }

    public function setGameMode(?GameMode $game_mode): self
    {
        $this->game_mode = $game_mode;

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->is_finished;
    }

    public function setIsFinished(bool $is_finished): self
    {
        $this->is_finished = $is_finished;

        return $this;
    }

    public function getIsAbandoned(): ?bool
    {
        return $this->is_abandoned;
    }

    public function setIsAbandoned(bool $is_abandoned): self
    {
        $this->is_abandoned = $is_abandoned;

        return $this;
    }

    public function getIsWalkover(): ?bool
    {
        return $this->is_walkover;
    }

    public function setIsWalkover(bool $is_walkover): self
    {
        $this->is_walkover = $is_walkover;

        return $this;
    }

    public function getHomePlayer(): ?Player
    {
        return $this->home_player;
    }

    public function setHomePlayer(?Player $home_player): self
    {
        $this->home_player = $home_player;

        return $this;
    }

    public function getAwayPlayer(): ?Player
    {
        return $this->away_player;
    }

    public function setAwayPlayer(?Player $away_player): self
    {
        $this->away_player = $away_player;

        return $this;
    }

    public function getDateOfMatch(): ?\DateTimeInterface
    {
        return $this->date_of_match;
    }

    public function setDateOfMatch(\DateTimeInterface $date_of_match): self
    {
        $this->date_of_match = $date_of_match;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getTournamentGroup(): ?TournamentGroup
    {
        return $this->tournament_group;
    }

    public function setTournamentGroup(?TournamentGroup $tournament_group): self
    {
        $this->tournament_group = $tournament_group;

        return $this;
    }
}
