<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409235054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE pomodoro_session (id INT IDENTITY NOT NULL, user_id INT NOT NULL, started_at DATETIME2(6) NOT NULL, ended_at DATETIME2(6), pomodoros_completes INT, pomodoro_duration INT, short_break INT, long_break INT, auto_start BIT, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6FFF4BB2A76ED395 ON pomodoro_session (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'started_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'ended_at'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session ADD CONSTRAINT FK_6FFF4BB2A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tache DROP CONSTRAINT FK_93872075A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE note
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tache
        SQL);
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
            CREATE TABLE note (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, titre NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, contenu VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS, categorie NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE NONCLUSTERED INDEX IDX_CFBDFA14FB88E14F ON note (utilisateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tache (id INT IDENTITY NOT NULL, user_id INT, titre NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, description VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS, tag NVARCHAR(100) COLLATE SQL_Latin1_General_CP1_CI_AS, due_date DATETIME2(6) NOT NULL, priority NVARCHAR(20) COLLATE SQL_Latin1_General_CP1_CI_AS, completed BIT NOT NULL, pinned BIT NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE NONCLUSTERED INDEX IDX_93872075A76ED395 ON tache (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tache ADD CONSTRAINT FK_93872075A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session DROP CONSTRAINT FK_6FFF4BB2A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pomodoro_session
        SQL);
    }
}
