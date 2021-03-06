<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BattleRepository")
 */
class Battle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Fighter", inversedBy="zone")
     */
    private $fighter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Zone", inversedBy="battles")
     */
    private $zone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fighter", inversedBy="battles")
     */
    private $winner;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->fighter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Fighter[]
     */
    public function getFighter(): Collection
    {
        return $this->fighter;
    }

    public function addFighter(Fighter $fighter): self
    {
        if (!$this->fighter->contains($fighter)) {
            $this->fighter[] = $fighter;
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): self
    {
        if ($this->fighter->contains($fighter)) {
            $this->fighter->removeElement($fighter);
        }

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWinner(): ?Fighter
    {
        return $this->winner;
    }

    public function setWinner(?Fighter $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
