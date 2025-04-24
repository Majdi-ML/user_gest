<?php

namespace App\Entity;

use App\Repository\BlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nombre_etages = null;

    #[ORM\Column]
    private ?int $nombre_appartement_etage = null;

    #[ORM\Column]
    private ?int $nombre_totale_appartement = null;

    #[ORM\OneToMany(mappedBy: 'Bloc_id', targetEntity: Appartement::class)]
    private Collection $Appartements;

    public function __construct()
    {
        $this->Appartements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNombreEtages(): ?int
    {
        return $this->nombre_etages;
    }

    public function setNombreEtages(int $nombre_etages): static
    {
        $this->nombre_etages = $nombre_etages;

        return $this;
    }

    public function getNombreAppartementEtage(): ?int
    {
        return $this->nombre_appartement_etage;
    }

    public function setNombreAppartementEtage(int $nombre_appartement_etage): static
    {
        $this->nombre_appartement_etage = $nombre_appartement_etage;

        return $this;
    }

    public function getNombreTotaleAppartement(): ?int
    {
        return $this->nombre_totale_appartement;
    }

    public function setNombreTotaleAppartement(int $nombre_totale_appartement): static
    {
        $this->nombre_totale_appartement = $nombre_totale_appartement;

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getBlocId(): Collection
    {
        return $this->Appartements;
    }

    public function addBlocId(Appartement $blocId): static
    {
        if (!$this->Appartements->contains($blocId)) {
            $this->Appartements->add($blocId);
            $blocId->setBlocId($this);
        }

        return $this;
    }

    public function removeBlocId(Appartement $blocId): static
    {
        if ($this->Appartements->removeElement($blocId)) {
            // set the owning side to null (unless already changed)
            if ($blocId->getBlocId() === $this) {
                $blocId->setBlocId(null);
            }
        }

        return $this;
    }
}
