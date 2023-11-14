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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Psr\Log\LoggerInterface;

class IngredientController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findAll();

        $this->logger->info('AFFICHAGE DE TOUS LES INGREDIENTS');

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient_greater_than_100', name: 'ingredient.greater_than_100')]
    public function greater_than_100(IngredientsRepository $repository, EntityManagerInterface $entityManager): Response //, EntityManagerInterface $entityManager
    {
        $query = $entityManager->createQuery(
            'SELECT ing
            FROM App\Entity\Ingredients ing
            WHERE ing.prix > 100'
        );

        $ingredients = $query->getResult();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient_greater_than_100_v2', name: 'ingredient.greater_than_100_v2')]
    public function greater_than_100_v2(IngredientsRepository $repository, EntityManagerInterface $entityManager): Response //, EntityManagerInterface $entityManager
    {

        $ingredients = $repository->findAllGreaterThanPrice();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/create', name: 'ingredient.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $ingredient = new Ingredients();
        $crea_form = $this->createForm(IngredientType::class, $ingredient);

        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            // Get the data from the form
            $ingredient = $crea_form->getData();

            // Set the creation date
            $ingredient->setCreatedAt(new \DateTime());

            // Persist and flush to save to the database
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->logger->info('CREATION D\'UN NOUVEL INGREDIENT');

            // Redirect to the ingredient list page
            return $this->redirectToRoute('app_ingredient');
        }

        // If the form is not valid, or it's not submitted, render the create form template
        return $this->render('ingredient/create.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }


    #[Route('/ingredient/store', name: 'ingredient.store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Create a form instance based on your form type (assuming you have one)
        $crea_form = $this->createForm(IngredientType::class);

        // Handle the form submission
        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            // Get the data from the form
            $ingredient = $crea_form->getData();

            // Set the creation date
            $ingredient->setCreatedAt(new \DateTime());

            // Persist and flush to save to the database
            $entityManager->persist($ingredient);
            $entityManager->flush();

            // Redirect to the ingredient list page
            return $this->redirectToRoute('app_ingredient');
        }

        // If the form is not valid, or it's not submitted, render the create form template
        return $this->render('ingredient/create.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }




    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Charger l'objet Ingredients correspondant à l'ID
        $ingredient = $entityManager->getRepository(Ingredients::class)->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException('L\'ingrédient n\'existe pas');
        }

        // Créer le formulaire en utilisant l'objet Ingredients chargé
        $editForm = $this->createForm(IngredientType::class, $ingredient);

        // Handle the form submission
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Persist and flush to save the changes to the database
            $entityManager->persist($ingredient);
            $entityManager->flush();

            // Redirect to the ingredient list page
            return $this->redirectToRoute('app_ingredient');
        }

        // Render the edit form template
        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'edit_form' => $editForm->createView(),
        ]);
    }


    // Gérer la soumission du formulaire de mise à jour
    #[Route('/ingredient/update/{id}', name: 'ingredient.update', methods: ['POST'])]
    public function update(Request $request, Ingredients $ingredient, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formData = $request->request->all();

        // Mettez à jour les informations de l'ingrédient
        $ingredient->setName($formData['name']);
        $ingredient->setPrix($formData['prix']);

        // Enregistrez les modifications dans la base de données
        $entityManager->update();
        $entityManager->flush();

        return $this->redirectToRoute('app_ingredient');
    }

    #[Route('/ingredient/delete/{id}', name: 'ingredient.delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Charger l'objet Ingredients correspondant à l'ID
        $ingredient = $entityManager->getRepository(Ingredients::class)->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException('L\'ingrédient n\'existe pas');
        }

        // Supprimez l'ingrédient de la base de données
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('app_ingredient');
    }

    // tomate
    #[Route('/ingredient/tomate', name: 'app_ingredient_tomate')]
    public function index_ingredient_tomate(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByName('tomate');

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    // tomate avec un prix minimum de 5
    #[Route('/ingredient/tomate_5', name: 'app_ingredient_tomate_5')]
    public function index_ingredient_tomate_5(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameAndPrice('tomate', 5);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    // ingredients commençant par tom
    #[Route('/ingredient/tom', name: 'app_ingredient_tom')]
    public function index_ingredient_tom(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameStartingWithTom();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    // prix de l'URL
    #[Route('/ingredient/by_price/{price}', name: 'app_ingredient_by_price')]
    public function index_ingredient_by_price(IngredientsRepository $repository, $price): Response
    {
        $ingredients = $repository->findIngredientsByPrice($price);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    // prix et nom de l'url
    #[Route('/ingredient/by_price/{price}/by_name/{name}', name: 'app_ingredient_by_price_and_name')]
    public function index_ingredient_by_price_and_name(IngredientsRepository $repository, $price, $name): Response
    {
        $ingredients = $repository->findIngredientsByPriceAndName($price, $name);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //find all avec sql
    #[Route('/ingredient/sql', name: 'app_ingredient_sql')]
    public function index_ingredient_sql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findAll_sql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/tomate_sql', name: 'app_ingredient_tomate_sql')]
    public function index_ingredient_tomate_sql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameTomate_sql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/tomate_5_sql', name: 'app_ingredient_tomate_5_sql')]
    public function index_ingredient_tomate_5_sql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameTomateAndPrice_sql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/tom_sql', name: 'app_ingredient_tom_sql')]
    public function index_ingredient_tom_sql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameStartingWithTom_sql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/by_price_sql/{price}', name: 'app_ingredient_by_price_sql')]
    public function index_ingredient_by_price_sql(IngredientsRepository $repository, $price): Response
    {
        $ingredients = $repository->findIngredientsByPrice_sql($price);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/by_price_and_name_sql/{price}/{name}', name: 'app_ingredient_by_price_and_name_sql')]
    public function index_ingredient_by_price_and_name_sql(IngredientsRepository $repository, $price, $name): Response
    {
        $ingredients = $repository->findIngredientsByPriceAndName_sql($price, $name);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //DQL
    #[Route('/ingredient_dql', name: 'app_ingredient_dql')]
    public function index_dql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findAll_dql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //prix et name
    #[Route('/ingredient_by_price_and_name_dql/{price}/{name}', name: 'app_ingredient_by_price_and_name_dql')]
    public function index_ingredient_by_price_and_name_dql(IngredientsRepository $repository, $price, $name): Response
    {
        $ingredients = $repository->findIngredientsByPriceAndNameDql($price, $name);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //prix
    #[Route('/ingredient_by_price_dql/{price}', name: 'app_ingredient_by_price_dql')]
    public function index_ingredient_by_price_dql(IngredientsRepository $repository, $price): Response
    {
        $ingredients = $repository->findIngredientsByPriceDql($price);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }


    //name
    #[Route('/ingredient_tom_dql', name: 'app_ingredient_tom_dql')]
    public function index_ingredient_tom_dql(IngredientsRepository $repository): Response
    {
        $ingredients = $repository->findIngredientsByNameStartingWithTomDql();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
    
}