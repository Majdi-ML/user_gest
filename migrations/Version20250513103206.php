<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513103206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__personne AS SELECT id, nom, prenom, email, telephone, adresse, status, date_location, date_achat FROM personne');
        $this->addSql('DROP TABLE personne');
        $this->addSql('CREATE TABLE personne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone INTEGER NOT NULL, adresse VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_location DATE DEFAULT NULL, date_achat DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO personne (id, nom, prenom, email, telephone, adresse, status, date_location, date_achat) SELECT id, nom, prenom, email, telephone, adresse, status, date_location, date_achat FROM __temp__personne');
        $this->addSql('DROP TABLE __temp__personne');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__personne AS SELECT id, nom, prenom, email, telephone, adresse, status, date_location, date_achat FROM personne');
        $this->addSql('DROP TABLE personne');
        $this->addSql('CREATE TABLE personne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone INTEGER NOT NULL, adresse VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_location DATE DEFAULT NULL, date_achat DATE NOT NULL)');
        $this->addSql('INSERT INTO personne (id, nom, prenom, email, telephone, adresse, status, date_location, date_achat) SELECT id, nom, prenom, email, telephone, adresse, status, date_location, date_achat FROM __temp__personne');
        $this->addSql('DROP TABLE __temp__personne');
    }
}
