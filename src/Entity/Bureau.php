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

    #[ORM\OneToMany(mappedBy: 'bureau', targetEntity: Cnss::class)]
    private Collection $cnsses;

    #[ORM\ManyToOne(inversedBy: 'bureaus')]
    private ?StatusBureau $status = null;

    #[ORM\ManyToOne(inversedBy: 'bureaus')]
    private ?FonctionBureau $fonction = null;

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
            $cnss->setBureau($this);
        }

        return $this;
    }

    public function removeCnss(Cnss $cnss): static
    {
        if ($this->cnsses->removeElement($cnss)) {
            // set the owning side to null (unless already changed)
            if ($cnss->getBureau() === $this) {
                $cnss->setBureau(null);
            }
        }

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
}
