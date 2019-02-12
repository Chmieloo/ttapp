<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointsRepository")
 */
class Points
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scores", inversedBy="points")
     * @ORM\JoinColumn(nullable=false)
     */
    private $score;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_home_point;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_away_point;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?Scores
    {
        return $this->score;
    }

    public function setScore(?Scores $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getIsHomePoint(): ?bool
    {
        return $this->is_home_point;
    }

    public function setIsHomePoint(bool $is_home_point): self
    {
        $this->is_home_point = $is_home_point;

        return $this;
    }

    public function getIsAwayPoint(): ?bool
    {
        return $this->is_away_point;
    }

    public function setIsAwayPoint(bool $is_away_point): self
    {
        $this->is_away_point = $is_away_point;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
