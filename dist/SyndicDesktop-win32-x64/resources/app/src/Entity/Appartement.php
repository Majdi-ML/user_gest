<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartementRepository::class)]
class Appartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $etage = null;

    #[ORM\Column]
    private ?bool $est_habite = null;

    #[ORM\ManyToOne(inversedBy: 'Appartements')]
    private ?Bloc $Bloc_id = null;

    #[ORM\ManyToMany(targetEntity: Personne::class, mappedBy: 'Appartements')]
    private Collection $personnes;

    #[ORM\OneToMany(mappedBy: 'appartement_id', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Personne $locataire_id = null;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
        $this->cautionnements = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(string $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function isEstHabite(): ?bool
    {
        return $this->est_habite;
    }

    public function setEstHabite(bool $est_habite): static
    {
        $this->est_habite = $est_habite;

        return $this;
    }

    public function getBlocId(): ?Bloc
    {
        return $this->Bloc_id;
    }

    public function setBlocId(?Bloc $Bloc_id): static
    {
        $this->Bloc_id = $Bloc_id;

        return $this;
    }

    /**
     * @return Collection<int, Personne>
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): static
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes->add($personne);
            $personne->addAppartement($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): static
    {
        if ($this->personnes->removeElement($personne)) {
            $personne->removeAppartement($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Cautionnement>
     */
    public function getCautionnementId(): Collection
    {
        return $this->cautionnements;
    }

    public function addCautionnementId(Cautionnement $cautionnementId): static
    {
        if (!$this->cautionnements->contains($cautionnementId)) {
            $this->cautionnements->add($cautionnementId);
            $cautionnementId->setAppartementId($this);
        }

        return $this;
    }

    public function removeCautionnementId(Cautionnement $cautionnementId): static
    {
        if ($this->cautionnements->removeElement($cautionnementId)) {
            // set the owning side to null (unless already changed)
            if ($cautionnementId->getAppartementId() === $this) {
                $cautionnementId->setAppartementId(null);
            }
        }

        return $this;
    }

    public function getLocataireId(): ?Personne
    {
        return $this->locataire_id;
    }

    public function setLocataireId(?Personne $locataire_id): static
    {
        $this->locataire_id = $locataire_id;

        return $this;
    }


}
