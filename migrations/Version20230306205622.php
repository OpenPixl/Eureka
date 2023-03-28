<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306205622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_course (bookroom_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_AB3613B384F39C9D (bookroom_id), INDEX IDX_AB3613B3591CC992 (course_id), PRIMARY KEY(bookroom_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B384F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B3591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B384F39C9D');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B3591CC992');
        $this->addSql('DROP TABLE bookroom_course');
    }
}
