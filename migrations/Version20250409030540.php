<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409030540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_accessadmin
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_backupoperator
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_datareader
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_datawriter
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_ddladmin
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_denydatareader
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_denydatawriter
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_owner
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA db_securityadmin
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SCHEMA dbo
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP CONSTRAINT FK_8D93D649BCC52533
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EA86383B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF159027487
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE goal DROP CONSTRAINT FK_FCDCEB2EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pulse_point DROP CONSTRAINT FK_489C9459A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DE667D1AFE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE achat_avatar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE achat_theme
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avatar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE goal
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pulse_point
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recompense
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE theme
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649BCC52533 ON [user]
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP COLUMN avatar_principal_id
        SQL);
    }
}
