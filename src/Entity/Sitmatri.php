<?php

namespace App\Entity;

use App\Repository\SitmatriRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitmatriRepository::class)]
class Sitmatri
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibSituation = null;

    #[ORM\OneToMany(mappedBy: 'situationMatri', targetEntity: Locataire::class)]
    private Collection $locataires;

    public function __construct()
    {
        $this->locataires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibSituation(): ?string
    {
        return $this->LibSituation;
    }

    public function setLibSituation(string $LibSituation): static
    {
        $this->LibSituation = $LibSituation;

        return $this;
    }

    /**
     * @return Collection<int, Locataire>
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }

    public function addLocataire(Locataire $locataire): static
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires->add($locataire);
            $locataire->setSituationMatri($this);
        }

        return $this;
    }

    public function removeLocataire(Locataire $locataire): static
    {
        if ($this->locataires->removeElement($locataire)) {
            // set the owning side to null (unless already changed)
            if ($locataire->getSituationMatri() === $this) {
                $locataire->setSituationMatri(null);
            }
        }

        return $this;
    }
}
