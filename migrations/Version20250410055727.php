<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410055727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache (id BIGINT IDENTITY NOT NULL, user_id INT, titre NVARCHAR(255) NOT NULL, description VARCHAR(MAX), tag NVARCHAR(100), due_date DATETIME2(6) NOT NULL, priority NVARCHAR(20), completed BIT NOT NULL, pinned BIT NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_93872075A76ED395 ON tache (user_id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE reglages_p DROP CONSTRAINT FK_4E29E036A76ED395');
        $this->addSql('DROP TABLE reglages_p');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN roles VARCHAR(MAX) NOT NULL');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN created_at DATETIME2(6) NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'roles\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'created_at\'');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN created_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN available_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN delivered_at DATETIME2(6)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'available_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'delivered_at\'');
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
        $this->addSql('CREATE TABLE reglages_p (id INT IDENTITY NOT NULL, user_id INT, pomodoro INT, courte_pause INT, longue_pause INT, theme NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE NONCLUSTERED INDEX UNIQ_4E29E036A76ED395 ON reglages_p (user_id) WHERE user_id IS NOT NULL');
        $this->addSql('ALTER TABLE reglages_p ADD CONSTRAINT FK_4E29E036A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tache DROP CONSTRAINT FK_93872075A76ED395');
        $this->addSql('DROP TABLE tache');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN roles VARCHAR(MAX) NOT NULL');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN created_at DATETIME2(6) NOT NULL');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'roles\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'created_at\'');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN created_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN available_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages ALTER COLUMN delivered_at DATETIME2(6)');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'available_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'delivered_at\'');
    }
}
