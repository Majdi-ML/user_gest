<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
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
    private ?float $montant_cnss = null;

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

    public function getMontantCnss(): ?float
    {
        return $this->montant_cnss;
    }

    public function setMontantCnss(float $montant_cnss): static
    {
        $this->montant_cnss = $montant_cnss;

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
}
