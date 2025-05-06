<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\OneToMany(mappedBy: 'locataire', targetEntity: Appartement::class)]
    private Collection $appartements;

    #[ORM\OneToMany(mappedBy: 'Personne', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    #[ORM\OneToMany(mappedBy: 'proprietaire', targetEntity: Appartement::class)]
    private Collection $appartementsProp;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_achat = null;

    public function __construct()
    {
       
       
        $this->appartements = new ArrayCollection();
        $this->cautionnements = new ArrayCollection();
        $this->appartementsProp = new ArrayCollection();
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

    public function __toString()
{
    return $this->getNom() . ' ' . $this->getPrenom();
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
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): static
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements->add($appartement);
            $appartement->setLocataire($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): static
    {
        if ($this->appartements->removeElement($appartement)) {
            // set the owning side to null (unless already changed)
            if ($appartement->getLocataire() === $this) {
                $appartement->setLocataire(null);
            }
        }

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
            $cautionnement->setPersonne($this);
        }

        return $this;
    }

    public function removeCautionnement(Cautionnement $cautionnement): static
    {
        if ($this->cautionnements->removeElement($cautionnement)) {
            // set the owning side to null (unless already changed)
            if ($cautionnement->getPersonne() === $this) {
                $cautionnement->setPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartementsProp(): Collection
    {
        return $this->appartementsProp;
    }

    public function addAppartementsProp(Appartement $appartementsProp): static
    {
        if (!$this->appartementsProp->contains($appartementsProp)) {
            $this->appartementsProp->add($appartementsProp);
            $appartementsProp->setProprietaire($this);
        }

        return $this;
    }

    public function removeAppartementsProp(Appartement $appartementsProp): static
    {
        if ($this->appartementsProp->removeElement($appartementsProp)) {
            // set the owning side to null (unless already changed)
            if ($appartementsProp->getProprietaire() === $this) {
                $appartementsProp->setProprietaire(null);
            }
        }

        return $this;
    }

    public function getDateLocation(): ?\DateTimeInterface
    {
        return $this->Date_location;
    }

    public function setDateLocation(?\DateTimeInterface $Date_location): static
    {
        $this->Date_location = $Date_location;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): static
    {
        $this->date_achat = $date_achat;

        return $this;
    }
}
