<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211061051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pim_attribute (attribute_id NVARCHAR(40) NOT NULL, name NVARCHAR(1024) NOT NULL, alias NVARCHAR(1024) NOT NULL, tab_id NVARCHAR(40), group_id NVARCHAR(40), description NVARCHAR(4000), is_required BIT NOT NULL, pim_type NVARCHAR(1024), max_count INT NOT NULL, measurement NVARCHAR(8), attribute_group_id NVARCHAR(40) NOT NULL, is_visible BIT NOT NULL, read_only BIT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_id))');
        $this->addSql('ALTER TABLE pim_attribute ADD CONSTRAINT DF_CF579130_6C8C6E91 DEFAULT 1 FOR is_visible');
        $this->addSql('ALTER TABLE pim_attribute ADD CONSTRAINT DF_CF579130_652DEF69 DEFAULT 0 FOR read_only');
        $this->addSql('CREATE TABLE pim_attribute_group (attribute_group_id NVARCHAR(40) NOT NULL, name NVARCHAR(1024) NOT NULL, alias NVARCHAR(1024) NOT NULL, custom_order INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_group_id))');
        $this->addSql('CREATE TABLE pim_attribute_tab (attribute_tab_id NVARCHAR(40) NOT NULL, name NVARCHAR(1024) NOT NULL, alias NVARCHAR(1024) NOT NULL, custom_order INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (attribute_tab_id))');
        $this->addSql('CREATE TABLE pim_brand (brand_id NVARCHAR(40) NOT NULL, name NVARCHAR(1024) NOT NULL, code NVARCHAR(1024) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (brand_id))');
        $this->addSql('CREATE TABLE pim_catalog (catalog_id NVARCHAR(40) NOT NULL, name NVARCHAR(255) NOT NULL, tree_path NVARCHAR(1024) NOT NULL, parent_id NVARCHAR(40), level INT, is_deleted BIT NOT NULL, PRIMARY KEY (catalog_id))');
        $this->addSql('CREATE TABLE pim_dictionary (dictionary_id NVARCHAR(40) NOT NULL, attribute_id NVARCHAR(255) NOT NULL, value NVARCHAR(1024) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (dictionary_id))');
        $this->addSql('CREATE TABLE pim_product (product_id NVARCHAR(40) NOT NULL, date_update DATETIME2(6) NOT NULL, date_create DATETIME2(6) NOT NULL, user_id NVARCHAR(40) NOT NULL, vendor_code NVARCHAR(255), catalog_id NVARCHAR(40), unit_id NVARCHAR(40), brand_id NVARCHAR(40), product_line_id NVARCHAR(40), is_deleted BIT NOT NULL, PRIMARY KEY (product_id))');
        $this->addSql('CREATE TABLE pim_product_attribute (product_attribute_id NVARCHAR(40) NOT NULL, date_create DATETIME2(6) NOT NULL, product_id NVARCHAR(40) NOT NULL, attribute_id NVARCHAR(40) NOT NULL, user_id NVARCHAR(40) NOT NULL, value VARCHAR(MAX) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (product_attribute_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_product_attribute\', N\'COLUMN\', value');
        $this->addSql('CREATE TABLE pim_product_attribute_history (product_attribute_history_id NVARCHAR(40) NOT NULL, date_create DATETIME2(6) NOT NULL, product_attribute_id NVARCHAR(40) NOT NULL, product_id NVARCHAR(40) NOT NULL, attribute_id NVARCHAR(40) NOT NULL, user_id NVARCHAR(40) NOT NULL, new_value VARCHAR(MAX) NOT NULL, old_value VARCHAR(MAX), PRIMARY KEY (product_attribute_history_id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_product_attribute_history\', N\'COLUMN\', new_value');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_product_attribute_history\', N\'COLUMN\', old_value');
        $this->addSql('CREATE TABLE pim_product_line (product_line_id NVARCHAR(40) NOT NULL, brand_id NVARCHAR(40) NOT NULL, name NVARCHAR(256) NOT NULL, code NVARCHAR(256) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (product_line_id))');
        $this->addSql('CREATE TABLE pim_unit (unit_id NVARCHAR(40) NOT NULL, name NVARCHAR(1024) NOT NULL, code NVARCHAR(1024) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (unit_id))');
        $this->addSql('CREATE TABLE pim_user (user_id NVARCHAR(255) NOT NULL, date_create DATETIME2(6) NOT NULL, username NVARCHAR(255) NOT NULL, roles VARCHAR(MAX) NOT NULL, units VARCHAR(MAX) NOT NULL, brands VARCHAR(MAX) NOT NULL, product_lines VARCHAR(MAX) NOT NULL, sources VARCHAR(MAX) NOT NULL, token NVARCHAR(1024) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_75A20A04F85E0677 ON pim_user (username) WHERE username IS NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_user\', N\'COLUMN\', roles');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_user\', N\'COLUMN\', units');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_user\', N\'COLUMN\', brands');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_user\', N\'COLUMN\', product_lines');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'pim_user\', N\'COLUMN\', sources');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
