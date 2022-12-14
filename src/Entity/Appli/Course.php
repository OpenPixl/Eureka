<?php

namespace App\Entity\Appli;

use App\Entity\Admin\Member;
use App\Repository\Appli\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\ManyToOne(inversedBy: 'Course')]
    private ?Member $teacher = null;

    #[ORM\Column(length: 20)]
    private ?string $level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoSize = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Bookroom::class, mappedBy: 'Course')]
    private Collection $bookrooms;

    public function __construct()
    {
        $this->bookrooms = new ArrayCollection();
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
            $bookroom->addCour($this);
        }

        return $this;
    }

    public function removeBookroom(Bookroom $bookroom): self
    {
        if ($this->bookrooms->removeElement($bookroom)) {
            $bookroom->removeCour($this);
        }

        return $this;
    }
}
