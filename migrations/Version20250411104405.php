<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411104405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INT AUTO_INCREMENT NOT NULL, bloc_id_id INT DEFAULT NULL, locataire_id INT DEFAULT NULL, proprietaire_id INT DEFAULT NULL, numero VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, etage VARCHAR(255) NOT NULL, est_habite TINYINT(1) NOT NULL, INDEX IDX_71A6BD8D7BE4F98C (bloc_id_id), INDEX IDX_71A6BD8DD8A38199 (locataire_id), INDEX IDX_71A6BD8D76C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloc (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_etages INT NOT NULL, nombre_appartement_etage INT NOT NULL, nombre_totale_appartement INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caisse (id INT AUTO_INCREMENT NOT NULL, solde DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cautionnement (id INT AUTO_INCREMENT NOT NULL, personne_id_id INT DEFAULT NULL, appartement_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, INDEX IDX_1585FA786BA58F3E (personne_id_id), INDEX IDX_1585FA788236FDD6 (appartement_id_id), INDEX IDX_1585FA789D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_depense DATE NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_340597579D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone INT NOT NULL, adresse VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, telephone INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D7BE4F98C FOREIGN KEY (bloc_id_id) REFERENCES bloc (id)');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8DD8A38199 FOREIGN KEY (locataire_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA786BA58F3E FOREIGN KEY (personne_id_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA788236FDD6 FOREIGN KEY (appartement_id_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA789D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_340597579D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D7BE4F98C');
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8DD8A38199');
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D76C50E4A');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA786BA58F3E');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA788236FDD6');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA789D86650F');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_340597579D86650F');
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE bloc');
        $this->addSql('DROP TABLE caisse');
        $this->addSql('DROP TABLE cautionnement');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
