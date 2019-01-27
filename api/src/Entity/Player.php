<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="home_player")
     */
    private $home_games;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="away_player")
     */
    private $away_games;

    /**
     * @ORM\Column(type="integer", options={"default" : 1500})
     */
    private $tournament_elo;

    /**
     * @ORM\Column(type="integer", options={"default" : 1500})
     */
    private $current_elo;

    public function __construct()
    {
        $this->home_games = new ArrayCollection();
        $this->away_games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getHomeGames(): Collection
    {
        return $this->home_games;
    }

    public function addHomeGame(Game $homeGame): self
    {
        if (!$this->home_games->contains($homeGame)) {
            $this->home_games[] = $homeGame;
            $homeGame->setHomePlayer($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $homeGame): self
    {
        if ($this->home_games->contains($homeGame)) {
            $this->home_games->removeElement($homeGame);
            // set the owning side to null (unless already changed)
            if ($homeGame->getHomePlayer() === $this) {
                $homeGame->setHomePlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getAwayGames(): Collection
    {
        return $this->away_games;
    }

    public function addAwayGame(Game $awayGame): self
    {
        if (!$this->away_games->contains($awayGame)) {
            $this->away_games[] = $awayGame;
            $awayGame->setAwayPlayer($this);
        }

        return $this;
    }

    public function removeAwayGame(Game $awayGame): self
    {
        if ($this->away_games->contains($awayGame)) {
            $this->away_games->removeElement($awayGame);
            // set the owning side to null (unless already changed)
            if ($awayGame->getAwayPlayer() === $this) {
                $awayGame->setAwayPlayer(null);
            }
        }

        return $this;
    }

    public function getTournamentElo(): ?int
    {
        return $this->tournament_elo;
    }

    public function setTournamentElo(int $tournament_elo): self
    {
        $this->tournament_elo = $tournament_elo;

        return $this;
    }

    public function getCurrentElo(): ?int
    {
        return $this->current_elo;
    }

    public function setCurrentElo(int $current_elo): self
    {
        $this->current_elo = $current_elo;

        return $this;
    }
}
