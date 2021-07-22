<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528143011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, first_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product CHANGE category category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE promotion CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE message message VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE product CHANGE category category VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'track\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE promotion MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE promotion DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE promotion CHANGE id id INT DEFAULT NULL, CHANGE message message VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`');
    }
}
