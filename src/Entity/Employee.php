<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Your password must contain a number"
     * )
     * @Assert\Regex(
     *     pattern="/[a-zA-Z]/",
     *     message="Your password must contain a letter"
     * ) 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employeeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employeeFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employeePhoto;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Expression(
     *     "this.getContract() in ['CDI'] or this.getEndContract()",
     *     message="Vous devez définir une date de fin de contrat"
     * )
     */
    private $endContract;

    /**
     * @ORM\ManyToOne(targetEntity=Sector::class, inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sector;

    /**
     * @ORM\ManyToOne(targetEntity=Contract::class, inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contract;

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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmployeeName(): ?string
    {
        return $this->employeeName;
    }

    public function setEmployeeName(string $employeeName): self
    {
        $this->employeeName = $employeeName;

        return $this;
    }

    public function getEmployeeFirstName(): ?string
    {
        return $this->employeeFirstName;
    }

    public function setEmployeeFirstName(string $employeeFirstName): self
    {
        $this->employeeFirstName = $employeeFirstName;

        return $this;
    }

    public function getEmployeePhoto(): ?string
    {
        return $this->employeePhoto;
    }

    public function setEmployeePhoto(string $employeePhoto): self
    {
        $this->employeePhoto = $employeePhoto;

        return $this;
    }

    public function getEndContract(): ?\DateTimeInterface
    {
        return $this->endContract;
    }

    public function setEndContract(?\DateTimeInterface $endContract): self
    {
        $this->endContract = $endContract;

        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
