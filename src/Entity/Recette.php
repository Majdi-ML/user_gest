<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[Vich\Uploadable]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_recette = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type_recette = null;

    #[ORM\Column(length: 255)]
    private ?string $nature_recette = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Attached_file = null;

    #[Vich\UploadableField(mapping: 'recette_files', fileNameProperty: 'Attached_file')]
    private ?File $file = null;

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

    public function getDateRecette(): ?\DateTimeInterface
    {
        return $this->date_recette;
    }

    public function setDateRecette(\DateTimeInterface $date_recette): static
    {
        $this->date_recette = $date_recette;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTypeRecette(): ?string
    {
        return $this->type_recette;
    }

    public function setTypeRecette(string $type_recette): static
    {
        $this->type_recette = $type_recette;
        return $this;
    }

    public function getNatureRecette(): ?string
    {
        return $this->nature_recette;
    }

    public function setNatureRecette(string $nature_recette): static
    {
        $this->nature_recette = $nature_recette;
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

    public function getAttachedFile(): ?string
    {
        return $this->Attached_file;
    }

    public function setAttachedFile(?string $Attached_file): static
    {
        $this->Attached_file = $Attached_file;
        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;
    }
}

