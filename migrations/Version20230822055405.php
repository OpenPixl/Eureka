<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822055405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, course_id INT DEFAULT NULL, date_book_at DATE NOT NULL, hour_book_open_at TIME NOT NULL, hour_book_closed_at TIME NOT NULL, is_active TINYINT(1) NOT NULL, forme VARCHAR(25) DEFAULT NULL, link_distanciel VARCHAR(255) DEFAULT NULL, place INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_replicate TINYINT(1) DEFAULT NULL, number_of_replicate INT DEFAULT NULL, choice_time VARCHAR(10) DEFAULT NULL, uniq VARCHAR(20) DEFAULT NULL, INDEX IDX_CE8931F754177093 (room_id), INDEX IDX_CE8931F741807E1D (teacher_id), INDEX IDX_CE8931F7591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, level VARCHAR(20) NOT NULL, logo_file VARCHAR(255) DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, color VARCHAR(15) DEFAULT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_member (course_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEDC3B35591CC992 (course_id), INDEX IDX_DEDC3B357597D3FE (member_id), PRIMARY KEY(course_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(5) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, gsm VARCHAR(14) NOT NULL, home VARCHAR(14) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, avatar_file VARCHAR(255) DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, avatar_size VARCHAR(255) DEFAULT NULL, typemember VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, ddn DATE DEFAULT NULL, UNIQUE INDEX UNIQ_70E4FA78E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, studient_id INT DEFAULT NULL, seance_id INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_62A8A7A738FD3D (studient_id), INDEX IDX_62A8A7A7E3797A94 (seance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, max_place INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F754177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F741807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE bookroom ADD CONSTRAINT FK_CE8931F7591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE course_member ADD CONSTRAINT FK_DEDC3B35591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_member ADD CONSTRAINT FK_DEDC3B357597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A738FD3D FOREIGN KEY (studient_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7E3797A94 FOREIGN KEY (seance_id) REFERENCES bookroom (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `member` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F754177093');
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F741807E1D');
        $this->addSql('ALTER TABLE bookroom DROP FOREIGN KEY FK_CE8931F7591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE course_member DROP FOREIGN KEY FK_DEDC3B35591CC992');
        $this->addSql('ALTER TABLE course_member DROP FOREIGN KEY FK_DEDC3B357597D3FE');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A738FD3D');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7E3797A94');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE bookroom');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_member');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
