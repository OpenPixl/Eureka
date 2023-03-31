<?php

namespace App\Entity\Appli;

use App\Entity\Admin\Member;
use App\Repository\Appli\RegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité pour les inscriptions des étudiants sur un cours
 */
#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Member $studient = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Bookroom $seance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudient(): ?Member
    {
        return $this->studient;
    }

    public function setStudient(?Member $studient): self
    {
        $this->studient = $studient;

        return $this;
    }

    public function getSeance(): ?Bookroom
    {
        return $this->seance;
    }

    public function setSeance(?Bookroom $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }
}
