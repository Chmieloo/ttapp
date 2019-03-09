<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190309123548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game ADD play_order INT DEFAULT NULL, ADD stage INT DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD level INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_cup DROP FOREIGN KEY FK_ACC74321BE120E4E');
        $this->addSql('DROP INDEX IDX_ACC74321BE120E4E ON game_cup');
        $this->addSql('ALTER TABLE game_cup CHANGE tournament_id tournament_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_cup ADD CONSTRAINT FK_ACC74321BE120E4E FOREIGN KEY (tournament_id_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_ACC74321BE120E4E ON game_cup (tournament_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP play_order, DROP stage, DROP name, DROP level');
        $this->addSql('ALTER TABLE game_cup DROP FOREIGN KEY FK_ACC74321BE120E4E');
        $this->addSql('DROP INDEX IDX_ACC74321BE120E4E ON game_cup');
        $this->addSql('ALTER TABLE game_cup CHANGE tournament_id_id tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_cup ADD CONSTRAINT FK_ACC74321BE120E4E FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_ACC74321BE120E4E ON game_cup (tournament_id)');
    }
}
