<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922125226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, sujet VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_member (message_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_47C36777537A1329 (message_id), INDEX IDX_47C367777597D3FE (member_id), PRIMARY KEY(message_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE message_member ADD CONSTRAINT FK_47C36777537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_member ADD CONSTRAINT FK_47C367777597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `member` ADD message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78537A1329 ON `member` (message_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78537A1329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message_member DROP FOREIGN KEY FK_47C36777537A1329');
        $this->addSql('ALTER TABLE message_member DROP FOREIGN KEY FK_47C367777597D3FE');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_member');
        $this->addSql('DROP INDEX IDX_70E4FA78537A1329 ON `member`');
        $this->addSql('ALTER TABLE `member` DROP message_id');
    }
}
