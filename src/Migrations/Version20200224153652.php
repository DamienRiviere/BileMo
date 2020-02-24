<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224153652 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE smartphone (id INT AUTO_INCREMENT NOT NULL, display_id INT NOT NULL, camera_id INT NOT NULL, battery_id INT NOT NULL, name VARCHAR(255) NOT NULL, os VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, processor VARCHAR(255) NOT NULL, gpu VARCHAR(255) NOT NULL, ram VARCHAR(255) NOT NULL, colors LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ports LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_26B07E2E51A2DF33 (display_id), UNIQUE INDEX UNIQ_26B07E2EB47685CD (camera_id), UNIQUE INDEX UNIQ_26B07E2E19A19CFC (battery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8D93D6499395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE battery (id INT AUTO_INCREMENT NOT NULL, capacity VARCHAR(255) NOT NULL, battery_technology VARCHAR(255) NOT NULL, removable_battery VARCHAR(255) NOT NULL, wireless_charging VARCHAR(255) NOT NULL, fast_charge VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE camera (id INT AUTO_INCREMENT NOT NULL, megapixels VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, organization VARCHAR(255) NOT NULL, customer_since DATE NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_81398E09F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, postal_code INT NOT NULL, phone_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE display (id INT AUTO_INCREMENT NOT NULL, size VARCHAR(255) NOT NULL, resolution VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE storage (id INT AUTO_INCREMENT NOT NULL, smartphone_id INT NOT NULL, capacity VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, INDEX IDX_547A1B342E4F4908 (smartphone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, postal_code INT NOT NULL, phone_number INT NOT NULL, INDEX IDX_5543718BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_26B07E2E51A2DF33 FOREIGN KEY (display_id) REFERENCES display (id)');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_26B07E2EB47685CD FOREIGN KEY (camera_id) REFERENCES camera (id)');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_26B07E2E19A19CFC FOREIGN KEY (battery_id) REFERENCES battery (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F5B7AF75 FOREIGN KEY (address_id) REFERENCES customer_address (id)');
        $this->addSql('ALTER TABLE storage ADD CONSTRAINT FK_547A1B342E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE storage DROP FOREIGN KEY FK_547A1B342E4F4908');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BA76ED395');
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_26B07E2E19A19CFC');
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_26B07E2EB47685CD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499395C3F3');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F5B7AF75');
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_26B07E2E51A2DF33');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE battery');
        $this->addSql('DROP TABLE camera');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('DROP TABLE display');
        $this->addSql('DROP TABLE storage');
        $this->addSql('DROP TABLE user_address');
    }
}
