<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410033919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, element_id INT NOT NULL, date_achat DATETIME2(6) NOT NULL, is_active BIT, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_26A98456FB88E14F ON achat (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_26A984561F1F2A24 ON achat (element_id)');
        $this->addSql('CREATE TABLE element (id INT IDENTITY NOT NULL, name NVARCHAR(50) NOT NULL, type NVARCHAR(20) NOT NULL, active BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE note (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, titre NVARCHAR(255) NOT NULL, contenu VARCHAR(MAX), categorie NVARCHAR(255), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_CFBDFA14FB88E14F ON note (utilisateur_id)');
        $this->addSql('CREATE TABLE pulse_point (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, points INT NOT NULL, date_created DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_489C9459FB88E14F ON pulse_point (utilisateur_id)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pulse_point\', N\'COLUMN\', \'date_created\'');
        $this->addSql('CREATE TABLE recompense (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, type NVARCHAR(50) NOT NULL, seuil INT NOT NULL, date_debloquee DATETIME2(6) NOT NULL, nom NVARCHAR(255) NOT NULL, valeur INT NOT NULL, description NVARCHAR(255) NOT NULL, avatar_offert NVARCHAR(255), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_1E9BC0DEFB88E14F ON recompense (utilisateur_id)');
        $this->addSql('CREATE TABLE tache (id INT IDENTITY NOT NULL, user_id INT, titre NVARCHAR(255) NOT NULL, description VARCHAR(MAX), tag NVARCHAR(100), due_date DATETIME2(6) NOT NULL, priority NVARCHAR(20), completed BIT NOT NULL, pinned BIT NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_93872075A76ED395 ON tache (user_id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984561F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE pulse_point ADD CONSTRAINT FK_489C9459FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE reglages DROP CONSTRAINT FK_46E7DCF5661AA');
        $this->addSql('DROP TABLE reglages');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE [user] ADD avatar_principal_id INT');
        $this->addSql('ALTER TABLE [user] ADD theme_name NVARCHAR(50)');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN roles VARCHAR(MAX) NOT NULL');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN created_at DATETIME2(6) NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'roles\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', \'created_at\'');
        $this->addSql('ALTER TABLE [user] ADD CONSTRAINT FK_8D93D649BCC52533 FOREIGN KEY (avatar_principal_id) REFERENCES element (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BCC52533 ON [user] (avatar_principal_id)');
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
        $this->addSql('ALTER TABLE [user] DROP CONSTRAINT FK_8D93D649BCC52533');
        $this->addSql('CREATE TABLE reglages (id INT IDENTITY NOT NULL, user_id_p_id INT, pomodoro_p INT, courte_pause_p INT, longue_pause_p INT, theme_p NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE NONCLUSTERED INDEX UNIQ_46E7DCF5661AA ON reglages (user_id_p_id) WHERE user_id_p_id IS NOT NULL');
        $this->addSql('ALTER TABLE reglages ADD CONSTRAINT FK_46E7DCF5661AA FOREIGN KEY (user_id_p_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat DROP CONSTRAINT FK_26A98456FB88E14F');
        $this->addSql('ALTER TABLE achat DROP CONSTRAINT FK_26A984561F1F2A24');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14FB88E14F');
        $this->addSql('ALTER TABLE pulse_point DROP CONSTRAINT FK_489C9459FB88E14F');
        $this->addSql('ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DEFB88E14F');
        $this->addSql('ALTER TABLE tache DROP CONSTRAINT FK_93872075A76ED395');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE pulse_point');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE tache');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL');
        $this->addSql('ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_dropextendedproperty N\'MS_Description\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('DROP INDEX IDX_8D93D649BCC52533 ON [user]');
        $this->addSql('ALTER TABLE [user] DROP COLUMN avatar_principal_id');
        $this->addSql('ALTER TABLE [user] DROP COLUMN theme_name');
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
