<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200104100351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE Segment (uid UUID NOT NULL, left_side point NOT NULL, right_side point NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(uid))');
        $this->addSql('COMMENT ON COLUMN Segment.uid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN Segment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE Task (id UUID NOT NULL, token UUID NOT NULL, status VARCHAR(255) NOT NULL, message VARCHAR(255) DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F24C741B5F37A13B ON Task (token)');
        $this->addSql('COMMENT ON COLUMN Task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN Task.token IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN Task.createdAt IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE Segment');
        $this->addSql('DROP TABLE Task');
    }
}
