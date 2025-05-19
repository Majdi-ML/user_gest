<?php

namespace App\Entity;

use App\Repository\FraisSyndicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FraisSyndicRepository::class)]
class FraisSyndic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\OneToMany(mappedBy: 'Montant', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    public function __construct()
    {
        $this->cautionnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, Cautionnement>
     */
    public function getCautionnements(): Collection
    {
        return $this->cautionnements;
    }

    public function addCautionnement(Cautionnement $cautionnement): static
    {
        if (!$this->cautionnements->contains($cautionnement)) {
            $this->cautionnements->add($cautionnement);
            $cautionnement->setMontant($this);
        }

        return $this;
    }

    public function removeCautionnement(Cautionnement $cautionnement): static
    {
        if ($this->cautionnements->removeElement($cautionnement)) {
            // set the owning side to null (unless already changed)
            if ($cautionnement->getMontant() === $this) {
                $cautionnement->setMontant(null);
            }
        }

        return $this;
    }
}
