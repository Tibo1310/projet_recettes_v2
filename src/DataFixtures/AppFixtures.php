<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredients;
use App\Entity\Recette;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Ajout d'un administrateur
        $admin = new User();
        $admin->setEmail($faker->unique()->email);  // Génération d'un email unique avec faker
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setNom($faker->lastName);  // Génération d'un nom avec faker
        $admin->setPrenom($faker->firstName);  // Génération d'un prénom avec faker
        $admin->setVille($faker->city);  // Génération d'une ville avec faker
        $admin->setCp($faker->postcode);  // Génération d'un code postal avec faker
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $manager->persist($admin);

        // Boucle pour créer 20 utilisateurs
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email);
            $user->setRoles(['ROLE_USER']);
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setVille($faker->city);
            $user->setCp($faker->postcode);
            $user->setPassword($this->hasher->hashPassword($user, '1234'));
            $manager->persist($user);
        }

        // Boucle pour Ingredients
        for ($i = 0; $i < 100; $i++) {
            $ingredient = new Ingredients();
            $ingredient->setName('ingr_' . $faker->unique()->word); // Utilisez unique pour les noms d'ingrédients
            $ingredient->setPrix($faker->randomFloat(2));
            $createdAt = new \DateTimeImmutable();
            $ingredient->setCreatedAt($createdAt);
            $manager->persist($ingredient);
        }

        // Boucle pour Recette
        for ($i = 0; $i < 50; $i++) {
            $recette = new Recette();
            $createdAt = new \DateTimeImmutable();
            $updatedAt = new \DateTimeImmutable();
            $recette->setCreatedAt($createdAt);
            $recette->setUpdatedAt($updatedAt);
            $recette->setNom($faker->sentence(2));
            $recette->setDescription($faker->paragraph);
            $recette->setTemps($faker->numberBetween(10, 120));
            $recette->setDifficulte($faker->numberBetween(1, 5));

            // Ajout d'ingrédients à la recette
            $randomIngredients = $manager->getRepository(Ingredients::class)->findBy([], null, mt_rand(2, 10));
            foreach ($randomIngredients as $ingredient) {
                $recette->addIngredient($ingredient);
            }

            $manager->persist($recette);
        }

        $manager->flush();
    }
}