<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $nom;

    #[Assert\NotNull]
    #[ORM\Column(type: "integer")]
    private ?int $temps;

    #[Assert\NotNull]
    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt;

    #[Assert\NotNull]
    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $updatedAt;

    #[Assert\NotNull]
    #[ORM\Column(type: "text")]
    private ?string $description;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $prix;

    #[Assert\Range(min: 0, max: 5)]
    #[ORM\Column(type: "integer")]
    private ?int $difficulte;

    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: "recettes")]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->difficulte = 0;
        // Commented as instructed:
        // $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->nom = '';
        $this->temps = 0;
        $this->description = '';
    }

public function getId(): ?int
{
    return $this->id;
}

public function getName(): ?string
{
    return $this->nom;
}

public function setNom(string $nom): self
{
    $this->nom = $nom;
    return $this;
}

public function getTemps(): ?int
{
    return $this->temps;
}

public function setTemps(int $temps): self
{
    $this->temps = $temps;
    return $this;
}

public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->createdAt;
}

public function setCreatedAt(\DateTimeInterface $createdAt): self
{
    $this->createdAt = $createdAt;
    return $this;
}

public function getUpdatedAt(): ?\DateTimeInterface
{
    return $this->updatedAt;
}

public function setUpdatedAt(\DateTimeInterface $updatedAt): self
{
    $this->updatedAt = $updatedAt;
    return $this;
}

public function getDescription(): ?string
{
    return $this->description;
}

public function setDescription(string $description): self
{
    $this->description = $description;
    return $this;
}

public function getPrix(): ?float
{
    return $this->prix;
}

public function setPrix(?float $prix): self
{
    $this->prix = $prix;
    return $this;
}

public function getDifficulte(): ?int
{
    return $this->difficulte;
}

public function setDifficulte(int $difficulte): self
{
    $this->difficulte = $difficulte;
    return $this;
}

#[ORM\PrePersist]
public function setCreatedAtValue(): void
{
    if (!$this->createdAt) {
        $this->createdAt = new \DateTime();
    }
}

/**
 * @return Collection|Ingredients[]
 */
public function getIngredients(): Collection
{
    return $this->ingredients;
}

public function addIngredient(Ingredients $ingredient): self
{
    if (!$this->ingredients->contains($ingredient)) {
        $this->ingredients[] = $ingredient;
    }
    return $this;
}

public function removeIngredient(Ingredients $ingredient): self
{
    $this->ingredients->removeElement($ingredient);
    return $this;
}

}
