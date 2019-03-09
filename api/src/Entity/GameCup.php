<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameCupRepository")
 */
class GameCup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $game_mode_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $home_player_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $away_player_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament_id;

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
     * @ORM\Column(type="datetime")
     */
    private $date_of_match;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $home_score;

    /**
     * @ORM\Column(type="integer")
     */
    private $away_score;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_played;

    /**
     * @ORM\Column(type="integer")
     */
    private $current_set;

    /**
     * @ORM\Column(type="integer")
     */
    private $server_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $play_order;

    /**
     * @ORM\Column(type="integer")
     */
    private $stage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level", inversedBy="gameCups")
     */
    private $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameModeId(): ?int
    {
        return $this->game_mode_id;
    }

    public function setGameModeId(int $game_mode_id): self
    {
        $this->game_mode_id = $game_mode_id;

        return $this;
    }

    public function getHomePlayerId(): ?string
    {
        return $this->home_player_id;
    }

    public function setHomePlayerId(string $home_player_id): self
    {
        $this->home_player_id = $home_player_id;

        return $this;
    }

    public function getAwayPlayerId(): ?string
    {
        return $this->away_player_id;
    }

    public function setAwayPlayerId(string $away_player_id): self
    {
        $this->away_player_id = $away_player_id;

        return $this;
    }

    public function getTournamentId(): ?Tournament
    {
        return $this->tournament_id;
    }

    public function setTournamentId(?Tournament $tournament_id): self
    {
        $this->tournament_id = $tournament_id;

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

    public function getDateOfMatch(): ?\DateTimeInterface
    {
        return $this->date_of_match;
    }

    public function setDateOfMatch(\DateTimeInterface $date_of_match): self
    {
        $this->date_of_match = $date_of_match;

        return $this;
    }

    public function getWinnerId(): ?string
    {
        return $this->winner_id;
    }

    public function setWinnerId(string $winner_id): self
    {
        $this->winner_id = $winner_id;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->home_score;
    }

    public function setHomeScore(int $home_score): self
    {
        $this->home_score = $home_score;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->away_score;
    }

    public function setAwayScore(int $away_score): self
    {
        $this->away_score = $away_score;

        return $this;
    }

    public function getDatePlayed(): ?\DateTimeInterface
    {
        return $this->date_played;
    }

    public function setDatePlayed(\DateTimeInterface $date_played): self
    {
        $this->date_played = $date_played;

        return $this;
    }

    public function getCurrentSet(): ?int
    {
        return $this->current_set;
    }

    public function setCurrentSet(int $current_set): self
    {
        $this->current_set = $current_set;

        return $this;
    }

    public function getServerId(): ?int
    {
        return $this->server_id;
    }

    public function setServerId(int $server_id): self
    {
        $this->server_id = $server_id;

        return $this;
    }

    public function getPlayOrder(): ?int
    {
        return $this->play_order;
    }

    public function setPlayOrder(int $play_order): self
    {
        $this->play_order = $play_order;

        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }
}
