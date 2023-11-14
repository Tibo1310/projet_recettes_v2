<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PreUpdate;

#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
#[HasLifecycleCallbacks]
class Ingredients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(
        pattern:"/^[a-zA-Z\s]+$/",
        message:"Le nom de l'ingrédient contient un caractère interdit.",
    )]
    
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre nom doit au moins avoir {{ limit }} caractères',
        maxMessage: 'Votre nom ne doit pas dépasser {{ limit }} caractères',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\Length(
        min: 0,
        max: 200,
        minMessage: 'Le prix doit au moins avoir {{ limit }} caractères',
        maxMessage: 'Le prix ne doit pas dépasser {{ limit }} caractères',
    )]
    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, mappedBy: 'ingredients')]
    private Collection $recettes;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;
    
    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[PreUpdate]
    public function updateTimestamp(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addIngredient($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeIngredient($this);
        }

        return $this;
    }
}