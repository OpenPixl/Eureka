<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922134513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_member (registration_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEBA3A92833D8F43 (registration_id), INDEX IDX_DEBA3A927597D3FE (member_id), PRIMARY KEY(registration_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_bookroom (registration_id INT NOT NULL, bookroom_id INT NOT NULL, INDEX IDX_F17C467F833D8F43 (registration_id), INDEX IDX_F17C467F84F39C9D (bookroom_id), PRIMARY KEY(registration_id, bookroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A92833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A927597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_bookroom ADD CONSTRAINT FK_F17C467F833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_bookroom ADD CONSTRAINT FK_F17C467F84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A92833D8F43');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A927597D3FE');
        $this->addSql('ALTER TABLE registration_bookroom DROP FOREIGN KEY FK_F17C467F833D8F43');
        $this->addSql('ALTER TABLE registration_bookroom DROP FOREIGN KEY FK_F17C467F84F39C9D');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE registration_member');
        $this->addSql('DROP TABLE registration_bookroom');
    }
}
