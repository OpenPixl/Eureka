<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110132524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookroom (id INT AUTO_INCREMENT NOT NULL, date_book_at DATE NOT NULL, hour_book_open_at TIME NOT NULL, hour_book_closed_at TIME NOT NULL, is_active TINYINT(1) NOT NULL, forme VARCHAR(25) DEFAULT NULL, link_distanciel VARCHAR(255) DEFAULT NULL, place INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookroom_room (bookroom_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_26A4E8C684F39C9D (bookroom_id), INDEX IDX_26A4E8C654177093 (room_id), PRIMARY KEY(bookroom_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookroom_course (bookroom_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_AB3613B384F39C9D (bookroom_id), INDEX IDX_AB3613B3591CC992 (course_id), PRIMARY KEY(bookroom_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, level VARCHAR(20) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(5) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, mobile VARCHAR(14) NOT NULL, home VARCHAR(14) DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_70E4FA78E7927C74 (email), INDEX IDX_70E4FA78537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, sujet VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_member (message_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_47C36777537A1329 (message_id), INDEX IDX_47C367777597D3FE (member_id), PRIMARY KEY(message_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_member (registration_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DEBA3A92833D8F43 (registration_id), INDEX IDX_DEBA3A927597D3FE (member_id), PRIMARY KEY(registration_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_bookroom (registration_id INT NOT NULL, bookroom_id INT NOT NULL, INDEX IDX_F17C467F833D8F43 (registration_id), INDEX IDX_F17C467F84F39C9D (bookroom_id), PRIMARY KEY(registration_id, bookroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, max_place INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookroom_room ADD CONSTRAINT FK_26A4E8C684F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_room ADD CONSTRAINT FK_26A4E8C654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B384F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookroom_course ADD CONSTRAINT FK_AB3613B3591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE message_member ADD CONSTRAINT FK_47C36777537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_member ADD CONSTRAINT FK_47C367777597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A92833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_member ADD CONSTRAINT FK_DEBA3A927597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_bookroom ADD CONSTRAINT FK_F17C467F833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_bookroom ADD CONSTRAINT FK_F17C467F84F39C9D FOREIGN KEY (bookroom_id) REFERENCES bookroom (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookroom_room DROP FOREIGN KEY FK_26A4E8C684F39C9D');
        $this->addSql('ALTER TABLE bookroom_room DROP FOREIGN KEY FK_26A4E8C654177093');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B384F39C9D');
        $this->addSql('ALTER TABLE bookroom_course DROP FOREIGN KEY FK_AB3613B3591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78537A1329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message_member DROP FOREIGN KEY FK_47C36777537A1329');
        $this->addSql('ALTER TABLE message_member DROP FOREIGN KEY FK_47C367777597D3FE');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A92833D8F43');
        $this->addSql('ALTER TABLE registration_member DROP FOREIGN KEY FK_DEBA3A927597D3FE');
        $this->addSql('ALTER TABLE registration_bookroom DROP FOREIGN KEY FK_F17C467F833D8F43');
        $this->addSql('ALTER TABLE registration_bookroom DROP FOREIGN KEY FK_F17C467F84F39C9D');
        $this->addSql('DROP TABLE bookroom');
        $this->addSql('DROP TABLE bookroom_room');
        $this->addSql('DROP TABLE bookroom_course');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_member');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE registration_member');
        $this->addSql('DROP TABLE registration_bookroom');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
