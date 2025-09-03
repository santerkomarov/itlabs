<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902115302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // Стол
    $this->addSql(<<<SQL
    CREATE TABLE desk (
        id INT AUTO_INCREMENT NOT NULL,
        num INT NOT NULL,
        description VARCHAR(255) DEFAULT NULL,
        max_guests INT NOT NULL,
        guests_def INT DEFAULT NULL,
        guests_now INT DEFAULT NULL,
        guests LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)',
        UNIQUE INDEX uniq_desk_num (num),
        PRIMARY KEY(id)
    ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
    SQL);

    // Гость с FK на Стол
    $this->addSql(<<<SQL
    CREATE TABLE guest (
        id INT AUTO_INCREMENT NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        is_present TINYINT(1) NOT NULL DEFAULT 0,
        desk_id INT DEFAULT NULL,
        INDEX idx_guest_desk (desk_id),
        PRIMARY KEY(id),
        CONSTRAINT FK_guest_desk FOREIGN KEY (desk_id)
            REFERENCES desk (id) ON DELETE SET NULL
    ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
    SQL);
}

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_guest_desk');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE desk');
    }
}
