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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $display_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slackName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Office", inversedBy="players")
     */
    private $office;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_pic_url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tournament_elo_previous;

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

    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    public function setDisplayName(?string $display_name): self
    {
        $this->display_name = $display_name;

        return $this;
    }

    public function getSlackName(): ?string
    {
        return $this->slackName;
    }

    public function setSlackName(string $slackName): self
    {
        $this->slackName = $slackName;

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

    public function getProfilePicUrl(): ?string
    {
        return $this->profile_pic_url;
    }

    public function setProfilePicUrl(?string $profile_pic_url): self
    {
        $this->profile_pic_url = $profile_pic_url;

        return $this;
    }

    public function getTournamentEloPrevious(): ?int
    {
        return $this->tournament_elo_previous;
    }

    public function setTournamentEloPrevious(?int $tournament_elo_previous): self
    {
        $this->tournament_elo_previous = $tournament_elo_previous;

        return $this;
    }
}
