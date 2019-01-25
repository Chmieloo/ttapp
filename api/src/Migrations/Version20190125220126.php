<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125220126 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `match` ADD home_player_id INT NOT NULL, ADD away_player_id INT NOT NULL');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505E7328C9B FOREIGN KEY (home_player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC5056861DE1 FOREIGN KEY (away_player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_7A5BC505E7328C9B ON `match` (home_player_id)');
        $this->addSql('CREATE INDEX IDX_7A5BC5056861DE1 ON `match` (away_player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC505E7328C9B');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC5056861DE1');
        $this->addSql('DROP INDEX IDX_7A5BC505E7328C9B ON `match`');
        $this->addSql('DROP INDEX IDX_7A5BC5056861DE1 ON `match`');
        $this->addSql('ALTER TABLE `match` DROP home_player_id, DROP away_player_id');
    }
}
