<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['groupe:list']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['groupe:post']
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['groupe:item']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['groupe:put']
            ]
        ],
        'delete' => [
            'access_control' => 'is_granted(\'DELETE\', object)',
        ],
    ],
)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['groupe:list','groupe:post','groupe:item','groupe:put', 'nft:list', 'nft:item','nft:put' ])]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Nft::class)]
    private Collection $nfts;

    public function __construct()
    {
        $this->nfts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Nft>
     */
    public function getNfts(): Collection
    {
        return $this->nfts;
    }

    public function addNft(Nft $nft): static
    {
        if (!$this->nfts->contains($nft)) {
            $this->nfts->add($nft);
            $nft->setGroupe($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getGroupe() === $this) {
                $nft->setGroupe(null);
            }
        }

        return $this;
    }
}
