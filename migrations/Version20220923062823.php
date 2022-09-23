<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923062823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_room (bookroom_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_26A4E8C684F39C9D (bookroom_id), INDEX IDX_26A4E8C654177093 (room_id), PRIMARY KEY(bookroom_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, max_place INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom_room ADD CONSTRAINT FK_26A4E8C684F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_room ADD CONSTRAINT FK_26A4E8C654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_salle DROP FOREIGN KEY FK_5B1968AC84F39C9D');
        $this->addSql('ALTER TABLE bookroom_salle DROP FOREIGN KEY FK_5B1968ACDC304035');
        $this->addSql('DROP TABLE bookroom_salle');
        $this->addSql('DROP TABLE salle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom_salle (bookroom_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_5B1968ACDC304035 (salle_id), INDEX IDX_5B1968AC84F39C9D (bookroom_id), PRIMARY KEY(bookroom_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, max_place INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bookroom_salle ADD CONSTRAINT FK_5B1968AC84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_salle ADD CONSTRAINT FK_5B1968ACDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_room DROP FOREIGN KEY FK_26A4E8C684F39C9D');
        $this->addSql('ALTER TABLE bookroom_room DROP FOREIGN KEY FK_26A4E8C654177093');
        $this->addSql('DROP TABLE bookroom_room');
        $this->addSql('DROP TABLE room');
    }
}
