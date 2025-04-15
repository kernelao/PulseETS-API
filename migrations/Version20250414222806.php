<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414222806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aide DROP CONSTRAINT FK_D99184A19D86650F');
        $this->addSql('DROP TABLE aide');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE pulse_point ALTER COLUMN date_created DATETIME2(6) NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pulse_point\', N\'COLUMN\', \'date_created\'');
        $this->addSql('ALTER TABLE tache ADD completed_at DATETIME2(6)');
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
        $this->addSql('CREATE TABLE aide (id INT IDENTITY NOT NULL, user_id_id INT, message NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, date_envoi DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_D99184A19D86650F ON aide (user_id_id)');
        $this->addSql('ALTER TABLE aide ADD CONSTRAINT FK_D99184A19D86650F FOREIGN KEY (user_id_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE pulse_point ALTER COLUMN date_created DATETIME2(6) NOT NULL');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pulse_point\', N\'COLUMN\', \'date_created\'');
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
        $this->addSql('ALTER TABLE tache DROP COLUMN completed_at');
    }
}
