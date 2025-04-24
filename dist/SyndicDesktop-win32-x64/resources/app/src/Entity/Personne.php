<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: Appartement::class, inversedBy: 'personnes')]
    private Collection $Appartements;

    #[ORM\OneToMany(mappedBy: 'Personne_id', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    public function __construct()
    {
        $this->Appartements = new ArrayCollection();
        $this->cautionnements = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartements(): Collection
    {
        return $this->Appartements;
    }

    public function addAppartement(Appartement $appartement): static
    {
        if (!$this->Appartements->contains($appartement)) {
            $this->Appartements->add($appartement);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): static
    {
        $this->Appartements->removeElement($appartement);

        return $this;
    }

    /**
     * @return Collection<int, Cautionnement>
     */
    public function getPersonneId(): Collection
    {
        return $this->cautionnements;
    }

    public function addPersonneId(Cautionnement $personneId): static
    {
        if (!$this->cautionnements->contains($personneId)) {
            $this->cautionnements->add($personneId);
            $personneId->setPersonneId($this);
        }

        return $this;
    }

    public function removePersonneId(Cautionnement $personneId): static
    {
        if ($this->cautionnements->removeElement($personneId)) {
            // set the owning side to null (unless already changed)
            if ($personneId->getPersonneId() === $this) {
                $personneId->setPersonneId(null);
            }
        }

        return $this;
    }
}
