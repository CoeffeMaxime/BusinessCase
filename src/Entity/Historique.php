<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HistoriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['historique:list']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['historique:post']
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['historique:item']
            ]
        ],
    ],
)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['historique:list','historique:post','historique:item','nft:list','nft:item'])]
    private ?\DateTimeInterface $dateJour = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['historique:list','historique:post','historique:item','nft:list','nft:item'])]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nft $nft = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateJour(): ?\DateTimeInterface
    {
        return $this->dateJour;
    }

    public function setDateJour(\DateTimeInterface $dateJour): static
    {
        $this->dateJour = $dateJour;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getNft(): ?Nft
    {
        return $this->nft;
    }

    public function setNft(?Nft $nft): static
    {
        $this->nft = $nft;

        return $this;
    }
}
