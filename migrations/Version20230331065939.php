<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331065939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_member (course_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEDC3B35591CC992 (course_id), INDEX IDX_DEDC3B357597D3FE (member_id), PRIMARY KEY(course_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_member ADD CONSTRAINT FK_DEDC3B35591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_member ADD CONSTRAINT FK_DEDC3B357597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_member DROP FOREIGN KEY FK_DEDC3B35591CC992');
        $this->addSql('ALTER TABLE course_member DROP FOREIGN KEY FK_DEDC3B357597D3FE');
        $this->addSql('DROP TABLE course_member');
    }
}
