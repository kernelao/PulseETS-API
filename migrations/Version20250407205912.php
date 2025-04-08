<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407205912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note (id INT IDENTITY NOT NULL, utilisateur_id INT NOT NULL, titre NVARCHAR(255) NOT NULL, contenu VARCHAR(MAX), categorie NVARCHAR(255), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_CFBDFA14FB88E14F ON note (utilisateur_id)');
        $this->addSql('CREATE TABLE tache (id INT IDENTITY NOT NULL, user_id INT, titre NVARCHAR(255) NOT NULL, description VARCHAR(MAX), tag NVARCHAR(100), due_date DATETIME2(6) NOT NULL, priority NVARCHAR(20), completed BIT NOT NULL, pinned BIT NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_93872075A76ED395 ON tache (user_id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)');
        $this->addSql('ALTER TABLE pomodoro_session DROP CONSTRAINT FK_6FFF4BB2A76ED395');
        $this->addSql('DROP TABLE pomodoro_session');
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
        $this->addSql('CREATE TABLE pomodoro_session (id INT IDENTITY NOT NULL, user_id INT NOT NULL, started_at DATETIME2(6) NOT NULL, ended_at DATETIME2(6), pomodoros_completes INT, pomodoro_duration INT, short_break INT, long_break INT, auto_start BIT, PRIMARY KEY (id))');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_6FFF4BB2A76ED395 ON pomodoro_session (user_id)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'started_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pomodoro_session\', N\'COLUMN\', \'ended_at\'');
        $this->addSql('ALTER TABLE pomodoro_session ADD CONSTRAINT FK_6FFF4BB2A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14FB88E14F');
        $this->addSql('ALTER TABLE tache DROP CONSTRAINT FK_93872075A76ED395');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE tache');
    }
}
