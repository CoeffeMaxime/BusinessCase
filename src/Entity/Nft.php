<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NftRepository::class)]
#[ApiResource(
    collectionOperations: [
    'get' => [
        'normalization_context' => [
            'groups' => ['nft:list']
        ]
    ],
    'post' => [
        'denormalization_context' => [
            'groups' => ['nft:post']
        ]
    ],
],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['nft:item']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['nft:put']
            ]
        ],
        'delete' => [
            'access_control' => 'is_granted(\'DELETE\', object)',
        ],
    ],
)]
class Nft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:list','nft:post','nft:item','nft:put'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['nft:list','nft:post','nft:item','nft:put'])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:list','nft:post','nft:item'])]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['nft:list','nft:post','nft:item','nft:put'])]
    private ?\DateTimeInterface $dateVente = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:list','nft:post','nft:item'])]
    private ?string $proprietaire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['nft:list','nft:post','nft:item'])]
    private ?string $valeurInitiale = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['nft:list','nft:post','nft:item','nft:put'])]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Historique::class)]
    #[Groups(['nft:list','nft:item'])]
    private Collection $historiques;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Acquisition::class)]
    #[Groups( 'nft:item')]
    private Collection $acquisitions;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Visite::class)]
    #[Groups('nft:item')]
    private Collection $Visites;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[Groups(['nft:list', 'nft:item','nft:put'])]
    private ?Groupe $groupe = null;

    public function __construct()
    {
        $this->historiques = new ArrayCollection();
        $this->acquisitions = new ArrayCollection();
        $this->Visites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(?\DateTimeInterface $dateVente): static
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->proprietaire;
    }

    public function setProprietaire(string $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getValeurInitiale(): ?string
    {
        return $this->valeurInitiale;
    }

    public function setValeurInitiale(string $valeurInitiale): static
    {
        $this->valeurInitiale = $valeurInitiale;

        return $this;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setNft($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getNft() === $this) {
                $historique->setNft(null);
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
            $acquisition->setNft($this);
        }

        return $this;
    }

    public function removeAcquisition(Acquisition $acquisition): static
    {
        if ($this->acquisitions->removeElement($acquisition)) {
            // set the owning side to null (unless already changed)
            if ($acquisition->getNft() === $this) {
                $acquisition->setNft(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, visite>
     */
    public function getVisites(): Collection
    {
        return $this->Visites;
    }

    public function addVisite(Visite $visite): static
    {
        if (!$this->Visites->contains($visite)) {
            $this->Visites->add($visite);
            $visite->setNft($this);
        }

        return $this;
    }

    public function removeVisite(Visite $visite): static
    {
        if ($this->Visites->removeElement($visite)) {
            // set the owning side to null (unless already changed)
            if ($visite->getNft() === $this) {
                $visite->setNft(null);
            }
        }

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }
}
