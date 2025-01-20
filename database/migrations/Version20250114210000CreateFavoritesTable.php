<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250114210000CreateFavoritesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates Favorites table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('favorites');
        $table->addColumn('id', 'string', ['length' => 36]);
        $table->addColumn('user_id', 'string', ['length' => 36]);
        $table->addColumn('gif_id', 'string', ['length' => 36]);
        $table->addColumn('alias', 'string', ['length' => 150]);
        $table->addUniqueIndex(['user_id', 'alias']);
        $table->addUniqueIndex(['user_id', 'gif_id']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('favorites');
    }
}
