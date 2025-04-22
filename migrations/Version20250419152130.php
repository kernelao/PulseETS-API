<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419152130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE aide DROP CONSTRAINT FK_D99184A1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aide
        SQL);
        $this->addSql("ALTER TABLE [reglages] DROP CONSTRAINT DF_46E7DCF_F5628617");

        $this->addSql(<<<'SQL'
            ALTER TABLE reglages DROP COLUMN is_default
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
            CREATE TABLE aide (id INT IDENTITY NOT NULL, user_id INT, objet NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, contenu VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, type NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, priorite INT NOT NULL, statut NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, reponse VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS, date DATETIME2(6) NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE NONCLUSTERED INDEX IDX_D99184A1A76ED395 ON aide (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aide ADD CONSTRAINT FK_D99184A1A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reglages ADD is_default BIT NOT NULL CONSTRAINT DF_46E7DCF_F5628617 DEFAULT 0
        SQL);
    }
}
