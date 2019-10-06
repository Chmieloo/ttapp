<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191006121317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE config');
        $this->addSql('ALTER TABLE tournament ADD office_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9FFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('CREATE INDEX IDX_BD5FB8D9FFA0C224 ON tournament (office_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, value VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9FFA0C224');
        $this->addSql('DROP INDEX IDX_BD5FB8D9FFA0C224 ON tournament');
        $this->addSql('ALTER TABLE tournament DROP office_id');
    }
}
