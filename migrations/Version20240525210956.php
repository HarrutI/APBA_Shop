<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240525210956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bag_products (id INT AUTO_INCREMENT NOT NULL, bag_id_id INT NOT NULL, product_id_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_66AE699D8272BCAA (bag_id_id), INDEX IDX_66AE699DDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_products (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, product_id_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_5242B8EBFCDAEAAA (order_id_id), INDEX IDX_5242B8EBDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bag_products ADD CONSTRAINT FK_66AE699D8272BCAA FOREIGN KEY (bag_id_id) REFERENCES bags (id)');
        $this->addSql('ALTER TABLE bag_products ADD CONSTRAINT FK_66AE699DDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EBFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EBDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE bags_products DROP FOREIGN KEY FK_CDCEBF446C8A81A9');
        $this->addSql('ALTER TABLE bags_products DROP FOREIGN KEY FK_CDCEBF448975E640');
        $this->addSql('DROP TABLE bags_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bags_products (bags_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_CDCEBF448975E640 (bags_id), INDEX IDX_CDCEBF446C8A81A9 (products_id), PRIMARY KEY(bags_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bags_products ADD CONSTRAINT FK_CDCEBF446C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bags_products ADD CONSTRAINT FK_CDCEBF448975E640 FOREIGN KEY (bags_id) REFERENCES bags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bag_products DROP FOREIGN KEY FK_66AE699D8272BCAA');
        $this->addSql('ALTER TABLE bag_products DROP FOREIGN KEY FK_66AE699DDE18E50B');
        $this->addSql('ALTER TABLE order_products DROP FOREIGN KEY FK_5242B8EBFCDAEAAA');
        $this->addSql('ALTER TABLE order_products DROP FOREIGN KEY FK_5242B8EBDE18E50B');
        $this->addSql('DROP TABLE bag_products');
        $this->addSql('DROP TABLE order_products');
    }
}
