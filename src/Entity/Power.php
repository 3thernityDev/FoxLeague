<?php

namespace App\Entity;

use App\Repository\PowerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PowerRepository::class)]
class Power
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    /**
     * @var Collection<int, hero>
     */
    #[ORM\OneToMany(targetEntity: hero::class, mappedBy: 'powerLink')]
    private Collection $heroLink;

    public function __construct()
    {
        $this->heroLink = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }

    /**
     * @return Collection<int, hero>
     */
    public function getHeroLink(): Collection
    {
        return $this->heroLink;
    }

    public function addHeroLink(hero $heroLink): static
    {
        if (!$this->heroLink->contains($heroLink)) {
            $this->heroLink->add($heroLink);
            $heroLink->setPowerLink($this);
        }

        return $this;
    }

    public function removeHeroLink(hero $heroLink): static
    {
        if ($this->heroLink->removeElement($heroLink)) {
            // set the owning side to null (unless already changed)
            if ($heroLink->getPowerLink() === $this) {
                $heroLink->setPowerLink(null);
            }
        }

        return $this;
    }
}
