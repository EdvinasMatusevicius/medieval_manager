<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519154328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE character_time (id INT AUTO_INCREMENT NOT NULL, owning_character_id INT NOT NULL, start_date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', `current_date` DATETIME NOT NULL, time_multiplier DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_78688CDDEE29551 (owning_character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE character_time ADD CONSTRAINT FK_78688CDDEE29551 FOREIGN KEY (owning_character_id) REFERENCES `character` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE character_time DROP FOREIGN KEY FK_78688CDDEE29551
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE character_time
        SQL);
    }
}
