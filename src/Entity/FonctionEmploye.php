<?php

namespace App\Entity;

use App\Repository\FonctionEmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FonctionEmployeRepository::class)]
class FonctionEmploye
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'fonctionEmploye', targetEntity: Employe::class)]
    private Collection $Employes;

    public function __construct()
    {
        $this->Employes = new ArrayCollection();
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
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->Employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->Employes->contains($employe)) {
            $this->Employes->add($employe);
            $employe->setFonctionEmploye($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->Employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getFonctionEmploye() === $this) {
                $employe->setFonctionEmploye(null);
            }
        }

        return $this;
    }
}
