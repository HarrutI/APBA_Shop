<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514212148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bags (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, UNIQUE INDEX UNIQ_1ACE9C65DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bags_products (bags_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_CDCEBF448975E640 (bags_id), INDEX IDX_CDCEBF446C8A81A9 (products_id), PRIMARY KEY(bags_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_products (orders_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_749C879CCFFE9AD6 (orders_id), INDEX IDX_749C879C6C8A81A9 (products_id), PRIMARY KEY(orders_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, name VARCHAR(100) NOT NULL, img VARCHAR(255) DEFAULT NULL, INDEX IDX_B3BA5A5AE308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_tags (products_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_E3AB5A2C6C8A81A9 (products_id), INDEX IDX_E3AB5A2C8D7B4FB4 (tags_id), PRIMARY KEY(products_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bags ADD CONSTRAINT FK_1ACE9C65DC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE bags_products ADD CONSTRAINT FK_CDCEBF448975E640 FOREIGN KEY (bags_id) REFERENCES bags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bags_products ADD CONSTRAINT FK_CDCEBF446C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879CCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AE308AC6F FOREIGN KEY (material_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products_tags ADD CONSTRAINT FK_E3AB5A2C6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_tags ADD CONSTRAINT FK_E3AB5A2C8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEDC2902E0');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEDC2902E0 FOREIGN KEY (client_id_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEDC2902E0');
        $this->addSql('ALTER TABLE bags DROP FOREIGN KEY FK_1ACE9C65DC2902E0');
        $this->addSql('ALTER TABLE bags_products DROP FOREIGN KEY FK_CDCEBF448975E640');
        $this->addSql('ALTER TABLE bags_products DROP FOREIGN KEY FK_CDCEBF446C8A81A9');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879CCFFE9AD6');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C6C8A81A9');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AE308AC6F');
        $this->addSql('ALTER TABLE products_tags DROP FOREIGN KEY FK_E3AB5A2C6C8A81A9');
        $this->addSql('ALTER TABLE products_tags DROP FOREIGN KEY FK_E3AB5A2C8D7B4FB4');
        $this->addSql('DROP TABLE bags');
        $this->addSql('DROP TABLE bags_products');
        $this->addSql('DROP TABLE orders_products');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEDC2902E0');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEDC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id)');
    }
}
