<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\OneToMany(targetEntity: Hero::class, mappedBy: 'team')]
    private Collection $hero;

    #[ORM\Column]
    private ?int $successRate = null;

    #[ORM\Column]
    private ?int $failRate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $popularity = null;

    #[ORM\OneToOne(mappedBy: 'team', cascade: ['persist', 'remove'])]
    private ?Mission $mission = null;

    public function __construct()
    {
        $this->hero = new ArrayCollection();
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

    /**
     * @return Collection<int, Hero>
     */
    public function getHero(): Collection
    {
        return $this->hero;
    }

    public function addHero(Hero $hero): static
    {
        if (!$this->hero->contains($hero)) {
            $this->hero->add($hero);
            $hero->setTeam($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        if ($this->hero->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getTeam() === $this) {
                $hero->setTeam(null);
            }
        }

        return $this;
    }

    public function getSuccessRate(): ?int
    {
        return $this->successRate;
    }

    public function setSuccessRate(int $successRate): static
    {
        $this->successRate = $successRate;

        return $this;
    }

    public function getFailRate(): ?int
    {
        return $this->failRate;
    }

    public function setFailRate(int $failRate): static
    {
        $this->failRate = $failRate;

        return $this;
    }

    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(?string $popularity): static
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        // unset the owning side of the relation if necessary
        if ($mission === null && $this->mission !== null) {
            $this->mission->setTeam(null);
        }

        // set the owning side of the relation if necessary
        if ($mission !== null && $mission->getTeam() !== $this) {
            $mission->setTeam($this);
        }

        $this->mission = $mission;

        return $this;
    }
}
