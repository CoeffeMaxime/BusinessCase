<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftRepository::class)]
#[ApiResource()]
class Nft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateVente = null;

    #[ORM\Column(length: 255)]
    private ?string $proprietaire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $valeurInitiale = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: historique::class)]
    private Collection $historiques;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: acquisition::class)]
    private Collection $acquisitions;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Visite::class)]
    private Collection $Visites;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?groupe $groupe = null;

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

    public function setDateVente(\DateTimeInterface $dateVente): static
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

    public function setCategorie(?categorie $categorie): static
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

    public function addHistorique(historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setNft($this);
        }

        return $this;
    }

    public function removeHistorique(historique $historique): static
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

    public function addAcquisition(acquisition $acquisition): static
    {
        if (!$this->acquisitions->contains($acquisition)) {
            $this->acquisitions->add($acquisition);
            $acquisition->setNft($this);
        }

        return $this;
    }

    public function removeAcquisition(acquisition $acquisition): static
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

    public function getGroupe(): ?groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }
}
