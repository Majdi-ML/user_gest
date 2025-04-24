<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411110956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA788236FDD6');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA789D86650F');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA786BA58F3E');
        $this->addSql('DROP INDEX IDX_1585FA786BA58F3E ON cautionnement');
        $this->addSql('DROP INDEX IDX_1585FA788236FDD6 ON cautionnement');
        $this->addSql('DROP INDEX IDX_1585FA789D86650F ON cautionnement');
        $this->addSql('ALTER TABLE cautionnement ADD personne_id INT DEFAULT NULL, ADD appartement_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP personne_id_id, DROP appartement_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA78A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA78E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1585FA78A21BD112 ON cautionnement (personne_id)');
        $this->addSql('CREATE INDEX IDX_1585FA78E1729BBA ON cautionnement (appartement_id)');
        $this->addSql('CREATE INDEX IDX_1585FA78A76ED395 ON cautionnement (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA78A21BD112');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA78E1729BBA');
        $this->addSql('ALTER TABLE cautionnement DROP FOREIGN KEY FK_1585FA78A76ED395');
        $this->addSql('DROP INDEX IDX_1585FA78A21BD112 ON cautionnement');
        $this->addSql('DROP INDEX IDX_1585FA78E1729BBA ON cautionnement');
        $this->addSql('DROP INDEX IDX_1585FA78A76ED395 ON cautionnement');
        $this->addSql('ALTER TABLE cautionnement ADD personne_id_id INT DEFAULT NULL, ADD appartement_id_id INT DEFAULT NULL, ADD user_id_id INT DEFAULT NULL, DROP personne_id, DROP appartement_id, DROP user_id');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA788236FDD6 FOREIGN KEY (appartement_id_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA789D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cautionnement ADD CONSTRAINT FK_1585FA786BA58F3E FOREIGN KEY (personne_id_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_1585FA786BA58F3E ON cautionnement (personne_id_id)');
        $this->addSql('CREATE INDEX IDX_1585FA788236FDD6 ON cautionnement (appartement_id_id)');
        $this->addSql('CREATE INDEX IDX_1585FA789D86650F ON cautionnement (user_id_id)');
    }
}
