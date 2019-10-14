<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012110143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spectators (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, spectators INT NOT NULL, pit DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_cup (id INT AUTO_INCREMENT NOT NULL, tournament_id_id INT NOT NULL, level_id INT DEFAULT NULL, game_mode_id INT NOT NULL, home_player_id VARCHAR(255) NOT NULL, away_player_id VARCHAR(255) NOT NULL, is_finished TINYINT(1) NOT NULL, is_abandoned TINYINT(1) NOT NULL, is_walkover TINYINT(1) NOT NULL, date_of_match DATETIME NOT NULL, winner_id VARCHAR(255) NOT NULL, home_score INT NOT NULL, away_score INT NOT NULL, date_played DATETIME NOT NULL, current_set INT NOT NULL, server_id INT NOT NULL, play_order INT NOT NULL, stage INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_ACC74321BE120E4E (tournament_id_id), INDEX IDX_ACC743215FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_cup ADD CONSTRAINT FK_ACC74321BE120E4E FOREIGN KEY (tournament_id_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE game_cup ADD CONSTRAINT FK_ACC743215FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE spectators');
        $this->addSql('DROP TABLE game_cup');
    }
}
