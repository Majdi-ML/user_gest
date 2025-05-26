<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519154329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__employe AS SELECT id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin FROM employe');
        $this->addSql('DROP TABLE employe');
        $this->addSql('CREATE TABLE employe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fonction_employe_id INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, cin INTEGER NOT NULL, salaire DOUBLE PRECISION DEFAULT NULL, montant_cnss DOUBLE PRECISION NOT NULL, couverture_sociale BOOLEAN DEFAULT NULL, telephone INTEGER DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, CONSTRAINT FK_F804D3B95095CE48 FOREIGN KEY (fonction_employe_id) REFERENCES fonction_employe (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO employe (id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin) SELECT id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin FROM __temp__employe');
        $this->addSql('DROP TABLE __temp__employe');
        $this->addSql('CREATE INDEX IDX_F804D3B95095CE48 ON employe (fonction_employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__employe AS SELECT id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin FROM employe');
        $this->addSql('DROP TABLE employe');
        $this->addSql('CREATE TABLE employe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fonction_employe_id INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, cin INTEGER NOT NULL, salaire DOUBLE PRECISION DEFAULT NULL, montant_cnss DOUBLE PRECISION NOT NULL, couverture_sociale BOOLEAN DEFAULT NULL, telephone INTEGER DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, CONSTRAINT FK_F804D3B95095CE48 FOREIGN KEY (fonction_employe_id) REFERENCES fonction_employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO employe (id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin) SELECT id, fonction_employe_id, nom, prenom, cin, salaire, montant_cnss, couverture_sociale, telephone, date_debut, date_fin FROM __temp__employe');
        $this->addSql('DROP TABLE __temp__employe');
        $this->addSql('CREATE INDEX IDX_F804D3B95095CE48 ON employe (fonction_employe_id)');
    }
}
