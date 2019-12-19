<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
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
        $segmentsTable->addColumn('uid', 'uuid');
        $segmentsTable->addColumn('left_side', 'point');
        $segmentsTable->addColumn('right_side', 'point');
        $segmentsTable->addUniqueIndex(['uid']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('segments');
    }
}
