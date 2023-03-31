<?php

namespace App\Entity\Admin;

use App\Entity\Appli\Bookroom;
use App\Entity\Appli\Course;
use App\Entity\Appli\Registration;
use App\Repository\Admin\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 14)]
    private ?string $gsm = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $home = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typemember = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: Course::class)]
    private Collection $courses;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: Bookroom::class)]
    private Collection $bookrooms;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ddn = null;

    #[ORM\OneToMany(mappedBy: 'studient', targetEntity: Registration::class)]
    private Collection $registrations;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'studients')]
    private Collection $studientcourse;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->bookrooms = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->studientcourse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    public function setGsm(string $gsm): self
    {
        $this->gsm = $gsm;

        return $this;
    }

    public function getHome(): ?string
    {
        return $this->home;
    }

    public function setHome(?string $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getAvatarFile(): ?string
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(string $avatarFile): self
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): self
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    public function getAvatarSize(): ?string
    {
        return $this->avatarSize;
    }

    public function setAvatarSize(?string $avatarSize): self
    {
        $this->avatarSize = $avatarSize;

        return $this;
    }

    public function getTypemember(): ?string
    {
        return $this->typemember;
    }

    public function setTypemember(string $typemember): self
    {
        $this->typemember = $typemember;

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTeacher() === $this) {
                $course->setTeacher(null);
            }
        }

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
            $bookroom->setTeacher($this);
        }

        return $this;
    }

    public function removeBookroom(Bookroom $bookroom): self
    {
        if ($this->bookrooms->removeElement($bookroom)) {
            // set the owning side to null (unless already changed)
            if ($bookroom->getTeacher() === $this) {
                $bookroom->setTeacher(null);
            }
        }

        return $this;
    }

    public function getDdn(): ?\DateTimeInterface
    {
        return $this->ddn;
    }

    public function setDdn(?\DateTimeInterface $ddn): self
    {
        $this->ddn = $ddn;

        return $this;
    }

    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
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
            $registration->setStudient($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getStudient() === $this) {
                $registration->setStudient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getStudientcourse(): Collection
    {
        return $this->studientcourse;
    }

    public function addStudientcourse(Course $studientcourse): self
    {
        if (!$this->studientcourse->contains($studientcourse)) {
            $this->studientcourse->add($studientcourse);
            $studientcourse->addStudient($this);
        }

        return $this;
    }

    public function removeStudientcourse(Course $studientcourse): self
    {
        if ($this->studientcourse->removeElement($studientcourse)) {
            $studientcourse->removeStudient($this);
        }

        return $this;
    }
}
