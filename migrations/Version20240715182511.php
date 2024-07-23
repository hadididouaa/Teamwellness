<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715182511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3456C5646');
        $this->addSql('DROP INDEX UNIQ_770CBCD3456C5646 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur DROP evaluation_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_770CBCD3E7927C74 ON collaborateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_770CBCD3E7927C74 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur ADD evaluation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_770CBCD3456C5646 ON collaborateur (evaluation_id)');
    }
}
