<?php

namespace App\Entity;

use App\Repository\LocataireRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocataireRepository::class)]
class Locataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NPrenoms = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $DateNaiss = null;

    #[ORM\Column(length: 255)]
    private ?string $LieuNaiss = null;

    
    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?FichierAdmin $InfoPiece = null;

   
    #[ORM\Column(length: 255)]
    private ?string $Profession = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $Ethnie = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $NbEnfts = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $NbPersChge = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $Pere = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $Mere = null;

    #[ORM\Column(length: 255)]
    private ?string $Contacts = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    private ?string $NPConjointe = null;

    #[ORM\Column(length: 255)]
    private ?string $ProfConj = null;

    #[ORM\Column(length: 255)]
    private ?string $EthnieConj = null;

    #[ORM\Column(length: 255)]
    private ?string $ContactConj = null;

    #[ORM\Column(length: 255)]
    private ?string $Genre = null;

    #[ORM\Column(length: 255,nul)]
    private ?string $VivezAvec = null;

    #[ORM\ManyToOne(inversedBy: 'locataires')]
    private ?Sitmatri $situationMatri = null;

    #[ORM\OneToMany(mappedBy: 'locataire', targetEntity: Contratloc::class)]
    private Collection $Contratlocs;

    #[ORM\OneToMany(mappedBy: 'locataire', targetEntity: CompteLoc::class)]
    private Collection $compteLocs;

    #[ORM\OneToMany(mappedBy: 'locataire', targetEntity: Factureloc::class)]
    private Collection $facturelocs;

    public function __construct()
    {
        $this->Contratlocs = new ArrayCollection();
        $this->compteLocs = new ArrayCollection();
        $this->facturelocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNPrenoms(): ?string
    {
        return $this->NPrenoms;
    }

    public function setNPrenoms(string $NPrenoms): static
    {
        $this->NPrenoms = $NPrenoms;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->DateNaiss;
    }

    public function setDateNaiss(\DateTimeInterface $DateNaiss): static
    {
        $this->DateNaiss = $DateNaiss;

        return $this;
    }

    public function getLieuNaiss(): ?string
    {
        return $this->LieuNaiss;
    }

    public function setLieuNaiss(string $LieuNaiss): static
    {
        $this->LieuNaiss = $LieuNaiss;

        return $this;
    }

    public function getInfoPiece(): ?FichierAdmin
    {
        return $this->InfoPiece;
    }

    public function setInfoPiece(FichierAdmin $InfoPiece): static
    {
        $this->InfoPiece = $InfoPiece;

        return $this;
    }

  

    public function getProfession(): ?string
    {
        return $this->Profession;
    }

    public function setProfession(string $Profession): static
    {
        $this->Profession = $Profession;

        return $this;
    }

    public function getEthnie(): ?string
    {
        return $this->Ethnie;
    }

    public function setEthnie(string $Ethnie): static
    {
        $this->Ethnie = $Ethnie;

        return $this;
    }

    public function getNbEnfts(): ?string
    {
        return $this->NbEnfts;
    }

    public function setNbEnfts(string $NbEnfts): static
    {
        $this->NbEnfts = $NbEnfts;

        return $this;
    }

    public function getNbPersChge(): ?string
    {
        return $this->NbPersChge;
    }

    public function setNbPersChge(string $NbPersChge): static
    {
        $this->NbPersChge = $NbPersChge;

        return $this;
    }

    public function getPere(): ?string
    {
        return $this->Pere;
    }

    public function setPere(string $Pere): static
    {
        $this->Pere = $Pere;

        return $this;
    }

    public function getMere(): ?string
    {
        return $this->Mere;
    }

    public function setMere(string $Mere): static
    {
        $this->Mere = $Mere;

        return $this;
    }

    public function getContacts(): ?string
    {
        return $this->Contacts;
    }

    public function setContacts(string $Contacts): static
    {
        $this->Contacts = $Contacts;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getNPConjointe(): ?string
    {
        return $this->NPConjointe;
    }

    public function setNPConjointe(string $NPConjointe): static
    {
        $this->NPConjointe = $NPConjointe;

        return $this;
    }

    public function getProfConj(): ?string
    {
        return $this->ProfConj;
    }

    public function setProfConj(string $ProfConj): static
    {
        $this->ProfConj = $ProfConj;

        return $this;
    }

    public function getEthnieConj(): ?string
    {
        return $this->EthnieConj;
    }

    public function setEthnieConj(string $EthnieConj): static
    {
        $this->EthnieConj = $EthnieConj;

        return $this;
    }

    public function getContactConj(): ?string
    {
        return $this->ContactConj;
    }

    public function setContactConj(string $ContactConj): static
    {
        $this->ContactConj = $ContactConj;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(string $Genre): static
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getVivezAvec(): ?string
    {
        return $this->VivezAvec;
    }

    public function setVivezAvec(string $VivezAvec): static
    {
        $this->VivezAvec = $VivezAvec;

        return $this;
    }

    public function getSituationMatri(): ?Sitmatri
    {
        return $this->situationMatri;
    }

    public function setSituationMatri(?Sitmatri $situationMatri): static
    {
        $this->situationMatri = $situationMatri;

        return $this;
    }

    /**
     * @return Collection<int, Contratloc>
     */
    public function getContratlocs(): Collection
    {
        return $this->Contratlocs;
    }

    public function addContratloc(Contratloc $Contratloc): static
    {
        if (!$this->Contratlocs->contains($Contratloc)) {
            $this->Contratlocs->add($Contratloc);
            $Contratloc->setLocataire($this);
        }

        return $this;
    }

    public function removeContratloc(Contratloc $Contratloc): static
    {
        if ($this->Contratlocs->removeElement($Contratloc)) {
            // set the owning side to null (unless already changed)
            if ($Contratloc->getLocataire() === $this) {
                $Contratloc->setLocataire(null);
            }
        }

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
            $compteLoc->setLocataire($this);
        }

        return $this;
    }

    public function removeCompteLoc(CompteLoc $compteLoc): static
    {
        if ($this->compteLocs->removeElement($compteLoc)) {
            // set the owning side to null (unless already changed)
            if ($compteLoc->getLocataire() === $this) {
                $compteLoc->setLocataire(null);
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
            $factureloc->setLocataire($this);
        }

        return $this;
    }

    public function removeFactureloc(Factureloc $factureloc): static
    {
        if ($this->facturelocs->removeElement($factureloc)) {
            // set the owning side to null (unless already changed)
            if ($factureloc->getLocataire() === $this) {
                $factureloc->setLocataire(null);
            }
        }

        return $this;
    }
}
