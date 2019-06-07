<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607150107 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE talent_three ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE talent_three ADD CONSTRAINT FK_B095C6725DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE INDEX IDX_B095C6725DA0FB8 ON talent_three (template_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE talent_three DROP FOREIGN KEY FK_B095C6725DA0FB8');
        $this->addSql('DROP INDEX IDX_B095C6725DA0FB8 ON talent_three');
        $this->addSql('ALTER TABLE talent_three DROP template_id');
    }
}
