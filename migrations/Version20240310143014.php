<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310143014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update wb_attribute table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wb_attribute ADD source NVARCHAR(55)');
        $this->addSql("UPDATE wb_attribute SET source = 'characteristics' WHERE PIM.dbo.wb_attribute.alias IS NULL");
        $this->addSql("UPDATE wb_attribute SET source = 'module' WHERE PIM.dbo.wb_attribute.alias IS NOT NULL");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
