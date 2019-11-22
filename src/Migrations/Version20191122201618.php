<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122201618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE battle ADD winner_id_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734FC53D4E9 FOREIGN KEY (winner_id_id) REFERENCES fighter (id)');
        $this->addSql('CREATE INDEX IDX_13991734FC53D4E9 ON battle (winner_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734FC53D4E9');
        $this->addSql('DROP INDEX IDX_13991734FC53D4E9 ON battle');
        $this->addSql('ALTER TABLE battle DROP winner_id_id, DROP description');
    }
}
