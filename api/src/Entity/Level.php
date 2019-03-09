<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LevelRepository")
 */
class Level
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
     * @ORM\OneToMany(targetEntity="App\Entity\GameCup", mappedBy="level")
     */
    private $gameCups;

    public function __construct()
    {
        $this->gameCups = new ArrayCollection();
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

    /**
     * @return Collection|GameCup[]
     */
    public function getGameCups(): Collection
    {
        return $this->gameCups;
    }

    public function addGameCup(GameCup $gameCup): self
    {
        if (!$this->gameCups->contains($gameCup)) {
            $this->gameCups[] = $gameCup;
            $gameCup->setLevel($this);
        }

        return $this;
    }

    public function removeGameCup(GameCup $gameCup): self
    {
        if ($this->gameCups->contains($gameCup)) {
            $this->gameCups->removeElement($gameCup);
            // set the owning side to null (unless already changed)
            if ($gameCup->getLevel() === $this) {
                $gameCup->setLevel(null);
            }
        }

        return $this;
    }
}
