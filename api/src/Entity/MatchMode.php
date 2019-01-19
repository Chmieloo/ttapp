<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchModeRepository")
 */
class MatchMode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $wins_required;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $max_sets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinsRequired(): ?int
    {
        return $this->wins_required;
    }

    public function setWinsRequired(int $wins_required): self
    {
        $this->wins_required = $wins_required;

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

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getMaxSets(): ?int
    {
        return $this->max_sets;
    }

    public function setMaxSets(int $max_sets): self
    {
        $this->max_sets = $max_sets;

        return $this;
    }
}
