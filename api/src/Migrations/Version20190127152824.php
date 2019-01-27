<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190127152824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, game_mode_id INT DEFAULT NULL, home_player_id INT NOT NULL, away_player_id INT NOT NULL, tournament_id INT DEFAULT NULL, is_finished TINYINT(1) NOT NULL, is_abandoned TINYINT(1) NOT NULL, is_walkover TINYINT(1) NOT NULL, date_of_match DATETIME NOT NULL, INDEX IDX_232B318CE227FA65 (game_mode_id), INDEX IDX_232B318CE7328C9B (home_player_id), INDEX IDX_232B318C6861DE1 (away_player_id), INDEX IDX_232B318C33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE227FA65 FOREIGN KEY (game_mode_id) REFERENCES game_mode (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE7328C9B FOREIGN KEY (home_player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C6861DE1 FOREIGN KEY (away_player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE game');
    }
}
