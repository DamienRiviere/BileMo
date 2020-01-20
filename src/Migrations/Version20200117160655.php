<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200117160655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE battery (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, capacity VARCHAR(255) NOT NULL, battery_technology VARCHAR(255) NOT NULL, removable_battery VARCHAR(255) NOT NULL, wireless_charging VARCHAR(255) NOT NULL, fast_charge VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE camera (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, megapixels VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, organization VARCHAR(255) NOT NULL, customer_since DATE NOT NULL, address_id INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09F5B7AF75 ON customer (address_id)');
        $this->addSql('CREATE TABLE customer_address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, postal_code INTEGER NOT NULL, phone_number INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE display (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, size VARCHAR(255) NOT NULL, resolution VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE smartphone (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, os VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, processor VARCHAR(255) NOT NULL, gpu VARCHAR(255) NOT NULL, ram VARCHAR(255) NOT NULL, colors CLOB NOT NULL --(DC2Type:array)
        , ports CLOB NOT NULL --(DC2Type:array)
        , display_id INTEGER NOT NULL, camera_id INTEGER NOT NULL, battery_id INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_26B07E2E51A2DF33 ON smartphone (display_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_26B07E2EB47685CD ON smartphone (camera_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_26B07E2E19A19CFC ON smartphone (battery_id)');
        $this->addSql('CREATE TABLE storage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, capacity VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, smartphone_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, customer_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE user_address (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, postal_code INTEGER NOT NULL, phone_number INTEGER NOT NULL, user_id INTEGER NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE battery');
        $this->addSql('DROP TABLE camera');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('DROP TABLE display');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP TABLE storage');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_address');
    }
}
