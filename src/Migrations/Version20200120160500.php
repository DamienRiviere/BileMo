<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120160500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE test (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer_address AS SELECT id, street, city, region, postal_code, phone_number FROM customer_address');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('CREATE TABLE customer_address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, street VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, region VARCHAR(255) NOT NULL COLLATE BINARY, postal_code INTEGER NOT NULL, phone_number INTEGER NOT NULL)');
        $this->addSql('INSERT INTO customer_address (id, street, city, region, postal_code, phone_number) SELECT id, street, city, region, postal_code, phone_number FROM __temp__customer_address');
        $this->addSql('DROP TABLE __temp__customer_address');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_address AS SELECT id, street, city, region, postal_code, phone_number, user_id FROM user_address');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('CREATE TABLE user_address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, street VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, region VARCHAR(255) NOT NULL COLLATE BINARY, postal_code INTEGER NOT NULL, phone_number INTEGER NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO user_address (id, street, city, region, postal_code, phone_number, user_id) SELECT id, street, city, region, postal_code, phone_number, user_id FROM __temp__user_address');
        $this->addSql('DROP TABLE __temp__user_address');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE customer_address ADD COLUMN name VARCHAR(255) NOT NULL COLLATE BINARY');
        $this->addSql('ALTER TABLE user_address ADD COLUMN name VARCHAR(255) NOT NULL COLLATE BINARY');
    }
}
