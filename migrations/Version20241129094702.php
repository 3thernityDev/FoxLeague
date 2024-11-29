<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129094702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C20DCF697');
        $this->addSql('DROP INDEX IDX_9067F23C20DCF697 ON mission');
        $this->addSql('ALTER TABLE mission DROP team_history_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission ADD team_history_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C20DCF697 FOREIGN KEY (team_history_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_9067F23C20DCF697 ON mission (team_history_id)');
    }
}
