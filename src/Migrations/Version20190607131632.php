<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607131632 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, class_id INT NOT NULL, UNIQUE INDEX UNIQ_97601F83EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE talent_point (id INT AUTO_INCREMENT NOT NULL, three_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, points INT NOT NULL, three_lvl INT NOT NULL, INDEX IDX_41F5EDA377A10D65 (three_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE talent_three (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F83EA000B10 FOREIGN KEY (class_id) REFERENCES wow_class (id)');
        $this->addSql('ALTER TABLE talent_point ADD CONSTRAINT FK_41F5EDA377A10D65 FOREIGN KEY (three_id) REFERENCES talent_three (id)');
        $this->addSql('ALTER TABLE wow_class DROP is_active');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE talent_point DROP FOREIGN KEY FK_41F5EDA377A10D65');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE talent_point');
        $this->addSql('DROP TABLE talent_three');
        $this->addSql('ALTER TABLE wow_class ADD is_active TINYINT(1) NOT NULL');
    }
}
