<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908161535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE paste (
                id VARCHAR(255) NOT NULL, 
                created_by_id INT NOT NULL, 
                text TEXT NOT NULL, 
                expiration_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                availability VARCHAR(30) NOT NULL, 
                PRIMARY KEY(id)
            )');
        $this->addSql('CREATE INDEX IDX_9C567898B03A8386 ON paste (created_by_id)');
        $this->addSql('
            CREATE TABLE "user" (
                id INT NOT NULL, 
                login VARCHAR(255) NOT NULL, 
                password VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('ALTER TABLE paste ADD CONSTRAINT FK_9C567898B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE paste DROP CONSTRAINT FK_9C567898B03A8386');
        $this->addSql('DROP TABLE paste');
        $this->addSql('DROP TABLE "user"');
    }
}
