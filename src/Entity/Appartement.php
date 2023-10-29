<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartementRepository::class)]
class Appartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibAppart = null;

    #[ORM\Column]
    private ?int $NbrePieces = null;

    #[ORM\Column]
    private ?int $NumEtage = null;

    #[ORM\Column]
    private ?int $Loyer = null;

    #[ORM\Column(nullable: true)]
    private ?int $Caution = null;

    #[ORM\Column(length: 255)]
    private ?string $Details = null;

    #[ORM\Column(nullable: true)]
    private ?int $Oqp = null;

    #[ORM\ManyToOne(inversedBy: 'appartements')]
    private ?Maison $maisson = null;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Contratloc::class)]
    private Collection $DateDebut;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Factureloc::class)]
    private Collection $facturelocs;

    public function __construct()
    {
        $this->DateDebut = new ArrayCollection();
        $this->facturelocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibAppart(): ?string
    {
        return $this->LibAppart;
    }

    public function setLibAppart(string $LibAppart): static
    {
        $this->LibAppart = $LibAppart;

        return $this;
    }

    public function getNbrePieces(): ?int
    {
        return $this->NbrePieces;
    }

    public function setNbrePieces(int $NbrePieces): static
    {
        $this->NbrePieces = $NbrePieces;

        return $this;
    }

    public function getNumEtage(): ?int
    {
        return $this->NumEtage;
    }

    public function setNumEtage(int $NumEtage): static
    {
        $this->NumEtage = $NumEtage;

        return $this;
    }

    public function getLoyer(): ?int
    {
        return $this->Loyer;
    }

    public function setLoyer(int $Loyer): static
    {
        $this->Loyer = $Loyer;

        return $this;
    }

    public function getCaution(): ?int
    {
        return $this->Caution;
    }

    public function setCaution(int $Caution): static
    {
        $this->Caution = $Caution;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->Details;
    }

    public function setDetails(string $Details): static
    {
        $this->Details = $Details;

        return $this;
    }

    public function getOqp(): ?int
    {
        return $this->Oqp;
    }

    public function setOqp(int $Oqp): static
    {
        $this->Oqp = $Oqp;

        return $this;
    }

    public function getMaisson(): ?Maison
    {
        return $this->maisson;
    }

    public function setMaisson(?Maison $maisson): static
    {
        $this->maisson = $maisson;

        return $this;
    }

    /**
     * @return Collection<int, Contratloc>
     */
    public function getDateDebut(): Collection
    {
        return $this->DateDebut;
    }

    public function addDateDebut(Contratloc $dateDebut): static
    {
        if (!$this->DateDebut->contains($dateDebut)) {
            $this->DateDebut->add($dateDebut);
            $dateDebut->setAppartement($this);
        }

        return $this;
    }

    public function removeDateDebut(Contratloc $dateDebut): static
    {
        if ($this->DateDebut->removeElement($dateDebut)) {
            // set the owning side to null (unless already changed)
            if ($dateDebut->getAppartement() === $this) {
                $dateDebut->setAppartement(null);
            }
        }

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
            $factureloc->setAppartement($this);
        }

        return $this;
    }

    public function removeFactureloc(Factureloc $factureloc): static
    {
        if ($this->facturelocs->removeElement($factureloc)) {
            // set the owning side to null (unless already changed)
            if ($factureloc->getAppartement() === $this) {
                $factureloc->setAppartement(null);
            }
        }

        return $this;
    }
}
