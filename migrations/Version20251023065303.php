<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251023065303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_orders DROP FOREIGN KEY FK_631C76C43EEE31F6');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F529939819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEDC2902E0');
        $this->addSql('DROP TABLE orders');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_category');
        $this->addSql('ALTER TABLE products CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a9777d11e TO IDX_B3BA5A5A12469DE2');
        $this->addSql('DROP INDEX IDX_631C76C43EEE31F6 ON products_orders');
        $this->addSql('ALTER TABLE products_orders ADD product_id INT NOT NULL, CHANGE orders_id_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE products_orders ADD CONSTRAINT FK_631C76C48D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE products_orders ADD CONSTRAINT FK_631C76C44584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_631C76C48D9F6D38 ON products_orders (order_id)');
        $this->addSql('CREATE INDEX IDX_631C76C44584665A ON products_orders (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_orders DROP FOREIGN KEY FK_631C76C48D9F6D38');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E52FFDEEDC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE products CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a12469de2 TO IDX_B3BA5A5A9777D11E');
        $this->addSql('ALTER TABLE products_orders DROP FOREIGN KEY FK_631C76C44584665A');
        $this->addSql('DROP INDEX IDX_631C76C48D9F6D38 ON products_orders');
        $this->addSql('DROP INDEX IDX_631C76C44584665A ON products_orders');
        $this->addSql('ALTER TABLE products_orders ADD orders_id_id INT NOT NULL, DROP order_id, DROP product_id');
        $this->addSql('ALTER TABLE products_orders ADD CONSTRAINT FK_631C76C43EEE31F6 FOREIGN KEY (orders_id_id) REFERENCES orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_631C76C43EEE31F6 ON products_orders (orders_id_id)');
    }
}
