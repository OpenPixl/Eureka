<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330112604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD studient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A738FD3D FOREIGN KEY (studient_id) REFERENCES `member` (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A738FD3D ON registration (studient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A738FD3D');
        $this->addSql('DROP INDEX IDX_62A8A7A738FD3D ON registration');
        $this->addSql('ALTER TABLE registration DROP studient_id');
    }
}
