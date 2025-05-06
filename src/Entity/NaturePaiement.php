<?php

namespace App\Entity;

use App\Repository\NaturePaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NaturePaiementRepository::class)]
class NaturePaiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nature = null;

    #[ORM\OneToMany(mappedBy: 'Nature_Paiement', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    public function __construct()
    {
        $this->cautionnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): static
    {
        $this->nature = $nature;

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
            $cautionnement->setNaturePaiement($this);
        }

        return $this;
    }

    public function removeCautionnement(Cautionnement $cautionnement): static
    {
        if ($this->cautionnements->removeElement($cautionnement)) {
            // set the owning side to null (unless already changed)
            if ($cautionnement->getNaturePaiement() === $this) {
                $cautionnement->setNaturePaiement(null);
            }
        }

        return $this;
    }
}
