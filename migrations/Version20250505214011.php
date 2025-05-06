<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505214011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locataire_id INTEGER DEFAULT NULL, bloc_id INTEGER DEFAULT NULL, proprietaire_id INTEGER DEFAULT NULL, numero VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, etage VARCHAR(255) NOT NULL, est_habite BOOLEAN NOT NULL, CONSTRAINT FK_71A6BD8DD8A38199 FOREIGN KEY (locataire_id) REFERENCES personne (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_71A6BD8D5582E9C0 FOREIGN KEY (bloc_id) REFERENCES bloc (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_71A6BD8D76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES personne (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_71A6BD8DD8A38199 ON appartement (locataire_id)');
        $this->addSql('CREATE INDEX IDX_71A6BD8D5582E9C0 ON appartement (bloc_id)');
        $this->addSql('CREATE INDEX IDX_71A6BD8D76C50E4A ON appartement (proprietaire_id)');
        $this->addSql('CREATE TABLE bloc (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_etages INTEGER NOT NULL, nombre_appartement_etage INTEGER NOT NULL, nombre_totale_appartement INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE bureau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status_id INTEGER DEFAULT NULL, fonction_id INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, telephone INTEGER DEFAULT NULL, CONSTRAINT FK_166FDEC46BF700BD FOREIGN KEY (status_id) REFERENCES status_bureau (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_166FDEC457889920 FOREIGN KEY (fonction_id) REFERENCES fonction_bureau (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_166FDEC46BF700BD ON bureau (status_id)');
        $this->addSql('CREATE INDEX IDX_166FDEC457889920 ON bureau (fonction_id)');
        $this->addSql('CREATE TABLE caisse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, solde DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE cautionnement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, personne_id INTEGER DEFAULT NULL, appartement_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, nature_paiement_id INTEGER DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, mois VARCHAR(255) NOT NULL, CONSTRAINT FK_1585FA78A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1585FA78E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1585FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1585FA7844CB3C2C FOREIGN KEY (nature_paiement_id) REFERENCES nature_paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1585FA78A21BD112 ON cautionnement (personne_id)');
        $this->addSql('CREATE INDEX IDX_1585FA78E1729BBA ON cautionnement (appartement_id)');
        $this->addSql('CREATE INDEX IDX_1585FA78A76ED395 ON cautionnement (user_id)');
        $this->addSql('CREATE INDEX IDX_1585FA7844CB3C2C ON cautionnement (nature_paiement_id)');
        $this->addSql('CREATE TABLE cnss (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bureau_id INTEGER DEFAULT NULL, trimstre INTEGER DEFAULT NULL, annee INTEGER NOT NULL, montant_totale DOUBLE PRECISION DEFAULT NULL, attached_file VARCHAR(255) NOT NULL, CONSTRAINT FK_87806B6832516FE2 FOREIGN KEY (bureau_id) REFERENCES bureau (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_87806B6832516FE2 ON cnss (bureau_id)');
        $this->addSql('CREATE TABLE depense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_depense DATE NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, attached_file VARCHAR(255) NOT NULL, CONSTRAINT FK_34059757A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_34059757A76ED395 ON depense (user_id)');
        $this->addSql('CREATE TABLE employe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, cin INTEGER NOT NULL, salaire DOUBLE PRECISION DEFAULT NULL, montant_cnss DOUBLE PRECISION NOT NULL, couverture_sociale BOOLEAN DEFAULT NULL, telephone INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE fonction_bureau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE nature_paiement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nature VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE papier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_papier_id INTEGER DEFAULT NULL, attached_file VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_creation DATE NOT NULL, CONSTRAINT FK_940A2D5E745A34FF FOREIGN KEY (type_papier_id) REFERENCES type_papier (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_940A2D5E745A34FF ON papier (type_papier_id)');
        $this->addSql('CREATE TABLE personne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone INTEGER NOT NULL, adresse VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, date_location DATE DEFAULT NULL, date_achat DATE NOT NULL)');
        $this->addSql('CREATE TABLE rassemeblement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER DEFAULT NULL, date DATE DEFAULT NULL, pv VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_26A9D2D3C54C8C93 FOREIGN KEY (type_id) REFERENCES type_rassmblement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_26A9D2D3C54C8C93 ON rassemeblement (type_id)');
        $this->addSql('CREATE TABLE status_bureau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE type_papier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE type_rassmblement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, telephone INTEGER DEFAULT NULL, is_valid BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE bloc');
        $this->addSql('DROP TABLE bureau');
        $this->addSql('DROP TABLE caisse');
        $this->addSql('DROP TABLE cautionnement');
        $this->addSql('DROP TABLE cnss');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE fonction_bureau');
        $this->addSql('DROP TABLE nature_paiement');
        $this->addSql('DROP TABLE papier');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE rassemeblement');
        $this->addSql('DROP TABLE status_bureau');
        $this->addSql('DROP TABLE type_papier');
        $this->addSql('DROP TABLE type_rassmblement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
