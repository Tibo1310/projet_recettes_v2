<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027111806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, temps INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, difficulte INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_ingredients (recette_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_B413140689312FE9 (recette_id), INDEX IDX_B41314063EC4DCE (ingredients_id), PRIMARY KEY(recette_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B413140689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B41314063EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients ADD updated_at DATETIME DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B60114F989D9B62 ON ingredients (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B413140689312FE9');
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B41314063EC4DCE');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE recette_ingredients');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_4B60114F989D9B62 ON ingredients');
        $this->addSql('ALTER TABLE ingredients DROP updated_at, DROP slug');
    }
}
