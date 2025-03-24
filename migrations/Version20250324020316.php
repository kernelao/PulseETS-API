<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324020316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE [user] ALTER COLUMN username NVARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON [user] (username) WHERE username IS NOT NULL');
        $this->addSql('EXEC sp_rename N\'[user].uniq_8d93d649e7927c74\', N\'UNIQ_IDENTIFIER_EMAIL\', N\'INDEX\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON [user]');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN username NVARCHAR(50)');
        $this->addSql('EXEC sp_rename N\'[user].uniq_identifier_email\', N\'UNIQ_8D93D649E7927C74\', N\'INDEX\'');
    }
}
