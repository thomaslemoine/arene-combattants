<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124165610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fighter (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, strength INT NOT NULL, intelligence INT NOT NULL, pv INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, killed_at DATETIME DEFAULT NULL, slug LONGTEXT NOT NULL, INDEX IDX_7A08C3FCC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE battle (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, winner_id_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_139917349F2C3FAB (zone_id), INDEX IDX_13991734FC53D4E9 (winner_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE battle_fighter (battle_id INT NOT NULL, fighter_id INT NOT NULL, INDEX IDX_B097779FC9732719 (battle_id), INDEX IDX_B097779F34934341 (fighter_id), PRIMARY KEY(battle_id, fighter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FCC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_139917349F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734FC53D4E9 FOREIGN KEY (winner_id_id) REFERENCES fighter (id)');
        $this->addSql('ALTER TABLE battle_fighter ADD CONSTRAINT FK_B097779FC9732719 FOREIGN KEY (battle_id) REFERENCES battle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE battle_fighter ADD CONSTRAINT FK_B097779F34934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734FC53D4E9');
        $this->addSql('ALTER TABLE battle_fighter DROP FOREIGN KEY FK_B097779F34934341');
        $this->addSql('ALTER TABLE fighter DROP FOREIGN KEY FK_7A08C3FCC54C8C93');
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_139917349F2C3FAB');
        $this->addSql('ALTER TABLE battle_fighter DROP FOREIGN KEY FK_B097779FC9732719');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE battle');
        $this->addSql('DROP TABLE battle_fighter');
    }
}
