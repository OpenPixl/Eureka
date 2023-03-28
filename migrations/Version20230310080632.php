<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310080632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B3591CC992');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B384F39C9D');
        $this->addSql('DROP TABLE bookroom_course');
        $this->addSql('ALTER TABLE bookroom ADD room_id INT DEFAULT NULL, ADD teacher_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F754177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F741807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('CREATE INDEX IDX_CE8931F754177093 ON bookroom (room_id)');
        $this->addSql('CREATE INDEX IDX_CE8931F741807E1D ON bookroom (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_course (bookroom_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_AB3613B3591CC992 (course_id), INDEX IDX_AB3613B384F39C9D (bookroom_id), PRIMARY KEY(bookroom_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B3591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B384F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F754177093');
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F741807E1D');
        $this->addSql('DROP INDEX IDX_CE8931F754177093 ON bookroom');
        $this->addSql('DROP INDEX IDX_CE8931F741807E1D ON bookroom');
        $this->addSql('ALTER TABLE bookroom DROP room_id, DROP teacher_id, CHANGE created_at created_at DATE NOT NULL, CHANGE updated_at updated_at DATE NOT NULL');
    }
}
