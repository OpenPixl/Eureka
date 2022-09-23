<?php

namespace App\Entity\Appli;

use App\Entity\Admin\Member;
use App\Repository\Appli\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

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
