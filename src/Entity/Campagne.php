<?php

namespace App\Entity;

use App\Repository\CampagneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneRepository::class)]
class Campagne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibCampagne = null;

    #[ORM\Column]
    private ?int $NbreProprio = null;

    #[ORM\Column]
    private ?int $NbreLocataire = null;

    #[ORM\Column]
    private ?int $MntTotal = null;

    #[ORM\Column(length: 255)]
    private ?string $MntPaye = null;

    #[ORM\ManyToOne(inversedBy: 'campagnes')]
    private ?Annee $annee = null;

    #[ORM\ManyToOne(inversedBy: 'campagnes')]
    private ?Tabmois $mois = null;

    #[ORM\OneToMany(mappedBy: 'compagne', targetEntity: Factureloc::class)]
    private Collection $facturelocs;

    public function __construct()
    {
        $this->facturelocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibCampagne(): ?string
    {
        return $this->LibCampagne;
    }

    public function setLibCampagne(string $LibCampagne): static
    {
        $this->LibCampagne = $LibCampagne;

        return $this;
    }

    public function getNbreProprio(): ?int
    {
        return $this->NbreProprio;
    }

    public function setNbreProprio(int $NbreProprio): static
    {
        $this->NbreProprio = $NbreProprio;

        return $this;
    }

    public function getNbreLocataire(): ?int
    {
        return $this->NbreLocataire;
    }

    public function setNbreLocataire(int $NbreLocataire): static
    {
        $this->NbreLocataire = $NbreLocataire;

        return $this;
    }

    public function getMntTotal(): ?int
    {
        return $this->MntTotal;
    }

    public function setMntTotal(int $MntTotal): static
    {
        $this->MntTotal = $MntTotal;

        return $this;
    }

    public function getMntPaye(): ?string
    {
        return $this->MntPaye;
    }

    public function setMntPaye(string $MntPaye): static
    {
        $this->MntPaye = $MntPaye;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMois(): ?Tabmois
    {
        return $this->mois;
    }

    public function setMois(?Tabmois $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * @return Collection<int, Factureloc>
     */
    public function getFacturelocs(): Collection
    {
        return $this->facturelocs;
    }

    public function addFactureloc(Factureloc $factureloc): static
    {
        if (!$this->facturelocs->contains($factureloc)) {
            $this->facturelocs->add($factureloc);
            $factureloc->setCompagne($this);
        }

        return $this;
    }

    public function removeFactureloc(Factureloc $factureloc): static
    {
        if ($this->facturelocs->removeElement($factureloc)) {
            // set the owning side to null (unless already changed)
            if ($factureloc->getCompagne() === $this) {
                $factureloc->setCompagne(null);
            }
        }

        return $this;
    }
}
