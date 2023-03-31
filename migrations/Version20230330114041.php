<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330114041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD seance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7E3797A94 FOREIGN KEY (seance_id) REFERENCES bookroom (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A7E3797A94 ON registration (seance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7E3797A94');
        $this->addSql('DROP INDEX IDX_62A8A7A7E3797A94 ON registration');
        $this->addSql('ALTER TABLE registration DROP seance_id');
    }
}
