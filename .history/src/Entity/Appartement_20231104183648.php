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

    #[ORM\OneToMany(mappedBy: 'appart', targetEntity: Contratloc::class)]
    private Collection $appartContratlocs;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Factureloc::class)]
    private Collection $facturelocs;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Contratloc::class)]
    private Collection $contratlocs;

    public function __construct()
    {

        $this->facturelocs = new ArrayCollection();
        $this->contratlocs = new ArrayCollection();
       /* $this->appartementContratlocs = new ArrayCollection();*/
        $this->appartContratlocs = new ArrayCollection();
        $this->Oqp = 0;
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
    public function getAppartContratlocs(): Collection
    {
        return $this->appartContratlocs;
    }

    public function addAppartContratloc(Contratloc $appartContratloc): static
    {
        if (!$this->appartContratlocs->contains($appartContratloc)) {
            $this->appartContratlocs->add($appartContratloc);
            $appartContratloc->setAppart($this);
        }

        return $this;
    }

    public function removeAppartContratloc(Contratloc $appartContratloc): static
    {
        if ($this->appartContratlocs->removeElement($appartContratloc)) {
            // set the owning side to null (unless already changed)
            if ($appartContratloc->getAppart() === $this) {
                $appartContratloc->setAppart(null);
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

    /**
     * @return Collection<int, Contratloc>
     */
    public function getContratlocs(): Collection
    {
        return $this->contratlocs;
    }

    public function addContratloc(Contratloc $contratloc): static
    {
        if (!$this->contratlocs->contains($contratloc)) {
            $this->contratlocs->add($contratloc);
            $contratloc->setAppart($this);
        }

        return $this;
    }

    public function removeContratloc(Contratloc $contratloc): static
    {
        if ($this->contratlocs->removeElement($contratloc)) {
            // set the owning side to null (unless already changed)
            if ($contratloc->getAppart() === $this) {
                $contratloc->setAppart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contratloc>
     */
    public function getAppartContratlocs(): Collection
    {
        return $this->appartementContratlocs;
    }
}
