<?php

namespace App\Entity\Appli;

use App\Repository\Appli\BookroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité pour la mise en place des créneaux disponibles
 */
#[ORM\Entity(repositoryClass: BookroomRepository::class)]
class Bookroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateBookAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hourBookOpenAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hourBookClosedAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $forme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkDistanciel = null;

    #[ORM\Column(nullable: true)]
    private ?int $place = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBookAt(): ?\DateTimeInterface
    {
        return $this->dateBookAt;
    }

    public function setDateBookAt(\DateTimeInterface $dateBookAt): self
    {
        $this->dateBookAt = $dateBookAt;

        return $this;
    }

    public function getHourBookOpenAt(): ?\DateTimeInterface
    {
        return $this->hourBookOpenAt;
    }

    public function setHourBookOpenAt(\DateTimeInterface $hourBookOpenAt): self
    {
        $this->hourBookOpenAt = $hourBookOpenAt;

        return $this;
    }

    public function getHourBookClosedAt(): ?\DateTimeInterface
    {
        return $this->hourBookClosedAt;
    }

    public function setHourBookClosedAt(\DateTimeInterface $hourBookClosedAt): self
    {
        $this->hourBookClosedAt = $hourBookClosedAt;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(?string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getLinkDistanciel(): ?string
    {
        return $this->linkDistanciel;
    }

    public function setLinkDistanciel(?string $linkDistanciel): self
    {
        $this->linkDistanciel = $linkDistanciel;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(?int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
