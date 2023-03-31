<?php

namespace App\Entity\Appli;

use App\Entity\Admin\Member;
use App\Repository\Appli\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité pour la gestion des cours
 */
#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoSize = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Member $teacher = null;

    #[ORM\ManyToMany(targetEntity: Bookroom::class, mappedBy: 'room')]
    private Collection $bookrooms;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Bookroom::class)]
    private Collection $seance;

    #[ORM\Column(length: 255)]
    private ?string $z = null;

    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'studientcourse')]
    private Collection $studients;

    public function __construct()
    {
        $this->bookrooms = new ArrayCollection();
        $this->seance = new ArrayCollection();
        $this->studients = new ArrayCollection();
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

    public function getTeacher(): ?Member
    {
        return $this->teacher;
    }

    public function setTeacher(?Member $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLogoFile(): ?string
    {
        return $this->logoFile;
    }

    public function setLogoFile(string $logoFile): self
    {
        $this->logoFile = $logoFile;

        return $this;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): self
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getLogoSize(): ?string
    {
        return $this->logoSize;
    }

    public function setLogoSize(?string $logoSize): self
    {
        $this->logoSize = $logoSize;

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

    /**
     * @return Collection<int, Bookroom>
     */
    public function getBookrooms(): Collection
    {
        return $this->bookrooms;
    }

    public function addBookroom(Bookroom $bookroom): self
    {
        if (!$this->bookrooms->contains($bookroom)) {
            $this->bookrooms->add($bookroom);
            $bookroom->addRoom($this);
        }

        return $this;
    }

    public function removeBookroom(Bookroom $bookroom): self
    {
        if ($this->bookrooms->removeElement($bookroom)) {
            $bookroom->removeRoom($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookroom>
     */
    public function getSeance(): Collection
    {
        return $this->seance;
    }

    public function addSeance(Bookroom $seance): self
    {
        if (!$this->seance->contains($seance)) {
            $this->seance->add($seance);
            $seance->setCourse($this);
        }

        return $this;
    }

    public function removeSeance(Bookroom $seance): self
    {
        if ($this->seance->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getCourse() === $this) {
                $seance->setCourse(null);
            }
        }

        return $this;
    }

    public function getZ(): ?string
    {
        return $this->z;
    }

    public function setZ(string $z): self
    {
        $this->z = $z;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getStudients(): Collection
    {
        return $this->studients;
    }

    public function addStudient(Member $studient): self
    {
        if (!$this->studients->contains($studient)) {
            $this->studients->add($studient);
        }

        return $this;
    }

    public function removeStudient(Member $studient): self
    {
        $this->studients->removeElement($studient);

        return $this;
    }
}
