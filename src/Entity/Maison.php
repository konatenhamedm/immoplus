<?php

namespace App\Entity;

use App\Repository\MaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MaisonRepository::class)]
class Maison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibMaison = null;

    #[ORM\Column(length: 255)]
    private ?string $Localisation = null;

    #[ORM\Column(length: 255)]
    private ?string $Lot = null;

    #[ORM\Column(length: 255)]
    private ?string $Ilot = null;

    #[ORM\Column(length: 255)]
    private ?string $TFoncier = null;

    #[ORM\Column]
    private ?int $MntCom = null;

    #[ORM\OneToMany(mappedBy: 'maisson', targetEntity: Appartement::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $appartements;

    #[ORM\ManyToOne(inversedBy: 'quartierMaisons')]
    private ?Quartier $quartier = null;

    #[ORM\ManyToOne(inversedBy: 'proprioMaisons')]
    private ?Proprio $proprio = null;

    #[ORM\ManyToOne(inversedBy: 'typeMaisonMaisons')]
    private ?Typemaison $typeMaison = null;

    #[ORM\ManyToOne(inversedBy: 'maisons')]
    /* #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(on: 'create')]*/
    private ?Utilisateur $IdAgent = null;

    /* #[ORM\ManyToOne(inversedBy: 'maisons')]
    private ?Quartier $quartier = null;

    #[ORM\ManyToOne(inversedBy: 'maisons2')]
    private ?Proprio $Proprio = null;

    #[ORM\ManyToOne(inversedBy: 'maisons3')]
    private ?Typemaison $Typemaison = null; */

    public function __construct()
    {
        $this->appartements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibMaison(): ?string
    {
        return $this->LibMaison;
    }

    public function setLibMaison(string $LibMaison): static
    {
        $this->LibMaison = $LibMaison;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->Localisation;
    }

    public function setLocalisation(string $Localisation): static
    {
        $this->Localisation = $Localisation;

        return $this;
    }

    public function getLot(): ?string
    {
        return $this->Lot;
    }

    public function setLot(string $Lot): static
    {
        $this->Lot = $Lot;

        return $this;
    }

    public function getIlot(): ?string
    {
        return $this->Ilot;
    }

    public function setIlot(string $Ilot): static
    {
        $this->Ilot = $Ilot;

        return $this;
    }

    public function getTFoncier(): ?string
    {
        return $this->TFoncier;
    }

    public function setTFoncier(string $TFoncier): static
    {
        $this->TFoncier = $TFoncier;

        return $this;
    }

    public function getMntCom(): ?int
    {
        return $this->MntCom;
    }

    public function setMntCom(int $MntCom): static
    {
        $this->MntCom = $MntCom;

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartements(): Collection
    {
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): static
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements->add($appartement);
            $appartement->setMaisson($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): static
    {
        if ($this->appartements->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getMaisson() === $this) {
                $appartement->setMaisson(null);
            }
        }

        return $this;
    }


    /* public function getQuartier(): ?Quartier
    {
        return $this->quartier;
    }

    public function setQuartier(?Quartier $quartier): static
    {
        $this->quartier = $quartier;

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

    public function getTypemaison(): ?Typemaison
    {
        return $this->Typemaison;
    }

    public function setTypemaison(?Typemaison $Typemaison): static
    {
        $this->Typemaison = $Typemaison;

        return $this;
    } */

    public function getQuartier(): ?Quartier
    {
        return $this->quartier;
    }

    public function setQuartier(?Quartier $quartier): static
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getProprio(): ?Proprio
    {
        return $this->proprio;
    }

    public function setProprio(?Proprio $proprio): static
    {
        $this->proprio = $proprio;

        return $this;
    }

    public function getTypeMaison(): ?Typemaison
    {
        return $this->typeMaison;
    }

    public function setTypeMaison(?Typemaison $typeMaison): static
    {
        $this->typeMaison = $typeMaison;

        return $this;
    }

    public function getIdAgent(): ?Utilisateur
    {
        return $this->IdAgent;
    }

    public function setIdAgent(?Utilisateur $IdAgent): static
    {
        $this->IdAgent = $IdAgent;

        return $this;
    }
}
