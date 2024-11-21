<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secretIdendity = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notableMission = null;

    #[ORM\Column]
    private ?int $succesRate = null;

    #[ORM\Column]
    private ?int $failRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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

    public function getSecretIdendity(): ?string
    {
        return $this->secretIdendity;
    }

    public function setSecretIdendity(?string $secretIdendity): static
    {
        $this->secretIdendity = $secretIdendity;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getNotableMission(): ?string
    {
        return $this->notableMission;
    }

    public function setNotableMission(?string $notableMission): static
    {
        $this->notableMission = $notableMission;

        return $this;
    }

    public function getSuccesRate(): ?int
    {
        return $this->succesRate;
    }

    public function setSuccesRate(int $succesRate): static
    {
        $this->succesRate = $succesRate;

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
}
