<?php
// src/Repository/IngredientsRepository.php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Ingredients;
use Psr\Log\LoggerInterface;

class IngredientsRepository extends ServiceEntityRepository
{
    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Ingredients::class);
        $this->logger = $logger;
    }

    public function findIngredientsByNameAndPrice($name, $price)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name = :name')
            ->andWhere('i.prix >= :price')
            ->setParameter('name', $name)
            ->setParameter('price', $price)
            ->getQuery()
            ->getResult();
    }


    public function findAllGreaterThanPrice(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT ing
            FROM App\Entity\Ingredients ing
            WHERE ing.prix > 100');

        // returns an array of Ingredients objects
        return $query->getResult();
    }

    // ingredients commençant par tom
    public function findIngredientsByNameStartingWithTom()
    {
        // Utilisation du logger
        $this->logger->info('APPEL de findIngredientByNameStartingWithTom');
        $this->logger->error('ERREUR DANS find_ingredient_tom');
        
        return $this->createQueryBuilder('i')
            ->andWhere('i.name LIKE :prefix')
            ->setParameter('prefix', 'tom%')
            ->getQuery()
            ->getResult();
    }
    // ingredients prix = url
    public function findIngredientsByPrice($price)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.prix = :price')
            ->setParameter('price', $price)
            ->getQuery()
            ->getResult();
    }

    // ingredients prix et nom = url

    public function findIngredientsByPriceAndName($price, $name)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.prix = :price')
            ->andWhere('i.name = :name')
            ->setParameter('price', $price)
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

//SQL
// Trouver tous les ingrédients
public function findAll_sql(): array
{
    $entityManager = $this->getEntityManager();
    $conn = $entityManager->getConnection();

    $sql = '
        SELECT id, name, prix, created_at as createdAt
        FROM ingredients
        ORDER BY id ASC
    ';

    $resultSet = $conn->executeQuery($sql);

    // Retourne un tableau associatif des résultats
    return $resultSet->fetchAllAssociative();
}


    // Trouver les ingrédients avec le nom "tomate" en utilisant SQL
    public function findIngredientsByNameTomate_sql(): array
    {
        $entityManager = $this->getEntityManager();
        $conn = $entityManager->getConnection();
    
        $sql = '
            SELECT id, name, prix, created_at as createdAt
            FROM ingredients
            WHERE name = :name
        ';
    
        $resultSet = $conn->executeQuery($sql, ['name' => 'tomate']);
    
        // Retourne un tableau associatif des résultats
        return $resultSet->fetchAllAssociative();
    }
    
    // Trouver les ingrédients avec le nom "tomate" et un prix minimum de 5 en utilisant SQL
    public function findIngredientsByNameTomateAndPrice_sql(): array
    {
        $entityManager = $this->getEntityManager();
        $conn = $entityManager->getConnection();
    
        $sql = '
            SELECT id, name, prix, created_at as createdAt
            FROM ingredients
            WHERE name = :name AND prix >= :price
        ';
    
        $resultSet = $conn->executeQuery($sql, ['name' => 'tomate', 'price' => 5]);
    
        // Retourne un tableau associatif des résultats
        return $resultSet->fetchAllAssociative();
    }
    
    // Trouver les ingrédients dont le nom commence par "tom" en utilisant SQL
    public function findIngredientsByNameStartingWithTom_sql(): array
    {
        $entityManager = $this->getEntityManager();
        $conn = $entityManager->getConnection();
    
        $sql = '
            SELECT id, name, prix, created_at as createdAt
            FROM ingredients
            WHERE name LIKE :prefix
        ';
    
        $resultSet = $conn->executeQuery($sql, ['prefix' => 'tom%']);
    
        // Retourne un tableau associatif des résultats
        return $resultSet->fetchAllAssociative();
    }
    
    // Trouver les ingrédients avec un prix spécifique en utilisant SQL
    public function findIngredientsByPrice_sql($price): array
    {
        $entityManager = $this->getEntityManager();
        $conn = $entityManager->getConnection();
    
        $sql = '
            SELECT id, name, prix, created_at as createdAt
            FROM ingredients
            WHERE prix = :price
        ';
    
        $resultSet = $conn->executeQuery($sql, ['price' => $price]);
    
        // Retourne un tableau associatif des résultats
        return $resultSet->fetchAllAssociative();
    }
    
    // Trouver les ingrédients avec un prix spécifique et un nom spécifique en utilisant SQL
    public function findIngredientsByPriceAndName_sql($price, $name): array
    {
        $entityManager = $this->getEntityManager();
        $conn = $entityManager->getConnection();
    
        $sql = '
            SELECT id, name, prix, created_at as createdAt
            FROM ingredients
            WHERE prix = :price AND name = :name
        ';
    
        $resultSet = $conn->executeQuery($sql, ['price' => $price, 'name' => $name]);
    
        // Retourne un tableau associatif des résultats
        return $resultSet->fetchAllAssociative();
    }

    // DQL
    public function findAll_dql(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT i
            FROM App\Entity\Ingredients i'
        );

        // Execute the query and return an array of Ingredients objects
        return $query->getResult();
    }

public function findIngredientsByNameTomateAndPriceDql(): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT i
        FROM App\Entity\Ingredients i
        WHERE i.name = :name AND i.prix >= :price'
    )->setParameter('name', 'tomate')
     ->setParameter('price', 5);

    // Exécute la requête et retourne un tableau d'objets Ingredients
    return $query->getResult();
}

public function findIngredientsByNameStartingWithTomDql(): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT i
        FROM App\Entity\Ingredients i
        WHERE i.name LIKE :prefix'
    )->setParameter('prefix', 'tom%');

    // Exécute la requête et retourne un tableau d'objets Ingredients
    return $query->getResult();
}

public function findIngredientsByPriceDql($price): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT i
        FROM App\Entity\Ingredients i
        WHERE i.prix = :price'
    )->setParameter('price', $price);

    // Exécute la requête et retourne un tableau d'objets Ingredients
    return $query->getResult();
}

public function findIngredientsByPriceAndNameDql($price, $name): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT i
        FROM App\Entity\Ingredients i
        WHERE i.prix = :price AND i.name = :name'
    )->setParameter('price', $price)
     ->setParameter('name', $name);

    // Exécute la requête et retourne un tableau d'objets Ingredients
    return $query->getResult();
}




}
