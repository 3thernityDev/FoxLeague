<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129042047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero DROP notable_mission');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C296CD8AE');
        $this->addSql('ALTER TABLE mission CHANGE team_id team_id INT NOT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hero ADD notable_mission VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C296CD8AE');
        $this->addSql('ALTER TABLE mission CHANGE team_id team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }
}
