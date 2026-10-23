<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211061150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wb_attribute (attribute_id UNIQUEIDENTIFIER NOT NULL, name NVARCHAR(1024) NOT NULL, type NVARCHAR(64) NOT NULL, charc_type INT NOT NULL, max_count INT NOT NULL, measurement NVARCHAR(8), description NVARCHAR(1024), alias NVARCHAR(55), group_id UNIQUEIDENTIFIER, default_value VARCHAR(MAX), is_required BIT NOT NULL, is_popular BIT NOT NULL, is_dictionary BIT NOT NULL, is_read_only BIT NOT NULL, is_visible BIT NOT NULL, is_deleted BIT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), PRIMARY KEY (attribute_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_attribute\', N\'COLUMN\', default_value');
        $this->addSql('CREATE TABLE wb_attribute_group (attribute_group_id UNIQUEIDENTIFIER NOT NULL, name NVARCHAR(128) NOT NULL, alias NVARCHAR(64) NOT NULL, type NVARCHAR(64) NOT NULL, order_group INT NOT NULL, tab_id UNIQUEIDENTIFIER, is_deleted BIT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), PRIMARY KEY (attribute_group_id))');
        $this->addSql('CREATE TABLE wb_attribute_map (attribute_map_id UNIQUEIDENTIFIER NOT NULL, wb_attribute_id UNIQUEIDENTIFIER NOT NULL, pim_attribute_id UNIQUEIDENTIFIER NOT NULL, wb_name NVARCHAR(1024) NOT NULL, pim_alias NVARCHAR(64) NOT NULL, wb_measure NVARCHAR(16), pim_measure NVARCHAR(16), PRIMARY KEY (attribute_map_id))');
        $this->addSql('CREATE TABLE wb_catalog (catalog_id UNIQUEIDENTIFIER NOT NULL, object_id INT NOT NULL, parent_id INT, name NVARCHAR(1024) NOT NULL, level INT, is_visible BIT NOT NULL, is_active BIT NOT NULL, is_deleted BIT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), PRIMARY KEY (catalog_id))');
        $this->addSql('CREATE TABLE wb_catalog_attribute (catalog_attribute_id UNIQUEIDENTIFIER NOT NULL, catalog_id NVARCHAR(40) NOT NULL, attribute_id NVARCHAR(40) NOT NULL, PRIMARY KEY (catalog_attribute_id))');
        $this->addSql('CREATE TABLE wb_price (product_id UNIQUEIDENTIFIER NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), price INT NOT NULL, discount INT NOT NULL, final_price INT NOT NULL, is_stock_available BIT NOT NULL, net_cost INT, PRIMARY KEY (product_id))');
        $this->addSql('CREATE TABLE wb_product (product_id UNIQUEIDENTIFIER NOT NULL, is_active BIT NOT NULL, is_deleted BIT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), catalog_id UNIQUEIDENTIFIER, imt_id INT, nm_id INT, vendor_code NVARCHAR(256), brand NVARCHAR(256), sizes VARCHAR(MAX), seller_name NVARCHAR(128), media_files VARCHAR(MAX), colors VARCHAR(MAX), tags VARCHAR(MAX), is_prohibited BIT NOT NULL, export_status INT NOT NULL, PRIMARY KEY (product_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47AA650CB06C0DCD ON wb_product (nm_id) WHERE nm_id IS NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', sizes');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', media_files');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', colors');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product\', N\'COLUMN\', tags');
        $this->addSql('ALTER TABLE wb_product ADD CONSTRAINT DF_47AA650C_930FC13 DEFAULT 0 FOR export_status');
        $this->addSql('CREATE TABLE wb_product_attribute (product_attribute_id UNIQUEIDENTIFIER NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), product_id UNIQUEIDENTIFIER NOT NULL, attribute_id UNIQUEIDENTIFIER NOT NULL, value VARCHAR(MAX) NOT NULL, hash NVARCHAR(4000) NOT NULL, prepared_value VARCHAR(MAX), PRIMARY KEY (product_attribute_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product_attribute\', N\'COLUMN\', value');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product_attribute\', N\'COLUMN\', prepared_value');
        $this->addSql('CREATE TABLE wb_product_attribute_history (product_attribute_history_id UNIQUEIDENTIFIER NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), user_id UNIQUEIDENTIFIER NOT NULL, product_attribute_id UNIQUEIDENTIFIER NOT NULL, new_value VARCHAR(MAX) NOT NULL, old_value VARCHAR(MAX), PRIMARY KEY (product_attribute_history_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product_attribute_history\', N\'COLUMN\', new_value');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_product_attribute_history\', N\'COLUMN\', old_value');
        $this->addSql('CREATE TABLE wb_suggest (suggest_id UNIQUEIDENTIFIER NOT NULL, is_active BIT NOT NULL, is_deleted BIT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6), value VARCHAR(MAX) NOT NULL, info NVARCHAR(256), attribute_id UNIQUEIDENTIFIER, catalog_id UNIQUEIDENTIFIER, object_id INT, PRIMARY KEY (suggest_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'wb_suggest\', N\'COLUMN\', value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
