<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201150624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ad-analytics tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad_analytics_full_statistic (full_statistic_id NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), link_id NVARCHAR(255) NOT NULL, add_time NVARCHAR(255) NOT NULL, user_agent NVARCHAR(2000) NOT NULL, is_mobile BIT NOT NULL, hash NVARCHAR(255) NOT NULL, PRIMARY KEY (full_statistic_id))');
        $this->addSql('CREATE TABLE ad_analytics_link (link_id NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), product_id NVARCHAR(255), ad_analytics_link_id INT NOT NULL, link NVARCHAR(1000) NOT NULL, shortcode NVARCHAR(64) NOT NULL, phrase NVARCHAR(1000), description NVARCHAR(1000), original_link NVARCHAR(2000) NOT NULL, campaign NVARCHAR(64), blogger NVARCHAR(64), source NVARCHAR(64), medium NVARCHAR(64), link_type NVARCHAR(128), folder_id NVARCHAR(128), folder_name NVARCHAR(128), folder_description NVARCHAR(128), PRIMARY KEY (link_id))');
        $this->addSql('CREATE TABLE ad_analytics_statistic (statistic_id NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), link_id NVARCHAR(255) NOT NULL, today INT NOT NULL, yesterday INT NOT NULL, all_time INT NOT NULL, PRIMARY KEY (statistic_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B44E843ADA40271 ON ad_analytics_statistic (link_id) WHERE link_id IS NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
