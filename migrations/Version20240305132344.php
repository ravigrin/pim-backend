<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305132344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ozon_attribute (attribute_uuid UNIQUEIDENTIFIER NOT NULL, catalog_uuid UNIQUEIDENTIFIER, catalog_id INT NOT NULL, type_id INT NOT NULL, attribute_id INT NOT NULL, attribute_complex_id INT NOT NULL, name NVARCHAR(1000) NOT NULL, description NVARCHAR(4000) NOT NULL, pim_type NVARCHAR(256) NOT NULL, type NVARCHAR(256) NOT NULL, is_collection BIT NOT NULL, is_required BIT NOT NULL, is_aspect BIT NOT NULL, max_value_count INT NOT NULL, group_name NVARCHAR(256) NOT NULL, group_id INT NOT NULL, dictionary_id INT NOT NULL, alias NVARCHAR(256), union_flag NVARCHAR(40), tab_uuid UNIQUEIDENTIFIER NOT NULL, group_uuid UNIQUEIDENTIFIER NOT NULL, read_only BIT NOT NULL, is_visible BIT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute\', N\'COLUMN\', attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute\', N\'COLUMN\', catalog_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute\', N\'COLUMN\', tab_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute\', N\'COLUMN\', group_uuid');
        $this->addSql('ALTER TABLE ozon_attribute ADD CONSTRAINT DF_8751306D_652DEF69 DEFAULT 0 FOR read_only');
        $this->addSql('CREATE TABLE ozon_attribute_bridge (attribute_uuid UNIQUEIDENTIFIER NOT NULL, attribute_pim_uuid UNIQUEIDENTIFIER NOT NULL, handler NVARCHAR(1024) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_uuid, attribute_pim_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute_bridge\', N\'COLUMN\', attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute_bridge\', N\'COLUMN\', attribute_pim_uuid');
        $this->addSql('CREATE TABLE ozon_attribute_group (attribute_group_uuid UNIQUEIDENTIFIER NOT NULL, name NVARCHAR(1024) NOT NULL, alias NVARCHAR(1024) NOT NULL, custom_order INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_group_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute_group\', N\'COLUMN\', attribute_group_uuid');
        $this->addSql('CREATE TABLE ozon_attribute_tab (attribute_tab_uuid UNIQUEIDENTIFIER NOT NULL, name NVARCHAR(1024) NOT NULL, alias NVARCHAR(1024) NOT NULL, custom_order INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_tab_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_attribute_tab\', N\'COLUMN\', attribute_tab_uuid');
        $this->addSql('CREATE TABLE ozon_catalog (catalog_uuid UNIQUEIDENTIFIER NOT NULL, tree_path NVARCHAR(1024) NOT NULL, level INT NOT NULL, catalog_id INT NOT NULL, type_id INT NOT NULL, type_name NVARCHAR(255) NOT NULL, is_active BIT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (catalog_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_catalog\', N\'COLUMN\', catalog_uuid');
        $this->addSql('CREATE TABLE ozon_dictionary (dictionary_uuid UNIQUEIDENTIFIER NOT NULL, catalog_uuid UNIQUEIDENTIFIER NOT NULL, attribute_uuid UNIQUEIDENTIFIER NOT NULL, value NVARCHAR(1024) NOT NULL, catalog_id INT NOT NULL, attribute_id INT NOT NULL, dictionary_id INT NOT NULL, info NVARCHAR(1024), picture NVARCHAR(1024), is_active BIT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (dictionary_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_dictionary\', N\'COLUMN\', dictionary_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_dictionary\', N\'COLUMN\', catalog_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_dictionary\', N\'COLUMN\', attribute_uuid');
        $this->addSql('CREATE TABLE ozon_product (product_uuid UNIQUEIDENTIFIER NOT NULL, date_update DATETIME2(6) NOT NULL, date_create DATETIME2(6) NOT NULL, user_uuid UNIQUEIDENTIFIER NOT NULL, offer_id NVARCHAR(40) NOT NULL, barcode NVARCHAR(40), ozon_product_id INT, catalog_uuid UNIQUEIDENTIFIER NOT NULL, currency_code NVARCHAR(40) NOT NULL, dimension_unit NVARCHAR(40) NOT NULL, vat NVARCHAR(40) NOT NULL, weight_unit NVARCHAR(40) NOT NULL, unification VARCHAR(MAX) NOT NULL, export INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (product_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product\', N\'COLUMN\', product_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product\', N\'COLUMN\', user_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product\', N\'COLUMN\', catalog_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product\', N\'COLUMN\', unification');
        $this->addSql('CREATE TABLE ozon_product_attribute (product_attribute_uuid UNIQUEIDENTIFIER NOT NULL, attribute_uuid UNIQUEIDENTIFIER NOT NULL, product_uuid UNIQUEIDENTIFIER NOT NULL, user_uuid UNIQUEIDENTIFIER NOT NULL, value VARCHAR(MAX) NOT NULL, hash NVARCHAR(4000), prepare_value VARCHAR(MAX), is_deleted BIT NOT NULL, PRIMARY KEY (product_attribute_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', product_attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', product_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', user_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', value');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute\', N\'COLUMN\', prepare_value');
        $this->addSql('CREATE TABLE ozon_product_attribute_history (product_attribute_history_uuid UNIQUEIDENTIFIER NOT NULL, date_create DATETIME2(6) NOT NULL, product_attribute_uuid UNIQUEIDENTIFIER NOT NULL, product_uuid UNIQUEIDENTIFIER NOT NULL, attribute_uuid UNIQUEIDENTIFIER NOT NULL, user_uuid UNIQUEIDENTIFIER NOT NULL, new_value VARCHAR(MAX) NOT NULL, old_value VARCHAR(MAX), PRIMARY KEY (product_attribute_history_uuid))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', product_attribute_history_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', product_attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', product_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', attribute_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:uuid)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', user_uuid');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', new_value');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'ozon_product_attribute_history\', N\'COLUMN\', old_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
