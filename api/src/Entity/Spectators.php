<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpectatorsRepository")
 */
class Spectators
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
    private $game_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $spectators;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?int
    {
        return $this->game_id;
    }

    public function setGameId(int $game_id): self
    {
        $this->game_id = $game_id;

        return $this;
    }

    public function getSpectators(): ?int
    {
        return $this->spectators;
    }

    public function setSpectators(int $spectators): self
    {
        $this->spectators = $spectators;

        return $this;
    }

    public function getPit(): ?\DateTimeInterface
    {
        return $this->pit;
    }

    public function setPit(\DateTimeInterface $pit): self
    {
        $this->pit = $pit;

        return $this;
    }
}
