<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411111646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D7BE4F98C');
        $this->addSql('DROP INDEX IDX_71A6BD8D7BE4F98C ON appartement');
        $this->addSql('ALTER TABLE appartement CHANGE bloc_id_id bloc_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D5582E9C0 FOREIGN KEY (bloc_id) REFERENCES bloc (id)');
        $this->addSql('CREATE INDEX IDX_71A6BD8D5582E9C0 ON appartement (bloc_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D5582E9C0');
        $this->addSql('DROP INDEX IDX_71A6BD8D5582E9C0 ON appartement');
        $this->addSql('ALTER TABLE appartement CHANGE bloc_id bloc_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D7BE4F98C FOREIGN KEY (bloc_id_id) REFERENCES bloc (id)');
        $this->addSql('CREATE INDEX IDX_71A6BD8D7BE4F98C ON appartement (bloc_id_id)');
    }
}
