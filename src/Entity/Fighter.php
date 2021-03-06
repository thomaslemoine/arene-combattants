<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FighterRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Fighter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="fighter_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $strength;

    /**
     * @ORM\Column(type="integer")
     */
    private $intelligence;

    /**
     * @ORM\Column(type="integer")
     */
    private $pv;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $killed_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="fighters")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Battle", mappedBy="fighter")
     */
    private $zone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Battle", mappedBy="winner_id")
     */
    private $battles;

    public function __construct()
    {
        $this->zone = new ArrayCollection();
        $this->battles = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $king;

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime();
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

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): self
    {
        $this->pv = $pv;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getKilledAt(): ?\DateTimeInterface
    {
        return $this->killed_at;
    }

    public function setKilledAt(?\DateTimeInterface $killed_at): self
    {
        $this->killed_at = $killed_at;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @return Collection|Battle[]
     */
    public function getZone(): Collection
    {
        return $this->zone;
    }

    public function addZone(Battle $zone): self
    {
        if (!$this->zone->contains($zone)) {
            $this->zone[] = $zone;
            $zone->addFighter($this);
        }

        return $this;
    }

    public function removeZone(Battle $zone): self
    {
        if ($this->zone->contains($zone)) {
            $this->zone->removeElement($zone);
            $zone->removeFighter($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Battle[]
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    public function addBattle(Battle $battle): self
    {
        if (!$this->battles->contains($battle)) {
            $this->battles[] = $battle;
            $battle->setWinner($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->contains($battle)) {
            $this->battles->removeElement($battle);
            // set the owning side to null (unless already changed)
            if ($battle->getWinner() === $this) {
                $battle->setWinner(null);
            }
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getKing(): ?bool
    {
        return $this->king;
    }

    public function setKing(?bool $king): self
    {
        $this->king = $king;

        return $this;
    }
}
