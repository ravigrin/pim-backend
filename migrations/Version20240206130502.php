<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206130502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update wb_price table - add production_cost, production_cost_flag';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wb_price ADD production_cost DOUBLE PRECISION');
        $this->addSql('ALTER TABLE wb_price ADD production_cost_flag BIT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
