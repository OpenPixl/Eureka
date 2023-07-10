<?php

namespace App\Entity\Appli;

use App\Entity\Admin\Member;
use App\Repository\Appli\BookroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité pour la mise en place des créneaux disponibles
 */
#[ORM\Entity(repositoryClass: BookroomRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'bookrooms')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'bookrooms')]
    private ?Member $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'seance')]
    private ?Course $course = null;

    #[ORM\OneToMany(mappedBy: 'seance', targetEntity: Registration::class)]
    private Collection $registrations;

    #[ORM\Column(nullable: true)]
    private ?bool $isReplicate = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfReplicate = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $choiceTime = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $uniq = null;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getTeacher(): ?Member
    {
        return $this->teacher;
    }

    public function setTeacher(?Member $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setSeance($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getSeance() === $this) {
                $registration->setSeance(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->dateBookAt->format('l d M Y');
    }

    public function isIsReplicate(): ?bool
    {
        return $this->isReplicate;
    }

    public function setIsReplicate(?bool $isReplicate): self
    {
        $this->isReplicate = $isReplicate;

        return $this;
    }

    public function getNumberOfReplicate(): ?int
    {
        return $this->numberOfReplicate;
    }

    public function setNumberOfReplicate(?int $numberOfReplicate): self
    {
        $this->numberOfReplicate = $numberOfReplicate;

        return $this;
    }

    public function getChoiceTime(): ?string
    {
        return $this->choiceTime;
    }

    public function setChoiceTime(?string $choiceTime): self
    {
        $this->choiceTime = $choiceTime;

        return $this;
    }

    public function getUniq(): ?string
    {
        return $this->uniq;
    }

    public function setUniq(?string $uniq): self
    {
        $this->uniq = $uniq;

        return $this;
    }
}
