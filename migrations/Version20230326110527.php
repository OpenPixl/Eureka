<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326110527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F7591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_CE8931F7591CC992 ON bookroom (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F7591CC992');
        $this->addSql('DROP INDEX IDX_CE8931F7591CC992 ON bookroom');
        $this->addSql('ALTER TABLE bookroom DROP course_id');
    }
}
