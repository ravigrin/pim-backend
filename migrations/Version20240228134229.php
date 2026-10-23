<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228134229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create AdAnalytics link_create_log';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad_analytics_link_create_log (hash NVARCHAR(255) NOT NULL, status NVARCHAR(64), message NVARCHAR(255), created_at DATETIME2(6) NOT NULL, PRIMARY KEY (hash))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
