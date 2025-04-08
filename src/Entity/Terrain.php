<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $superfice = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $justification = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $telClient = null;

    #[ORM\Column(length: 255)]
    private ?string $localisationClient = null;

    #[ORM\OneToMany(mappedBy: 'terrain', targetEntity: CompteCltT::class)]
    private Collection $compteCltTs;

    #[ORM\ManyToOne(inversedBy: 'terrain')]
    private ?Site $site = null;

    public function __construct()
    {
        $this->compteCltTs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuperfice(): ?string
    {
        return $this->superfice;
    }

    public function setSuperfice(string $superfice): static
    {
        $this->superfice = $superfice;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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

    public function getJustification(): ?string
    {
        return $this->justification;
    }

    public function setJustification(string $justification): static
    {
        $this->justification = $justification;

        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getTelClient(): ?string
    {
        return $this->telClient;
    }

    public function setTelClient(string $telClient): static
    {
        $this->telClient = $telClient;

        return $this;
    }

    public function getLocalisationClient(): ?string
    {
        return $this->localisationClient;
    }

    public function setLocalisationClient(string $localisationClient): static
    {
        $this->localisationClient = $localisationClient;

        return $this;
    }

    /**
     * @return Collection<int, CompteCltT>
     */
    public function getCompteCltTs(): Collection
    {
        return $this->compteCltTs;
    }

    public function addCompteCltT(CompteCltT $compteCltT): static
    {
        if (!$this->compteCltTs->contains($compteCltT)) {
            $this->compteCltTs->add($compteCltT);
            $compteCltT->setTerrain($this);
        }

        return $this;
    }

    public function removeCompteCltT(CompteCltT $compteCltT): static
    {
        if ($this->compteCltTs->removeElement($compteCltT)) {
            // set the owning side to null (unless already changed)
            if ($compteCltT->getTerrain() === $this) {
                $compteCltT->setTerrain(null);
            }
        }

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }
}
