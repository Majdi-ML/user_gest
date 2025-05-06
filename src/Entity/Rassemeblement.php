<?php

namespace App\Entity;

use App\Repository\RassemeblementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: RassemeblementRepository::class)]
#[Vich\Uploadable]
class Rassemeblement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rassemeblements')]
    private ?TypeRassmblement $Type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pv = null;

    #[Vich\UploadableField(mapping: 'rassemblement_files', fileNameProperty: 'Pv')]
    private ?File $pvFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeRassmblement
    {
        return $this->Type;
    }

    public function setType(?TypeRassmblement $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPv(): ?string
    {
        return $this->Pv;
    }

    public function setPv(?string $Pv): static
    {
        $this->Pv = $Pv;

        return $this;
    }
    public function setPvFile(?File $pvFile = null): void
    {
        $this->pvFile = $pvFile;
    }

    public function getPvFile(): ?File
    {
        return $this->pvFile;
    }
}
