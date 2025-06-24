<?php

namespace App\Entity;

use App\Repository\FraisSyndicReglementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FraisSyndicReglementRepository::class)]
class FraisSyndicReglement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fraisSyndicReglements')]
    private ?FraisSyndic $frais = null;

    #[ORM\ManyToOne(inversedBy: 'fraisSyndicReglements')]
    private ?Appartement $appartement = null;

    #[ORM\ManyToOne(inversedBy: 'fraisSyndicReglements')]
    private ?Personne $Personne = null;

    #[ORM\ManyToOne(inversedBy: 'fraisSyndicReglements')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'fraisSyndicReglements')]
    private ?NaturePaiement $nature_paiement = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Janvier = null;

    #[ORM\Column(nullable: true)]
    private ?bool $fevrier = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mars = null;

    #[ORM\Column(nullable: true)]
    private ?bool $avril = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mai = null;

    #[ORM\Column(nullable: true)]
    private ?bool $juin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $juillet = null;

    #[ORM\Column(nullable: true)]
    private ?bool $aout = null;

    #[ORM\Column(nullable: true)]
    private ?bool $septembre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $octobre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $novembre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $decembre = null;

    #[ORM\Column(nullable: true)]
    private ?float $totale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrais(): ?FraisSyndic
    {
        return $this->frais;
    }

    public function setFrais(?FraisSyndic $frais): static
    {
        $this->frais = $frais;

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

    public function getPersonne(): ?Personne
    {
        return $this->Personne;
    }

    public function setPersonne(?Personne $Personne): static
    {
        $this->Personne = $Personne;

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
        return $this->nature_paiement;
    }

    public function setNaturePaiement(?NaturePaiement $nature_paiement): static
    {
        $this->nature_paiement = $nature_paiement;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function isJanvier(): ?bool
    {
        return $this->Janvier;
    }

    public function setJanvier(?bool $Janvier): static
    {
        $this->Janvier = $Janvier;

        return $this;
    }

    public function isFevrier(): ?bool
    {
        return $this->fevrier;
    }

    public function setFevrier(?bool $fevrier): static
    {
        $this->fevrier = $fevrier;

        return $this;
    }

    public function isMars(): ?bool
    {
        return $this->mars;
    }

    public function setMars(?bool $mars): static
    {
        $this->mars = $mars;

        return $this;
    }

    public function isAvril(): ?bool
    {
        return $this->avril;
    }

    public function setAvril(?bool $avril): static
    {
        $this->avril = $avril;

        return $this;
    }

    public function isMai(): ?bool
    {
        return $this->mai;
    }

    public function setMai(?bool $mai): static
    {
        $this->mai = $mai;

        return $this;
    }

    public function isJuin(): ?bool
    {
        return $this->juin;
    }

    public function setJuin(?bool $juin): static
    {
        $this->juin = $juin;

        return $this;
    }

    public function isJuillet(): ?bool
    {
        return $this->juillet;
    }

    public function setJuillet(?bool $juillet): static
    {
        $this->juillet = $juillet;

        return $this;
    }

    public function isAout(): ?bool
    {
        return $this->aout;
    }

    public function setAout(?bool $aout): static
    {
        $this->aout = $aout;

        return $this;
    }

    public function isSeptembre(): ?bool
    {
        return $this->septembre;
    }

    public function setSeptembre(?bool $septembre): static
    {
        $this->septembre = $septembre;

        return $this;
    }

    public function isOctobre(): ?bool
    {
        return $this->octobre;
    }

    public function setOctobre(?bool $octobre): static
    {
        $this->octobre = $octobre;

        return $this;
    }

    public function isNovembre(): ?bool
    {
        return $this->novembre;
    }

    public function setNovembre(?bool $novembre): static
    {
        $this->novembre = $novembre;

        return $this;
    }

    public function isDecembre(): ?bool
    {
        return $this->decembre;
    }

    public function setDecembre(?bool $decembre): static
    {
        $this->decembre = $decembre;

        return $this;
    }

    public function getTotale(): ?float
    {
        return $this->totale;
    }

    public function setTotale(?float $totale): static
    {
        $this->totale = $totale;

        return $this;
    }
}
