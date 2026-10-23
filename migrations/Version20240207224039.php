<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207224039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wb_media (media_id NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), number INT NOT NULL, product_id NVARCHAR(255) NOT NULL, little NVARCHAR(256), small NVARCHAR(256), big NVARCHAR(256), video NVARCHAR(256), hash NVARCHAR(256) NOT NULL, PRIMARY KEY (media_id))');
        $this->addSql('CREATE TABLE wb_size (size_id NVARCHAR(255) NOT NULL, product_id NVARCHAR(255) NOT NULL, chrt_id INT NOT NULL, tech_size NVARCHAR(64) NOT NULL, wb_size NVARCHAR(64) NOT NULL, skus VARCHAR(MAX) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), PRIMARY KEY (size_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_size\', N\'COLUMN\', skus');
        $this->addSql('ALTER TABLE wb_product ADD title NVARCHAR(1000)');
        $this->addSql('ALTER TABLE wb_product ADD description NVARCHAR(2000)');
        $this->addSql('ALTER TABLE wb_product ADD nm_uuid NVARCHAR(255)');
        $this->addSql('ALTER TABLE wb_product ADD dimensions_id NVARCHAR(255)');
        $this->addSql('ALTER TABLE wb_product ADD wb_updated_at DATETIME2(6)');
        $this->addSql('ALTER TABLE wb_product ADD media VARCHAR(MAX)');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN media_files');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN colors');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN is_prohibited');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', media');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47AA650CB8F723E2 ON wb_product (nm_uuid) WHERE nm_uuid IS NOT NULL');
        $this->addSql('ALTER TABLE wb_product ADD dimensions VARCHAR(MAX)');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN sizes');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN dimensions_id');
        $this->addSql('ALTER TABLE wb_product DROP COLUMN media');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', dimensions');
        $this->addSql('ALTER TABLE wb_product_attribute ADD wb_attribute_id INT');
        $this->addSql('ALTER TABLE wb_product_attribute DROP COLUMN prepared_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
