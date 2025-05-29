<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Prenom = null;

    #[ORM\Column]
    private ?int $cin = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaire = null;

    #[ORM\Column]
    private ?float $cnss = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Couverture_sociale = null;

    #[ORM\Column(nullable: true)]
    private ?int $telephone = null;

    #[ORM\ManyToOne(inversedBy: 'Employes')]
    private ?FonctionEmploye $fonctionEmploye = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE ,nullable: true)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Cnss::class)]
    private Collection $cnsses;

    public function __construct()
    {
        $this->cnsses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getCnss(): ?float
    {
        return $this->cnss;
    }

    public function setCnss(float $cnss): static
    {
        $this->cnss = $cnss;

        return $this;
    }

    public function isCouvertureSociale(): ?bool
    {
        return $this->Couverture_sociale;
    }

    public function setCouvertureSociale(?bool $Couverture_sociale): static
    {
        $this->Couverture_sociale = $Couverture_sociale;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFonctionEmploye(): ?FonctionEmploye
    {
        return $this->fonctionEmploye;
    }

    public function setFonctionEmploye(?FonctionEmploye $fonctionEmploye): static
    {
        $this->fonctionEmploye = $fonctionEmploye;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    /**
     * @return Collection<int, Cnss>
     */
    public function getCnsses(): Collection
    {
        return $this->cnsses;
    }

    public function addCnss(Cnss $cnss): static
    {
        if (!$this->cnsses->contains($cnss)) {
            $this->cnsses->add($cnss);
            $cnss->setEmploye($this);
        }

        return $this;
    }

    public function removeCnss(Cnss $cnss): static
    {
        if ($this->cnsses->removeElement($cnss)) {
            // set the owning side to null (unless already changed)
            if ($cnss->getEmploye() === $this) {
                $cnss->setEmploye(null);
            }
        }

        return $this;
    }
}
