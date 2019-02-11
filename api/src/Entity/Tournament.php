<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 */
class Tournament
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
     * @ORM\Column(type="boolean")
     */
    private $is_finished;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_playoffs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_time;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="tournament")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TournamentGroup", mappedBy="tournament")
     */
    private $tournamentGroups;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_official;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->tournamentGroups = new ArrayCollection();
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

    public function getIsFinished(): ?bool
    {
        return $this->is_finished;
    }

    public function setIsFinished(bool $is_finished): self
    {
        $this->is_finished = $is_finished;

        return $this;
    }

    public function getIsPlayoffs(): ?bool
    {
        return $this->is_playoffs;
    }

    public function setIsPlayoffs(bool $is_playoffs): self
    {
        $this->is_playoffs = $is_playoffs;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TournamentGroup[]
     */
    public function getTournamentGroups(): Collection
    {
        return $this->tournamentGroups;
    }

    public function addGroup(TournamentGroup $group): self
    {
        if (!$this->tournamentGroups->contains($group)) {
            $this->tournamentGroups[] = $group;
            $group->setTournament($this);
        }

        return $this;
    }

    public function removeGroup(TournamentGroup $group): self
    {
        if ($this->tournamentGroups->contains($group)) {
            $this->tournamentGroups->removeElement($group);
            // set the owning side to null (unless already changed)
            if ($group->getTournament() === $this) {
                $group->setTournament(null);
            }
        }

        return $this;
    }

    public function getIsOfficial(): ?bool
    {
        return $this->is_official;
    }

    public function setIsOfficial(bool $is_official): self
    {
        $this->is_official = $is_official;

        return $this;
    }
}
