<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330175907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id NVARCHAR(255) NOT NULL, image NVARCHAR(255) NOT NULL, points_required INT NOT NULL, available_from DATETIME2(6), available_until DATETIME2(6), is_event_exclusive BIT NOT NULL, is_goal_based BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE tache DROP CONSTRAINT FK_93872075A76ED395');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE SessionPomodoro');
        $this->addSql('ALTER TABLE [user] ADD avatar INT');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'roles\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'available_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'delivered_at\'');
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
        $this->addSql('CREATE TABLE tache (id INT IDENTITY NOT NULL, user_id INT, titre NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, description VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS, tag NVARCHAR(100) COLLATE SQL_Latin1_General_CP1_CI_AS, due_date DATETIME2(6) NOT NULL, priority NVARCHAR(20) COLLATE SQL_Latin1_General_CP1_CI_AS, completed BIT NOT NULL, pinned BIT NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_93872075A76ED395 ON tache (user_id)');
        $this->addSql('CREATE TABLE SessionPomodoro (session_Id INT IDENTITY NOT NULL, duree_totale NUMERIC(18, 0), date_session DATETIME2(6) NOT NULL, heure_debut TIME(0), heure_fin TIME(0), PRIMARY KEY (session_Id))');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('ALTER TABLE [user] DROP COLUMN avatar');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'roles\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'available_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'delivered_at\'');
    }
}
