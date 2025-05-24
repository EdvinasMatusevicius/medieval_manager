<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524143937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `character` DROP FOREIGN KEY FK_937AB0349EEA759
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_937AB0349EEA759 ON `character`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `character` DROP inventory_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership ADD character_id INT DEFAULT NULL, ADD tavern_id INT DEFAULT NULL, DROP owner_id, DROP owner_type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership ADD CONSTRAINT FK_9F2C05FC1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership ADD CONSTRAINT FK_9F2C05FC416D7217 FOREIGN KEY (tavern_id) REFERENCES tavern (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9F2C05FC1136BE75 ON inventory_ownership (character_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9F2C05FC416D7217 ON inventory_ownership (tavern_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tavern DROP FOREIGN KEY FK_326811299EEA759
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_326811299EEA759 ON tavern
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tavern DROP inventory_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `character` ADD inventory_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `character` ADD CONSTRAINT FK_937AB0349EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_937AB0349EEA759 ON `character` (inventory_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership DROP FOREIGN KEY FK_9F2C05FC1136BE75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership DROP FOREIGN KEY FK_9F2C05FC416D7217
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_9F2C05FC1136BE75 ON inventory_ownership
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_9F2C05FC416D7217 ON inventory_ownership
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_ownership ADD owner_id INT NOT NULL, ADD owner_type VARCHAR(255) NOT NULL, DROP character_id, DROP tavern_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tavern ADD inventory_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tavern ADD CONSTRAINT FK_326811299EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_326811299EEA759 ON tavern (inventory_id)
        SQL);
    }
}
