<?php

namespace App\Entity;

use App\Repository\LigneversementfraisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneversementfraisRepository::class)]
class Ligneversementfrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateversementfrais = null;

    #[ORM\Column(length: 255)]
    private ?string $montantverse = null;

    #[ORM\ManyToOne(inversedBy: 'ligneversementfrais')]
    private ?ComptecltT $comptecltT = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateversementfrais(): ?\DateTimeInterface
    {
        return $this->dateversementfrais;
    }

    public function setDateversementfrais(\DateTimeInterface $dateversementfrais): static
    {
        $this->dateversementfrais = $dateversementfrais;

        return $this;
    }

    public function getMontantverse(): ?string
    {
        return $this->montantverse;
    }

    public function setMontantverse(string $montantverse): static
    {
        $this->montantverse = $montantverse;

        return $this;
    }

    public function getComptecltT(): ?ComptecltT
    {
        return $this->comptecltT;
    }

    public function setComptecltT(?ComptecltT $comptecltT): static
    {
        $this->comptecltT = $comptecltT;

        return $this;
    }
}
