<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306093907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom (id INT AUTO_INCREMENT NOT NULL, date_book_at DATE NOT NULL, hour_book_open_at TIME NOT NULL, hour_book_closed_at TIME NOT NULL, is_active TINYINT(1) NOT NULL, forme VARCHAR(25) DEFAULT NULL, link_distanciel VARCHAR(255) DEFAULT NULL, place INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, level VARCHAR(20) NOT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(5) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, gsm VARCHAR(14) NOT NULL, home VARCHAR(14) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, avatar_file VARCHAR(255) DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, avatar_size VARCHAR(255) DEFAULT NULL, typemember VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_70E4FA78E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_member (registration_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEBA3A92833D8F43 (registration_id), INDEX IDX_DEBA3A927597D3FE (member_id), PRIMARY KEY(registration_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, max_place INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A92833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A927597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A92833D8F43');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A927597D3FE');
        $this->addSql('DROP TABLE bookroom');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE registration_member');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
