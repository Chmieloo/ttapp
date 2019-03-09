<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190309093204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_cup ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_cup ADD CONSTRAINT FK_ACC743215FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('CREATE INDEX IDX_ACC743215FB14BA7 ON game_cup (level_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_cup DROP FOREIGN KEY FK_ACC743215FB14BA7');
        $this->addSql('DROP INDEX IDX_ACC743215FB14BA7 ON game_cup');
        $this->addSql('ALTER TABLE game_cup DROP level_id');
    }
}
