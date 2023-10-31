<?php

namespace App\Entity;

use App\Repository\CampagneContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneContratRepository::class)]
class CampagneContrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numEtage = null;

    #[ORM\Column(length: 255)]
    private ?string $numAppart = null;

    #[ORM\Column(length: 255)]
    private ?string $nbrePiece = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $details = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateLimite = null;

    #[ORM\ManyToOne(inversedBy: 'campagneContrats')]
    private ?Campagne $campagne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumEtage(): ?string
    {
        return $this->numEtage;
    }

    public function setNumEtage(string $numEtage): static
    {
        $this->numEtage = $numEtage;

        return $this;
    }

    public function getNumAppart(): ?string
    {
        return $this->numAppart;
    }

    public function setNumAppart(string $numAppart): static
    {
        $this->numAppart = $numAppart;

        return $this;
    }

    public function getNbrePiece(): ?string
    {
        return $this->nbrePiece;
    }

    public function setNbrePiece(string $nbrePiece): static
    {
        $this->nbrePiece = $nbrePiece;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeInterface $dateLimite): static
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getCampagne(): ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne): static
    {
        $this->campagne = $campagne;

        return $this;
    }
}
