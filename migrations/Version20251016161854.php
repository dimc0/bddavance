<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016161854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_orders DROP FOREIGN KEY FK_products_orders_order');
        $this->addSql('ALTER TABLE products_orders DROP FOREIGN KEY FK_products_orders_product');
        $this->addSql('DROP INDEX FK_products_orders_product ON products_orders');
        $this->addSql('ALTER TABLE products_orders DROP product_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_orders ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE products_orders ADD CONSTRAINT FK_products_orders_product FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_products_orders_product ON products_orders (product_id)');
    }
}
