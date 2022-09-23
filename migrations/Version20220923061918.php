<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923061918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_course (bookroom_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_AB3613B384F39C9D (bookroom_id), INDEX IDX_AB3613B3591CC992 (course_id), PRIMARY KEY(bookroom_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, level VARCHAR(20) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B384F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B3591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE bookroom_cours DROP FOREIGN KEY FK_E8449A6C7ECF78B0');
        $this->addSql('ALTER TABLE bookroom_cours DROP FOREIGN KEY FK_E8449A6C84F39C9D');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');
        $this->addSql('DROP TABLE bookroom_cours');
        $this->addSql('DROP TABLE cours');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_cours (bookroom_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_E8449A6C7ECF78B0 (cours_id), INDEX IDX_E8449A6C84F39C9D (bookroom_id), PRIMARY KEY(bookroom_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, level VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_FDCA8C9C41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bookroom_cours ADD CONSTRAINT FK_E8449A6C7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_cours ADD CONSTRAINT FK_E8449A6C84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B384F39C9D');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B3591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('DROP TABLE bookroom_course');
        $this->addSql('DROP TABLE course');
    }
}
