<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330112448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A927597D3FE');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A92833D8F43');
        $this->addSql('DROP TABLE registration_member');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration_member (registration_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEBA3A927597D3FE (member_id), INDEX IDX_DEBA3A92833D8F43 (registration_id), PRIMARY KEY(registration_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A927597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A92833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
