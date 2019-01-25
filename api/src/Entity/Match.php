<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 */
class Match
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchMode", inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $match_mode;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMatchMode(): ?MatchMode
    {
        return $this->match_mode;
    }

    public function setMatchMode(?MatchMode $match_mode): self
    {
        $this->match_mode = $match_mode;

        return $this;
    }
}
