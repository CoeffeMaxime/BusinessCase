<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => ['adresse:post']
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['adresse:item']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['adresse:put']
            ]
        ],
        'delete' => [
            'access_control' => 'is_granted(\'DELETE\', object)',
        ],
    ],
)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put', 'adresse:post','adresse:item','adresse:put'])]
    private ?string $ligne1 = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put', 'adresse:post','adresse:item','adresse:put'])]
    private ?string $ligne2 = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put', 'adresse:post','adresse:item','adresse:put'])]
    private ?string $ligne3 = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put', 'adresse:post','adresse:item','adresse:put'])]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:item','user:put', 'adresse:post','adresse:item','adresse:put'])]
    private ?string $ville = null;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne1(): ?string
    {
        return $this->ligne1;
    }

    public function setLigne1(string $ligne1): static
    {
        $this->ligne1 = $ligne1;

        return $this;
    }

    public function getLigne2(): ?string
    {
        return $this->ligne2;
    }

    public function setLigne2(string $ligne2): static
    {
        $this->ligne2 = $ligne2;

        return $this;
    }

    public function getLigne3(): ?string
    {
        return $this->ligne3;
    }

    public function setLigne3(string $ligne3): static
    {
        $this->ligne3 = $ligne3;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setAdresse($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdresse() === $this) {
                $user->setAdresse(null);
            }
        }

        return $this;
    }
}
