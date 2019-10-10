<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\Column(type="integer")
     */
    private $winner_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $home_score;

    /**
     * @ORM\Column(type="smallint")
     */
    private $away_score;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Scores", mappedBy="game", orphanRemoval=true)
     */
    private $scores;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_played;

    /**
     * @ORM\Column(type="smallint")
     */
    private $current_set;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $server_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $play_order;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $playoff_home_player_id;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $playoff_away_player_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oldHomeELO;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oldAwayELO;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $newHomeELO;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $newAwayELO;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Office", inversedBy="games")
     */
    private $office;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

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

    public function getWinnerId(): ?int
    {
        return $this->winner_id;
    }

    public function setWinnerId(int $winner_id): self
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

    /**
     * @return Collection|Scores[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Scores $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setGame($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSetScores()
    {
        $currentHomeScore = 0;
        $currentAwayScore = 0;

        /**
         * @var  $key
         * @var Scores $score
         */
        foreach ($this->scores as $key => $score) {
            $currentHomeScore += $score->getHomePoints() > $score->getAwayPoints() ? 1 : 0;
            $currentAwayScore += $score->getAwayPoints() > $score->getHomePoints() ? 1 : 0;
        }

        return [$currentHomeScore, $currentAwayScore];
    }

    public function removeScore(Scores $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getGame() === $this) {
                $score->setGame(null);
            }
        }

        return $this;
    }

    public function getDatePlayed(): ?\DateTimeInterface
    {
        return $this->date_played;
    }

    public function setDatePlayed(?\DateTimeInterface $date_played): self
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

    public function setServerId(?int $server_id): self
    {
        $this->server_id = $server_id;

        return $this;
    }

    public function getPlayOrder(): ?int
    {
        return $this->play_order;
    }

    public function setPlayOrder(?int $play_order): self
    {
        $this->play_order = $play_order;

        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(?int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPlayoffHomePlayerId(): ?string
    {
        return $this->playoff_home_player_id;
    }

    public function setPlayoffHomePlayerId(?string $playoff_home_player_id): self
    {
        $this->playoff_home_player_id = $playoff_home_player_id;

        return $this;
    }

    public function getPlayoffAwayPlayerId(): ?string
    {
        return $this->playoff_away_player_id;
    }

    public function setPlayoffAwayPlayerId(?string $playoff_away_player_id): self
    {
        $this->playoff_away_player_id = $playoff_away_player_id;

        return $this;
    }

    public function getOldHomeELO(): ?int
    {
        return $this->oldHomeELO;
    }

    public function setOldHomeELO(?int $oldHomeELO): self
    {
        $this->oldHomeELO = $oldHomeELO;

        return $this;
    }

    public function getOldAwayELO(): ?int
    {
        return $this->oldAwayELO;
    }

    public function setOldAwayELO(?int $oldAwayELO): self
    {
        $this->oldAwayELO = $oldAwayELO;

        return $this;
    }

    public function getNewHomeELO(): ?int
    {
        return $this->newHomeELO;
    }

    public function setNewHomeELO(?int $newHomeELO): self
    {
        $this->newHomeELO = $newHomeELO;

        return $this;
    }

    public function getNewAwayELO(): ?int
    {
        return $this->newAwayELO;
    }

    public function setNewAwayELO(?int $newAwayELO): self
    {
        $this->newAwayELO = $newAwayELO;

        return $this;
    }

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function setOffice(?Office $office): self
    {
        $this->office = $office;

        return $this;
    }
}
