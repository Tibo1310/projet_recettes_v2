<?php

namespace App\EventListener;

use App\Entity\Ingredients;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(entity: Ingredients::class)]

class IngredientListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $ingredient = $args->getEntity();

        // Check if the entity is an instance of Ingredients
        if (!$ingredient instanceof Ingredients) {
            return;
        }

        $ingredient->setSlug($this->slugger->slug($ingredient->getName())->lower());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $ingredient = $args->getEntity();

        // Check if the entity is an instance of Ingredients
        if (!$ingredient instanceof Ingredients) {
            return;
        }

        $ingredient->setSlug($this->slugger->slug($ingredient->getName())->lower());
        // $ingredient->setUpdatedAt(new \DateTime()); pas obligatoire, fait dans l'entité
    }

}

