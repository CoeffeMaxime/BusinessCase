<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AcquisitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AcquisitionRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'acquisition:post'
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalisation context' => [
                'groups' => 'acquisition:item'
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['acquisition:put']
            ]
        ],
    ],
)]
class Acquisition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['acquisition:item','acquisition:post', 'user:item','user:put','nft:item'])]
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column]
    #[Groups(['acquisition:put','acquisition:post', 'user:item','user:put','nft:item'])]
    private ?bool $estVendu = null;

    #[ORM\ManyToOne(inversedBy: 'acquisitions')]
    #[Groups(['acquisition:item','acquisition:post','nft:item'])]
    private ?Nft $nft = null;

    #[ORM\ManyToOne(inversedBy: 'acquisitions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['acquisition:item','acquisition:post'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function isEstVendu(): ?bool
    {
        return $this->estVendu;
    }

    public function setEstVendu(bool $estVendu): static
    {
        $this->estVendu = $estVendu;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
