<?php

namespace App\Entity;

use App\Repository\TypemaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypemaisonRepository::class)]
class Typemaison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibType = null;

    #[ORM\OneToMany(mappedBy: 'Typemaison', targetEntity: Maison::class)]
    private Collection $maisons;

    #[ORM\OneToMany(mappedBy: 'typeMaison', targetEntity: Maison::class)]
    private Collection $typeMaisonMaisons;

    public function __construct()
    {
        $this->maisons = new ArrayCollection();
        $this->typeMaisonMaisons = new ArrayCollection();
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

    /**
     * @return Collection<int, Maison>
     */
    public function getMaisons(): Collection
    {
        return $this->maisons;
    }

    public function addMaison(Maison $maison): static
    {
        if (!$this->maisons->contains($maison)) {
            $this->maisons->add($maison);
            $maison->setTypemaison($this);
        }

        return $this;
    }

    public function removeMaison(Maison $maison): static
    {
        if ($this->maisons->removeElement($maison)) {
            // set the owning side to null (unless already changed)
            if ($maison->getTypemaison() === $this) {
                $maison->setTypemaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Maison>
     */
    public function getTypeMaisonMaisons(): Collection
    {
        return $this->typeMaisonMaisons;
    }

    public function addTypeMaisonMaison(Maison $typeMaisonMaison): static
    {
        if (!$this->typeMaisonMaisons->contains($typeMaisonMaison)) {
            $this->typeMaisonMaisons->add($typeMaisonMaison);
            $typeMaisonMaison->setTypeMaison($this);
        }

        return $this;
    }

    public function removeTypeMaisonMaison(Maison $typeMaisonMaison): static
    {
        if ($this->typeMaisonMaisons->removeElement($typeMaisonMaison)) {
            // set the owning side to null (unless already changed)
            if ($typeMaisonMaison->getTypeMaison() === $this) {
                $typeMaisonMaison->setTypeMaison(null);
            }
        }

        return $this;
    }
}
