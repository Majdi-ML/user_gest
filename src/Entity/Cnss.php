<?php

namespace App\Entity;

use App\Repository\CnssRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: CnssRepository::class)]
#[Vich\Uploadable]
class Cnss
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $trimstre = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant_totale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attached_file = null;

    
    #[Vich\UploadableField(mapping: 'cnss_files', fileNameProperty: 'attached_file')]
    private ?File $cnssFile = null;

    #[ORM\ManyToOne(inversedBy: 'cnsses')]
    private ?Employe $employe = null;

    public function setCnssFile(?File $cnssFile = null): void
    {
        $this->cnssFile = $cnssFile;
    }

    public function getCnssFile(): ?File
    {
        return $this->cnssFile;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrimstre(): ?int
    {
        return $this->trimstre;
    }

    public function setTrimstre(?int $trimstre): static
    {
        $this->trimstre = $trimstre;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMontantTotale(): ?float
    {
        return $this->montant_totale;
    }

    public function setMontantTotale(?float $montant_totale): static
    {
        $this->montant_totale = $montant_totale;

        return $this;
    }

    public function getAttachedFile(): ?string
    {
        return $this->attached_file;
    }

    public function setAttachedFile(?string $attached_file): static
    {
        $this->attached_file = $attached_file;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

   

 
}
