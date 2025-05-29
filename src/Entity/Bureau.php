<?php

namespace App\Entity;

use App\Repository\BureauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BureauRepository::class)]
class Bureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?int $telephone = null;

    

    #[ORM\ManyToOne(inversedBy: 'bureaus')]
    private ?StatusBureau $status = null;

    #[ORM\ManyToOne(inversedBy: 'bureaus')]
    private ?FonctionBureau $fonction = null;

    #[ORM\Column(length: 4)]
    private ?string $annee_debut = null;

    #[ORM\Column(length: 4)]
    private ?string $annee_fin = null;

    public function __construct()
    {
       
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
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

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


    public function getStatus(): ?StatusBureau
    {
        return $this->status;
    }

    public function setStatus(?StatusBureau $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFonction(): ?FonctionBureau
    {
        return $this->fonction;
    }

    public function setFonction(?FonctionBureau $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getAnneeDebut(): ?string
    {
        return $this->annee_debut;
    }

    public function setAnneeDebut(string $annee_debut): static
    {
        $this->annee_debut = $annee_debut;

        return $this;
    }

    public function getAnneeFin(): ?string
    {
        return $this->annee_fin;
    }

    public function setAnneeFin(string $annee_fin): static
    {
        $this->annee_fin = $annee_fin;

        return $this;
    }
}
