<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417170624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aide (id INT IDENTITY NOT NULL, user_id INT, objet NVARCHAR(255) NOT NULL, contenu VARCHAR(MAX) NOT NULL, type NVARCHAR(50) NOT NULL, priorite INT NOT NULL, statut NVARCHAR(50) NOT NULL, reponse VARCHAR(MAX), date DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_D99184A1A76ED395 ON aide (user_id)');
        $this->addSql('ALTER TABLE aide ADD CONSTRAINT FK_D99184A1A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id)');
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
        $this->addSql('ALTER TABLE aide DROP CONSTRAINT FK_D99184A1A76ED395');
        $this->addSql('DROP TABLE aide');
    }
}
