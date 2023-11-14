<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecetteType;
use App\Entity\Recette;


class RecetteController extends AbstractController
{
    /////////////////////////////////////////////////////////
    // RECETTES :

    // print 5 recettes dont leurs ingrédients
    #[Route('/recette/ingredient', name: 'recette.ingredient')]
    public function listFirstTenRecettesWithIngredients(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT r, i
         FROM App\Entity\Recette r
         JOIN r.ingredients i
         ORDER BY r.id ASC'
        )->setMaxResults(10);

        $recettes = $query->getResult();

        return $this->render('recette/ingredient.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/avec_5_ingredients', name: 'recette.avec_5_ingredients')]
    public function listRecettesWithFiveIngredients(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT r
         FROM App\Entity\Recette r
         WHERE SIZE(r.ingredients) = 5'
        );

        $recettes = $query->getResult();

        return $this->render('recette/avec_5_ingredients.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette', name: 'recette_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT r, i
        FROM App\Entity\Recette r
        JOIN r.ingredients i
        ORDER BY r.id ASC'
        );

        $recettes = $query->getResult();

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/new', name: 'recette_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('recette_index');
        }

        return $this->render('recette/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
