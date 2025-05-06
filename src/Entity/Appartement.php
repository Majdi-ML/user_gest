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

    

    

    #[ORM\ManyToOne(inversedBy: 'appartements')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Personne $locataire = null;

    

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Cautionnement::class)]
    private Collection $cautionnements;

    #[ORM\ManyToOne(inversedBy: 'appartements')]
    private ?Bloc $bloc = null;

    #[ORM\ManyToOne(inversedBy: 'appartementsProp')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Personne $proprietaire = null;

    public function __construct()
    {
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

    


    

    


    public function __toString(): string
{
    $blocNom = $this->bloc ? $this->bloc->getNom() : 'Bloc inconnu';
    return sprintf('%s - Étage %s - N° %s', $blocNom, $this->etage, $this->numero);
}

    public function getLocataire(): ?Personne
    {
        return $this->locataire;
    }

    public function setLocataire(?Personne $locataire): static
    {
        $this->locataire = $locataire;

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
            $cautionnement->setAppartement($this);
        }

        return $this;
    }

    public function removeCautionnement(Cautionnement $cautionnement): static
    {
        if ($this->cautionnements->removeElement($cautionnement)) {
            // set the owning side to null (unless already changed)
            if ($cautionnement->getAppartement() === $this) {
                $cautionnement->setAppartement(null);
            }
        }

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getProprietaire(): ?Personne
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Personne $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

}
