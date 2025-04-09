<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409030339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    
}


public function down(Schema $schema): void
{
    $this->addSql(<<<'SQL'
        ALTER TABLE [user] DROP CONSTRAINT FK_8D93D649BCC52533
    SQL);
    $this->addSql(<<<'SQL'
        DROP INDEX UNIQ_8D93D649BCC52533 ON [user]
    SQL);
    $this->addSql(<<<'SQL'
        ALTER TABLE [user] DROP COLUMN avatar_principal_id
    SQL);
}

}
