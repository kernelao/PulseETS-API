<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409182124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reglages DROP CONSTRAINT FK_46E7DCF9D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reglages
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
            CREATE TABLE reglages (id INT IDENTITY NOT NULL, user_id_id INT, pomodoro INT, courte_pause INT, longue_pause INT, theme NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE NONCLUSTERED INDEX UNIQ_46E7DCF9D86650F ON reglages (user_id_id) WHERE user_id_id IS NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reglages ADD CONSTRAINT FK_46E7DCF9D86650F FOREIGN KEY (user_id_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
