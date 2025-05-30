<?php

namespace App\Entity;

use App\Repository\FonctionBureauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FonctionBureauRepository::class)]
class FonctionBureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'fonction', targetEntity: Bureau::class)]
    private Collection $bureaus;

    public function __construct()
    {
        $this->bureaus = new ArrayCollection();
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
     * @return Collection<int, Bureau>
     */
    public function getBureaus(): Collection
    {
        return $this->bureaus;
    }

    public function addBureau(Bureau $bureau): static
    {
        if (!$this->bureaus->contains($bureau)) {
            $this->bureaus->add($bureau);
            $bureau->setFonction($this);
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): static
    {
        if ($this->bureaus->removeElement($bureau)) {
            // set the owning side to null (unless already changed)
            if ($bureau->getFonction() === $this) {
                $bureau->setFonction(null);
            }
        }

        return $this;
    }
}
