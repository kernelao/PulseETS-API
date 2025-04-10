<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409040159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE [user] DROP CONSTRAINT FK_8D93D649BCC52533');
        $this->addSql('CREATE TABLE note (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, titre NVARCHAR(255) NOT NULL, contenu VARCHAR(MAX), categorie NVARCHAR(255), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_CFBDFA14FB88E14F ON note (utilisateur_id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EA86383B10');
        $this->addSql('ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EAA76ED395');
        $this->addSql('ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF159027487');
        $this->addSql('ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF1A76ED395');
        $this->addSql('ALTER TABLE goal DROP CONSTRAINT FK_FCDCEB2EA76ED395');
        $this->addSql('ALTER TABLE pulse_point DROP CONSTRAINT FK_489C9459A76ED395');
        $this->addSql('ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DE667D1AFE');
        $this->addSql('ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DEA76ED395');
        $this->addSql('DROP TABLE achat_avatar');
        $this->addSql('DROP TABLE achat_theme');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE pulse_point');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP INDEX UNIQ_8D93D649BCC52533 ON [user]');
        $this->addSql('ALTER TABLE [user] DROP COLUMN avatar_principal_id');
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
        $this->addSql('CREATE TABLE achat_avatar (id INT IDENTITY NOT NULL, user_id INT NOT NULL, avatar_id INT NOT NULL, date_achat DATETIME2(6) NOT NULL, is_active BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_D20E12EAA76ED395 ON achat_avatar (user_id)');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_D20E12EA86383B10 ON achat_avatar (avatar_id)');
        $this->addSql('CREATE TABLE achat_theme (id INT IDENTITY NOT NULL, user_id INT, theme_id INT NOT NULL, date_achat DATE, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_8F5F9AF1A76ED395 ON achat_theme (user_id)');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_8F5F9AF159027487 ON achat_theme (theme_id)');
        $this->addSql('CREATE TABLE avatar (id INT IDENTITY NOT NULL, name NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, active BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE goal (id INT IDENTITY NOT NULL, user_id INT NOT NULL, description NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, completed BIT NOT NULL, date_created DATETIME2(6) NOT NULL, points INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_FCDCEB2EA76ED395 ON goal (user_id)');
        $this->addSql('CREATE TABLE pulse_point (id INT IDENTITY NOT NULL, user_id INT NOT NULL, points INT NOT NULL, date_created DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_489C9459A76ED395 ON pulse_point (user_id)');
        $this->addSql('CREATE TABLE recompense (id INT IDENTITY NOT NULL, goal_id INT, user_id INT NOT NULL, type NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, nom NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, valeur INT NOT NULL, description NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, avatar_offert NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_1E9BC0DE667D1AFE ON recompense (goal_id)');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_1E9BC0DEA76ED395 ON recompense (user_id)');
        $this->addSql('CREATE TABLE theme (id INT IDENTITY NOT NULL, name NVARCHAR(25) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, active BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE achat_avatar ADD CONSTRAINT FK_D20E12EA86383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_avatar ADD CONSTRAINT FK_D20E12EAA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_theme ADD CONSTRAINT FK_8F5F9AF159027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_theme ADD CONSTRAINT FK_8F5F9AF1A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pulse_point ADD CONSTRAINT FK_489C9459A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DEA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14FB88E14F');
        $this->addSql('DROP TABLE note');
        $this->addSql('ALTER TABLE [user] ADD avatar_principal_id INT');
        $this->addSql('ALTER TABLE [user] ADD CONSTRAINT FK_8D93D649BCC52533 FOREIGN KEY (avatar_principal_id) REFERENCES achat_avatar (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE NONCLUSTERED INDEX UNIQ_8D93D649BCC52533 ON [user] (avatar_principal_id) WHERE avatar_principal_id IS NOT NULL');
    }
}
