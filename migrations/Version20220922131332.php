<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922131332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom (id INT AUTO_INCREMENT NOT NULL, date_book_at DATE NOT NULL, hour_book_open_at TIME NOT NULL, hour_book_closed_at TIME NOT NULL, is_active TINYINT(1) NOT NULL, forme VARCHAR(25) DEFAULT NULL, link_distanciel VARCHAR(255) DEFAULT NULL, place INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookroom_salle (bookroom_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_5B1968AC84F39C9D (bookroom_id), INDEX IDX_5B1968ACDC304035 (salle_id), PRIMARY KEY(bookroom_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookroom_cours (bookroom_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_E8449A6C84F39C9D (bookroom_id), INDEX IDX_E8449A6C7ECF78B0 (cours_id), PRIMARY KEY(bookroom_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom_salle ADD CONSTRAINT FK_5B1968AC84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_salle ADD CONSTRAINT FK_5B1968ACDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_cours ADD CONSTRAINT FK_E8449A6C84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_cours ADD CONSTRAINT FK_E8449A6C7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom_salle DROP FOREIGN KEY FK_5B1968AC84F39C9D');
        $this->addSql('ALTER TABLE bookroom_salle DROP FOREIGN KEY FK_5B1968ACDC304035');
        $this->addSql('ALTER TABLE bookroom_cours DROP FOREIGN KEY FK_E8449A6C84F39C9D');
        $this->addSql('ALTER TABLE bookroom_cours DROP FOREIGN KEY FK_E8449A6C7ECF78B0');
        $this->addSql('DROP TABLE bookroom');
        $this->addSql('DROP TABLE bookroom_salle');
        $this->addSql('DROP TABLE bookroom_cours');
    }
}
