<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoresRepository")
 */
class Scores
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\Column(type="smallint")
     */
    private $set_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $home_points;

    /**
     * @ORM\Column(type="integer")
     */
    private $away_points;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Points", mappedBy="score", orphanRemoval=true)
     */
    private $points;

    public function __construct()
    {
        $this->points = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getSetNumber(): ?int
    {
        return $this->set_number;
    }

    public function setSetNumber(int $set_number): self
    {
        $this->set_number = $set_number;

        return $this;
    }

    public function getHomePoints(): ?int
    {
        return $this->home_points;
    }

    public function setHomePoints(int $home_points): self
    {
        $this->home_points = $home_points;

        return $this;
    }

    public function getAwayPoints(): ?int
    {
        return $this->away_points;
    }

    public function setAwayPoints(int $away_points): self
    {
        $this->away_points = $away_points;

        return $this;
    }

    /**
     * @return Collection|Points[]
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Points $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setScore($this);
        }

        return $this;
    }

    public function removePoint(Points $point): self
    {
        if ($this->points->contains($point)) {
            $this->points->removeElement($point);
            // set the owning side to null (unless already changed)
            if ($point->getScore() === $this) {
                $point->setScore(null);
            }
        }

        return $this;
    }
}
