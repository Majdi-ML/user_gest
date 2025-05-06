<?php

namespace App\Entity;

use App\Repository\TypePapierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePapierRepository::class)]
class TypePapier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'typePapier', targetEntity: Papier::class)]
    private Collection $Papiers;

    public function __construct()
    {
        $this->Papiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Papier>
     */
    public function getPapiers(): Collection
    {
        return $this->Papiers;
    }

    public function addPapier(Papier $papier): static
    {
        if (!$this->Papiers->contains($papier)) {
            $this->Papiers->add($papier);
            $papier->setTypePapier($this);
        }

        return $this;
    }

    public function removePapier(Papier $papier): static
    {
        if ($this->Papiers->removeElement($papier)) {
            // set the owning side to null (unless already changed)
            if ($papier->getTypePapier() === $this) {
                $papier->setTypePapier(null);
            }
        }

        return $this;
    }
}
