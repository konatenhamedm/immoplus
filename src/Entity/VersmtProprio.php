<?php

namespace App\Entity;

use App\Repository\VersmtProprioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VersmtProprioRepository::class)]
class VersmtProprio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idCptProprio = null;

    #[ORM\ManyToOne(inversedBy: 'versmtProprios')]
    private ?Proprio $Proprio = null;

    #[ORM\ManyToOne(inversedBy: 'versmtProprios')]
    private ?TypeVersements $type_versement = null;

    #[ORM\ManyToOne(inversedBy: 'versmtProprios')]
    private ?CptProprio $compte_Proprio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCptProprio(): ?int
    {
        return $this->idCptProprio;
    }

    public function setIdCptProprio(int $idCptProprio): static
    {
        $this->idCptProprio = $idCptProprio;

        return $this;
    }

    public function getProprio(): ?Proprio
    {
        return $this->Proprio;
    }

    public function setProprio(?Proprio $Proprio): static
    {
        $this->Proprio = $Proprio;

        return $this;
    }

    public function getTypeVersement(): ?TypeVersements
    {
        return $this->type_versement;
    }

    public function setTypeVersement(?TypeVersements $type_versement): static
    {
        $this->type_versement = $type_versement;

        return $this;
    }

    public function getCompteProprio(): ?CptProprio
    {
        return $this->compte_Proprio;
    }

    public function setCompteProprio(?CptProprio $compte_Proprio): static
    {
        $this->compte_Proprio = $compte_Proprio;

        return $this;
    }
}
