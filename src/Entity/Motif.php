<?php

namespace App\Entity;

use App\Repository\MotifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotifRepository::class)]
class Motif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $LibMotif = null;

    #[ORM\OneToMany(mappedBy: 'motif', targetEntity: Fincontrat::class)]
    private Collection $fincontrats;

    public function __construct()
    {
        $this->fincontrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibMotif(): ?string
    {
        return $this->LibMotif;
    }

    public function setLibMotif(string $LibMotif): static
    {
        $this->LibMotif = $LibMotif;

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
            $fincontrat->setMotif($this);
        }

        return $this;
    }

    public function removeFincontrat(Fincontrat $fincontrat): static
    {
        if ($this->fincontrats->removeElement($fincontrat)) {
            // set the owning side to null (unless already changed)
            if ($fincontrat->getMotif() === $this) {
                $fincontrat->setMotif(null);
            }
        }

        return $this;
    }
}
