<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229085716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE files_product_image (product_image_id INT IDENTITY NOT NULL, date_create DATETIME2(6) NOT NULL, product_id NVARCHAR(255) NOT NULL, image_id NVARCHAR(1000) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (product_image_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
