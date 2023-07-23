<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [

        'post' => [
            'denormalization_context' => [
                'groups' => ['user:post']
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['user:item']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['user:put']
            ]
        ],
        'delete' => [
            'access_control' => 'is_granted(\'DELETE\', object)',
        ],
    ],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?string $email = null;

    #[ORM\Column]

    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?string $Pseudo = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Visite::class)]
    #[Groups('user:item')]
    private Collection $visites;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Acquisition::class)]
    #[Groups(['user:item','user:put'])]
    private Collection $acquisitions;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['user:post', 'user:item','user:put'])]
    private ?Adresse $adresse = null;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->acquisitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(string $Pseudo): static
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    /**
     * @return Collection<int, visite>
     */
    public function getVisites(): Collection
    {
        return $this->visites;
    }

    public function addVisite(Visite $visite): static
    {
        if (!$this->visites->contains($visite)) {
            $this->visites->add($visite);
            $visite->setUser($this);
        }

        return $this;
    }

    public function removeVisite(Visite $visite): static
    {
        if ($this->visites->removeElement($visite)) {
            // set the owning side to null (unless already changed)
            if ($visite->getUser() === $this) {
                $visite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, acquisition>
     */
    public function getAcquisitions(): Collection
    {
        return $this->acquisitions;
    }

    public function addAcquisition(Acquisition $acquisition): static
    {
        if (!$this->acquisitions->contains($acquisition)) {
            $this->acquisitions->add($acquisition);
            $acquisition->setUser($this);
        }

        return $this;
    }

    public function removeAcquisition(Acquisition $acquisition): static
    {
        if ($this->acquisitions->removeElement($acquisition)) {
            // set the owning side to null (unless already changed)
            if ($acquisition->getUser() === $this) {
                $acquisition->setUser(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }
}
