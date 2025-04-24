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

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?Personne $Personne_id = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnements')]
    private ?Appartement $appartement_id = null;

    #[ORM\ManyToOne(inversedBy: 'cautionnemets')]
    private ?User $user_id = null;



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

    public function getPersonneId(): ?Personne
    {
        return $this->Personne_id;
    }

    public function setPersonneId(?Personne $Personne_id): static
    {
        $this->Personne_id = $Personne_id;

        return $this;
    }

    public function getAppartementId(): ?Appartement
    {
        return $this->appartement_id;
    }

    public function setAppartementId(?Appartement $appartement_id): static
    {
        $this->appartement_id = $appartement_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

}
