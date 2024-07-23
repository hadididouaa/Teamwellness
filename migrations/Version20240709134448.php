<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709134448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collaborateur (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_770CBCD3456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, moyenne INT NOT NULL, interpretation LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, num INT NOT NULL, enonce LONGTEXT NOT NULL, rep1 LONGTEXT NOT NULL, score1 INT NOT NULL, rep2 LONGTEXT NOT NULL, score2 INT NOT NULL, rep3 LONGTEXT NOT NULL, score3 INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponses (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, question_id INT NOT NULL, evaluation_id INT NOT NULL, scorereponse INT NOT NULL, INDEX IDX_1E512EC6A848E3B1 (collaborateur_id), INDEX IDX_1E512EC61E27F6BF (question_id), INDEX IDX_1E512EC6456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3456C5646');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6A848E3B1');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC61E27F6BF');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6456C5646');
        $this->addSql('DROP TABLE collaborateur');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reponses');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
