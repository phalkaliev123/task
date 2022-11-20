<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114121249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS user');
        $this->addSql('CREATE TABLE user (
                id INT auto_increment NOT NULL,
                email varchar(100) NOT NULL,
                first_name varchar(100) NOT NULL,
                last_name varchar(100) NOT NULL,
                CONSTRAINT user_UN UNIQUE KEY (email),
                CONSTRAINT user_PK PRIMARY KEY (id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci;
        ');
        $this->addSql("INSERT INTO user (email, first_name, last_name ) VALUES('test@test.com', 'test first name' , 'test last name')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS task.user');
    }
}
