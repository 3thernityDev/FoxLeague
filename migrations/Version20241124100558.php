<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124100558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hero (id INT AUTO_INCREMENT NOT NULL, power_link_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, secret_idendity VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, notable_mission VARCHAR(255) DEFAULT NULL, succes_rate INT NOT NULL, fail_rate INT NOT NULL, INDEX IDX_51CE6E866D0A960D (power_link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E866D0A960D FOREIGN KEY (power_link_id) REFERENCES power (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E866D0A960D');
        $this->addSql('DROP TABLE hero');
    }
}
