<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181224191831 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE wow_class (id INT AUTO_INCREMENT NOT NULL, img VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build ADD wow_class_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE build ADD CONSTRAINT FK_BDA0F2DBDE7708D5 FOREIGN KEY (wow_class_id) REFERENCES wow_class (id)');
        $this->addSql('CREATE INDEX IDX_BDA0F2DBDE7708D5 ON build (wow_class_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE build DROP FOREIGN KEY FK_BDA0F2DBDE7708D5');
        $this->addSql('DROP TABLE wow_class');
        $this->addSql('DROP INDEX IDX_BDA0F2DBDE7708D5 ON build');
        $this->addSql('ALTER TABLE build DROP wow_class_id');
    }
}
