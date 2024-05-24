<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524151545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materials ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE materials ADD CONSTRAINT FK_9B1716B5DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_9B1716B5DE18E50B ON materials (product_id_id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9D86650F');
        $this->addSql('ALTER TABLE orders CHANGE user_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AE308AC6F');
        $this->addSql('DROP INDEX IDX_B3BA5A5AE308AC6F ON products');
        $this->addSql('ALTER TABLE products DROP material_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materials DROP FOREIGN KEY FK_9B1716B5DE18E50B');
        $this->addSql('DROP INDEX IDX_9B1716B5DE18E50B ON materials');
        $this->addSql('ALTER TABLE materials DROP product_id_id');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9D86650F');
        $this->addSql('ALTER TABLE orders CHANGE user_id_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9D86650F FOREIGN KEY (user_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products ADD material_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AE308AC6F FOREIGN KEY (material_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AE308AC6F ON products (material_id)');
    }
}
