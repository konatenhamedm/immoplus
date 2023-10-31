<?php

namespace App\Entity;

use App\Repository\ContratlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratlocRepository::class)]
class Contratloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Contratlocs')]
    private ?Locataire $locataire = null;

    #[ORM\ManyToOne(inversedBy: 'DateDebut')]
    private ?appartement $appartement = null;

    #[ORM\Column(nullable:true)]
    private ?int $DateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?int $DateFin = null;

    #[ORM\Column]
    private ?int $NbMoisCaution = null;

    #[ORM\Column]
    private ?int $MntCaution = null;

    #[ORM\Column]
    private ?int $NbMoisAvance = null;

    #[ORM\Column]
    private ?int $MntAvance = null;

    #[ORM\Column(nullable: true)]
    private ?int $MntLoyer = null;

    #[ORM\Column(length: 255,nulla)]
    private ?string $AutreInfos = null;

    #[ORM\Column(length: 255)]
    private ?string $ScanContrat = null;

    #[ORM\ManyToOne(inversedBy: 'Contratlocs')]
    private ?Regime $Regime = null;

    #[ORM\Column]
    private ?int $DateEntree = null;

    #[ORM\Column]
    private ?int $DateProchVers = null;

    #[ORM\ManyToOne(inversedBy: 'Contratlocs')]
    private ?Nature $Nature = null;

    #[ORM\Column]
    private ?int $MntLoyerPrec = null;

    #[ORM\Column]
    private ?int $MntLoyerIni = null;

    #[ORM\Column]
    private ?int $MntLoyerActu = null;

    #[ORM\Column]
    private ?int $MntArriere = null;

    #[ORM\Column(length: 255)]
    private ?string $DejaLocataire = null;

    #[ORM\Column(length: 255)]
    private ?string $StatutLoc = null;

    #[ORM\Column]
    private ?int $Fraisanex = null;

    #[ORM\Column]
    private ?int $Etat = null;

    #[ORM\Column]
    private ?int $TotVerse = null;

    #[ORM\OneToMany(mappedBy: 'contrat', targetEntity: CompteLoc::class)]
    private Collection $compteLocs;

    #[ORM\OneToMany(mappedBy: 'contrat', targetEntity: Factureloc::class)]
    private Collection $facturelocs;

    #[ORM\OneToMany(mappedBy: 'contrat', targetEntity: Fincontrat::class)]
    private Collection $fincontrats;

    public function __construct()
    {
        $this->compteLocs = new ArrayCollection();
        $this->facturelocs = new ArrayCollection();
        $this->fincontrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): static
    {
        $this->locataire = $locataire;

        return $this;
    }

    public function getAppartement(): ?appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?appartement $appartement): static
    {
        $this->appartement = $appartement;

        return $this;
    }

    public function getDateDebut(): ?int
    {
        return $this->DateDebut;
    }

    public function setDateDebut(int $DateDebut): static
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?int
    {
        return $this->DateFin;
    }

    public function setDateFin(int $DateFin): static
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getNbMoisCaution(): ?int
    {
        return $this->NbMoisCaution;
    }

    public function setNbMoisCaution(int $NbMoisCaution): static
    {
        $this->NbMoisCaution = $NbMoisCaution;

        return $this;
    }

    public function getMntCaution(): ?int
    {
        return $this->MntCaution;
    }

    public function setMntCaution(int $MntCaution): static
    {
        $this->MntCaution = $MntCaution;

        return $this;
    }

    public function getNbMoisAvance(): ?int
    {
        return $this->NbMoisAvance;
    }

    public function setNbMoisAvance(int $NbMoisAvance): static
    {
        $this->NbMoisAvance = $NbMoisAvance;

        return $this;
    }

    public function getMntAvance(): ?int
    {
        return $this->MntAvance;
    }

    public function setMntAvance(int $MntAvance): static
    {
        $this->MntAvance = $MntAvance;

        return $this;
    }

    public function getMntLoyer(): ?int
    {
        return $this->MntLoyer;
    }

    public function setMntLoyer(int $MntLoyer): static
    {
        $this->MntLoyer = $MntLoyer;

        return $this;
    }

    public function getAutreInfos(): ?string
    {
        return $this->AutreInfos;
    }

    public function setAutreInfos(string $AutreInfos): static
    {
        $this->AutreInfos = $AutreInfos;

        return $this;
    }

    public function getScanContrat(): ?string
    {
        return $this->ScanContrat;
    }

    public function setScanContrat(string $ScanContrat): static
    {
        $this->ScanContrat = $ScanContrat;

        return $this;
    }

    public function getRegime(): ?Regime
    {
        return $this->Regime;
    }

    public function setRegime(?Regime $Regime): static
    {
        $this->Regime = $Regime;

        return $this;
    }

    public function getDateEntree(): ?int
    {
        return $this->DateEntree;
    }

    public function setDateEntree(int $DateEntree): static
    {
        $this->DateEntree = $DateEntree;

        return $this;
    }

    public function getDateProchVers(): ?int
    {
        return $this->DateProchVers;
    }

    public function setDateProchVers(int $DateProchVers): static
    {
        $this->DateProchVers = $DateProchVers;

        return $this;
    }

    public function getNature(): ?Nature
    {
        return $this->Nature;
    }

    public function setNature(?Nature $Nature): static
    {
        $this->Nature = $Nature;

        return $this;
    }

    public function getMntLoyerPrec(): ?int
    {
        return $this->MntLoyerPrec;
    }

    public function setMntLoyerPrec(int $MntLoyerPrec): static
    {
        $this->MntLoyerPrec = $MntLoyerPrec;

        return $this;
    }

    public function getMntLoyerIni(): ?int
    {
        return $this->MntLoyerIni;
    }

    public function setMntLoyerIni(int $MntLoyerIni): static
    {
        $this->MntLoyerIni = $MntLoyerIni;

        return $this;
    }

    public function getMntLoyerActu(): ?int
    {
        return $this->MntLoyerActu;
    }

    public function setMntLoyerActu(int $MntLoyerActu): static
    {
        $this->MntLoyerActu = $MntLoyerActu;

        return $this;
    }

    public function getMntArriere(): ?int
    {
        return $this->MntArriere;
    }

    public function setMntArriere(int $MntArriere): static
    {
        $this->MntArriere = $MntArriere;

        return $this;
    }

    public function getDejaLocataire(): ?string
    {
        return $this->DejaLocataire;
    }

    public function setDejaLocataire(string $DejaLocataire): static
    {
        $this->DejaLocataire = $DejaLocataire;

        return $this;
    }

    public function getStatutLoc(): ?string
    {
        return $this->StatutLoc;
    }

    public function setStatutLoc(string $StatutLoc): static
    {
        $this->StatutLoc = $StatutLoc;

        return $this;
    }

    public function getFraisanex(): ?int
    {
        return $this->Fraisanex;
    }

    public function setFraisanex(int $Fraisanex): static
    {
        $this->Fraisanex = $Fraisanex;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->Etat;
    }

    public function setEtat(int $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getTotVerse(): ?int
    {
        return $this->TotVerse;
    }

    public function setTotVerse(int $TotVerse): static
    {
        $this->TotVerse = $TotVerse;

        return $this;
    }

    /**
     * @return Collection<int, CompteLoc>
     */
    public function getCompteLocs(): Collection
    {
        return $this->compteLocs;
    }

    public function addCompteLoc(CompteLoc $compteLoc): static
    {
        if (!$this->compteLocs->contains($compteLoc)) {
            $this->compteLocs->add($compteLoc);
            $compteLoc->setContrat($this);
        }

        return $this;
    }

    public function removeCompteLoc(CompteLoc $compteLoc): static
    {
        if ($this->compteLocs->removeElement($compteLoc)) {
            // set the owning side to null (unless already changed)
            if ($compteLoc->getContrat() === $this) {
                $compteLoc->setContrat(null);
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
            $factureloc->setContrat($this);
        }

        return $this;
    }

    public function removeFactureloc(Factureloc $factureloc): static
    {
        if ($this->facturelocs->removeElement($factureloc)) {
            // set the owning side to null (unless already changed)
            if ($factureloc->getContrat() === $this) {
                $factureloc->setContrat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fincontrat>
     */
    public function getFincontrats(): Collection
    {
        return $this->fincontrats;
    }

    public function addFincontrat(Fincontrat $fincontrat): static
    {
        if (!$this->fincontrats->contains($fincontrat)) {
            $this->fincontrats->add($fincontrat);
            $fincontrat->setContrat($this);
        }

        return $this;
    }

    public function removeFincontrat(Fincontrat $fincontrat): static
    {
        if ($this->fincontrats->removeElement($fincontrat)) {
            // set the owning side to null (unless already changed)
            if ($fincontrat->getContrat() === $this) {
                $fincontrat->setContrat(null);
            }
        }

        return $this;
    }
}
