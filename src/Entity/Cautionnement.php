<?php

namespace App\Entity;

use App\Repository\CautionnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CautionnementRepository::class)]
class Cautionnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_paiement = null;

    

    #[ORM\Column(length: 255)]
    private ?string $Mois = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?Personne $Personne = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?Appartement $appartement = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?NaturePaiement $Nature_Paiement = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTimeInterface $date_paiement): static
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }

   

    public function getMois(): ?string
    {
        return $this->Mois;
    }

    public function setMois(string $Mois): static
    {
        $this->Mois = $Mois;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->Personne;
    }

    public function setPersonne(?Personne $Personne): static
    {
        $this->Personne = $Personne;

        return $this;
    }

    public function getAppartement(): ?Appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?Appartement $appartement): static
    {
        $this->appartement = $appartement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNaturePaiement(): ?NaturePaiement
    {
        return $this->Nature_Paiement;
    }

    public function setNaturePaiement(?NaturePaiement $Nature_Paiement): static
    {
        $this->Nature_Paiement = $Nature_Paiement;

        return $this;
    }

}
