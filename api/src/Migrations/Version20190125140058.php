<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125140058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_finished TINYINT(1) NOT NULL, start_time DATETIME DEFAULT NULL, end_time DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, match_mode_id INT NOT NULL, is_finished TINYINT(1) NOT NULL, is_abandoned TINYINT(1) NOT NULL, is_walkover TINYINT(1) NOT NULL, INDEX IDX_7A5BC50543DA25 (match_mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50543DA25 FOREIGN KEY (match_mode_id) REFERENCES match_mode (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE `match`');
    }
}
