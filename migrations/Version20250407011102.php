<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407011102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE achat_avatar (id INT IDENTITY NOT NULL, user_id INT NOT NULL, avatar_id INT NOT NULL, date_achat DATETIME2(6) NOT NULL, is_active BIT NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D20E12EAA76ED395 ON achat_avatar (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D20E12EA86383B10 ON achat_avatar (avatar_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE achat_theme (id INT IDENTITY NOT NULL, user_id INT, theme_id INT NOT NULL, date_achat DATE, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F5F9AF1A76ED395 ON achat_theme (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F5F9AF159027487 ON achat_theme (theme_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE avatar (id INT IDENTITY NOT NULL, name NVARCHAR(255) NOT NULL, active BIT NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE goal (id INT IDENTITY NOT NULL, user_id INT NOT NULL, description NVARCHAR(255) NOT NULL, completed BIT NOT NULL, date_created DATETIME2(6) NOT NULL, points INT NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FCDCEB2EA76ED395 ON goal (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pulse_point (id INT IDENTITY NOT NULL, user_id INT NOT NULL, points INT NOT NULL, date_created DATETIME2(6) NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_489C9459A76ED395 ON pulse_point (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE recompense (id INT IDENTITY NOT NULL, goal_id INT, type NVARCHAR(255) NOT NULL, nom NVARCHAR(50) NOT NULL, valeur INT NOT NULL, description NVARCHAR(255) NOT NULL, avatar_offert NVARCHAR(255) NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1E9BC0DE667D1AFE ON recompense (goal_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE theme (id INT IDENTITY NOT NULL, name NVARCHAR(25) NOT NULL, active BIT NOT NULL, PRIMARY KEY (id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE theme_user (theme_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY (theme_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C754227459027487 ON theme_user (theme_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C7542274A76ED395 ON theme_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_goal (user_id INT NOT NULL, goal_id INT NOT NULL, PRIMARY KEY (user_id, goal_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_865DA7E7A76ED395 ON user_goal (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_865DA7E7667D1AFE ON user_goal (goal_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_avatar (user_id INT NOT NULL, avatar_id INT NOT NULL, PRIMARY KEY (user_id, avatar_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_73256912A76ED395 ON user_avatar (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7325691286383B10 ON user_avatar (avatar_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar ADD CONSTRAINT FK_D20E12EAA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar ADD CONSTRAINT FK_D20E12EA86383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme ADD CONSTRAINT FK_8F5F9AF1A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme ADD CONSTRAINT FK_8F5F9AF159027487 FOREIGN KEY (theme_id) REFERENCES theme (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pulse_point ADD CONSTRAINT FK_489C9459A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme_user ADD CONSTRAINT FK_C754227459027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme_user ADD CONSTRAINT FK_C7542274A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_goal ADD CONSTRAINT FK_865DA7E7A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_goal ADD CONSTRAINT FK_865DA7E7667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_avatar ADD CONSTRAINT FK_73256912A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_avatar ADD CONSTRAINT FK_7325691286383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ADD avatar_id INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES achat_avatar (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D64986383B10 ON [user] (avatar_id)
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
            ALTER TABLE [user] DROP CONSTRAINT FK_8D93D64986383B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_avatar DROP CONSTRAINT FK_D20E12EA86383B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE achat_theme DROP CONSTRAINT FK_8F5F9AF159027487
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE goal DROP CONSTRAINT FK_FCDCEB2EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pulse_point DROP CONSTRAINT FK_489C9459A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recompense DROP CONSTRAINT FK_1E9BC0DE667D1AFE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme_user DROP CONSTRAINT FK_C754227459027487
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme_user DROP CONSTRAINT FK_C7542274A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_goal DROP CONSTRAINT FK_865DA7E7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_goal DROP CONSTRAINT FK_865DA7E7667D1AFE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_avatar DROP CONSTRAINT FK_73256912A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_avatar DROP CONSTRAINT FK_7325691286383B10
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE achat_avatar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE achat_theme
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avatar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE goal
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pulse_point
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recompense
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE theme
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE theme_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_goal
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_avatar
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D64986383B10 ON [user]
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE [user] DROP COLUMN avatar_id
        SQL);
    }
}
