<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
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
     * @ORM\OneToMany(targetEntity="App\Entity\Fighter", mappedBy="type")
     */
    private $fighters;

    public function __construct()
    {
        $this->fighters = new ArrayCollection();
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
     * @return Collection|Fighter[]
     */
    public function getFighters(): Collection
    {
        return $this->fighters;
    }

    public function addFighter(Fighter $fighter): self
    {
        if (!$this->fighters->contains($fighter)) {
            $this->fighters[] = $fighter;
            $fighter->setType($this);
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): self
    {
        if ($this->fighters->contains($fighter)) {
            $this->fighters->removeElement($fighter);
            // set the owning side to null (unless already changed)
            if ($fighter->getType() === $this) {
                $fighter->setType(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->name;
    }
}
