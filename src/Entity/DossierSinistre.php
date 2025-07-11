<?php

namespace App\Entity;

use App\Repository\DossierSinistreRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: DossierSinistreRepository::class)]
#[Vich\Uploadable]
class DossierSinistre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroPolice = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $idDossier = null;

    /**
     * @var Collection<int, Reclamation>
     */
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'dossierSinistre', orphanRemoval: true)]
    private Collection $reclamations;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $constatImageName = null;

    #[Vich\UploadableField(mapping: 'constat_images', fileNameProperty: 'constatImageName')]
    private ?File $constatImageFile = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $carImageNames = [];

    private ?array $carImages = null;
    public function getCarImages(): ?array
    {
        return $this->carImages;
    }

    public function setCarImages(?array $carImages): self
    {
        $this->carImages = $carImages;
        return $this;
    }

    // This field won't be persisted, just for upload handling
    #[Vich\UploadableField(mapping: 'car_images', fileNameProperty: 'carImageNames')]
    private ?File $carImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }






    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getNumeroPolice(): ?string
    {
        return $this->numeroPolice;
    }

    public function setNumeroPolice(string $numeroPolice): static
    {
        $this->numeroPolice = $numeroPolice;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdDossier(): ?string
    {
        return $this->idDossier;
    }

    public function setIdDossier(string $idDossier): static
    {
        $this->idDossier = $idDossier;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setDossierSinistre($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getDossierSinistre() === $this) {
                $reclamation->setDossierSinistre(null);
            }
        }

        return $this;
    }



    public function setConstatImageFile(?File $constatImageFile = null): void
    {
        $this->constatImageFile = $constatImageFile;

        if (null !== $constatImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getConstatImageFile(): ?File
    {
        return $this->constatImageFile;
    }

    public function setConstatImageName(?string $constatImageName): void
    {
        $this->constatImageName = $constatImageName;
    }

    public function getConstatImageName(): ?string
    {
        return $this->constatImageName;
    }

    /* CAR IMAGES HANDLING */
    public function getCarImageNames(): array
    {
        return $this->carImageNames ?? [];
    }

    public function setCarImageNames(array $carImageNames): self
    {
        $this->carImageNames = $carImageNames;
        return $this;
    }

    public function addCarImageName(string $imageName): self
    {
        if (!in_array($imageName, $this->carImageNames, true)) {
            $this->carImageNames[] = $imageName;
        }
        return $this;
    }

    public function setCarImageFile(?File $carImageFile = null): void
    {
        $this->carImageFile = $carImageFile;

        if (null !== $carImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCarImageFile(): ?File
    {
        return $this->carImageFile;
    }


}
