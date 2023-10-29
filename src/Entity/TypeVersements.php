<?php

namespace App\Entity;

use App\Repository\TypeVersementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeVersementsRepository::class)]
class TypeVersements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibType = null;

    #[ORM\Column(length: 255)]
    private ?string $CodTyp = null;

    #[ORM\OneToMany(mappedBy: 'type_versement', targetEntity: VersmtProprio::class)]
    private Collection $versmtProprios;

    #[ORM\OneToMany(mappedBy: 'typeversement', targetEntity: Reglements::class)]
    private Collection $reglements;

    public function __construct()
    {
        $this->versmtProprios = new ArrayCollection();
        $this->reglements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibType(): ?string
    {
        return $this->LibType;
    }

    public function setLibType(string $LibType): static
    {
        $this->LibType = $LibType;

        return $this;
    }

    public function getCodTyp(): ?string
    {
        return $this->CodTyp;
    }

    public function setCodTyp(string $CodTyp): static
    {
        $this->CodTyp = $CodTyp;

        return $this;
    }

    /**
     * @return Collection<int, VersmtProprio>
     */
    public function getVersmtProprios(): Collection
    {
        return $this->versmtProprios;
    }

    public function addVersmtProprio(VersmtProprio $versmtProprio): static
    {
        if (!$this->versmtProprios->contains($versmtProprio)) {
            $this->versmtProprios->add($versmtProprio);
            $versmtProprio->setTypeVersement($this);
        }

        return $this;
    }

    public function removeVersmtProprio(VersmtProprio $versmtProprio): static
    {
        if ($this->versmtProprios->removeElement($versmtProprio)) {
            // set the owning side to null (unless already changed)
            if ($versmtProprio->getTypeVersement() === $this) {
                $versmtProprio->setTypeVersement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reglements>
     */
    public function getReglements(): Collection
    {
        return $this->reglements;
    }

    public function addReglement(Reglements $reglement): static
    {
        if (!$this->reglements->contains($reglement)) {
            $this->reglements->add($reglement);
            $reglement->setTypeversement($this);
        }

        return $this;
    }

    public function removeReglement(Reglements $reglement): static
    {
        if ($this->reglements->removeElement($reglement)) {
            // set the owning side to null (unless already changed)
            if ($reglement->getTypeversement() === $this) {
                $reglement->setTypeversement(null);
            }
        }

        return $this;
    }
}
