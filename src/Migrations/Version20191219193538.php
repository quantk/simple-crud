<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219193538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $segmentsTable = $schema->createTable('segments');
        $segmentsTable->addColumn('uid', 'uuid')->setNotnull(true);
        $segmentsTable->addColumn('left_side', 'point');
        $segmentsTable->addColumn('right_side', 'point');
        $segmentsTable->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $segmentsTable->setPrimaryKey(['uid']);

        $tasksTable = $schema->createTable('tasks');
        $tasksTable->addColumn('id', 'uuid');
        $tasksTable->addColumn('token', 'uuid');
        $tasksTable->addColumn('status', 'string');
        $tasksTable->addColumn('message', 'string')->setNotnull(false);
        $tasksTable->addColumn('created_at', Types::DATETIME_IMMUTABLE);
        $tasksTable->setPrimaryKey(['id']);
        $tasksTable->addUniqueIndex(['token']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('segments');
    }
}
