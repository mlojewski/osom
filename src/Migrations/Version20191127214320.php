<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191127214320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resolution_project ADD resolution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resolution_project ADD CONSTRAINT FK_4B46220512A1C43A FOREIGN KEY (resolution_id) REFERENCES resolution (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B46220512A1C43A ON resolution_project (resolution_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resolution_project DROP FOREIGN KEY FK_4B46220512A1C43A');
        $this->addSql('DROP INDEX UNIQ_4B46220512A1C43A ON resolution_project');
        $this->addSql('ALTER TABLE resolution_project DROP resolution_id');
    }
}
