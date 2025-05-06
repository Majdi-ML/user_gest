<?php

namespace App\Entity;

use App\Repository\TypeRassmblementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRassmblementRepository::class)]
class TypeRassmblement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'Type', targetEntity: Rassemeblement::class)]
    private Collection $rassemeblements;

    public function __construct()
    {
        $this->rassemeblements = new ArrayCollection();
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
     * @return Collection<int, Rassemeblement>
     */
    public function getRassemeblements(): Collection
    {
        return $this->rassemeblements;
    }

    public function addRassemeblement(Rassemeblement $rassemeblement): static
    {
        if (!$this->rassemeblements->contains($rassemeblement)) {
            $this->rassemeblements->add($rassemeblement);
            $rassemeblement->setType($this);
        }

        return $this;
    }

    public function removeRassemeblement(Rassemeblement $rassemeblement): static
    {
        if ($this->rassemeblements->removeElement($rassemeblement)) {
            // set the owning side to null (unless already changed)
            if ($rassemeblement->getType() === $this) {
                $rassemeblement->setType(null);
            }
        }

        return $this;
    }
}
