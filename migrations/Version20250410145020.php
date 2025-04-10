<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410145020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reglages DROP CONSTRAINT FK_46E7DCFACDE6C3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reglages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat ADD CONSTRAINT FK_26A98456FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat ADD CONSTRAINT FK_26A984561F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'started_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'ended_at'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pulse_point ADD CONSTRAINT FK_489C9459FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ADD avatar_principal_id INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ADD theme_name NVARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ALTER COLUMN roles VARCHAR(MAX) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ALTER COLUMN created_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:json)', N'SCHEMA', 'dbo', N'TABLE', 'user', N'COLUMN', 'roles'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'user', N'COLUMN', 'created_at'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ADD CONSTRAINT FK_8D93D649BCC52533 FOREIGN KEY (avatar_principal_id) REFERENCES element (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649BCC52533 ON [user] (avatar_principal_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN created_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN available_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN delivered_at DATETIME2(6)
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'created_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'available_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_addextendedproperty N'MS_Description', N'(DC2Type:datetime_immutable)', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'delivered_at'
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
            CREATE TABLE reglages (id INT IDENTITY NOT NULL, user_nb_id INT, pomodoro INT, courte_pause INT, longue_pause INT, theme NVARCHAR(50) COLLATE SQL_Latin1_General_CP1_CI_AS, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE NONCLUSTERED INDEX UNIQ_46E7DCFACDE6C3 ON reglages (user_nb_id) WHERE user_nb_id IS NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reglages ADD CONSTRAINT FK_46E7DCFACDE6C3 FOREIGN KEY (user_nb_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session ALTER COLUMN started_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodoro_session ALTER COLUMN ended_at DATETIME2(6)
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'started_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'pomodoro_session', N'COLUMN', 'ended_at'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat DROP CONSTRAINT FK_26A98456FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat DROP CONSTRAINT FK_26A984561F1F2A24
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pulse_point DROP CONSTRAINT FK_489C9459FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DEFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP CONSTRAINT FK_8D93D649BCC52533
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649BCC52533 ON [user]
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP COLUMN avatar_principal_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP COLUMN theme_name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ALTER COLUMN roles VARCHAR(MAX) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ALTER COLUMN created_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'user', N'COLUMN', 'roles'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'user', N'COLUMN', 'created_at'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN created_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN available_at DATETIME2(6) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER COLUMN delivered_at DATETIME2(6)
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'created_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'available_at'
        SQL);
        $this->addSql(<<<'SQL'
            EXEC sp_dropextendedproperty N'MS_Description', N'SCHEMA', 'dbo', N'TABLE', 'messenger_messages', N'COLUMN', 'delivered_at'
        SQL);
    }
}
