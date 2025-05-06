<?php

namespace App\Entity;

use App\Repository\PapierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: PapierRepository::class)]
#[Vich\Uploadable]
class Papier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Attached_file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\ManyToOne(inversedBy: 'Papiers')]
    private ?TypePapier $typePapier = null;

    #[Vich\UploadableField(mapping: 'papier_files', fileNameProperty: 'Attached_file')]
    private ?File $documentFile = null;

    public function setDocumentFile(?File $documentFile = null): void
    {
        $this->documentFile = $documentFile;
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getTypePapier(): ?TypePapier
    {
        return $this->typePapier;
    }

    public function setTypePapier(?TypePapier $typePapier): static
    {
        $this->typePapier = $typePapier;

        return $this;
    }
}
