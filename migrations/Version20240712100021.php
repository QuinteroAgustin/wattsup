<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712100021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commission DROP FOREIGN KEY FK_1C650158F675F31B');
        $this->addSql('ALTER TABLE commission_user DROP FOREIGN KEY FK_C80211AB202D1EB2');
        $this->addSql('ALTER TABLE commission_user DROP FOREIGN KEY FK_C80211ABA76ED395');
        $this->addSql('DROP TABLE commission');
        $this->addSql('DROP TABLE commission_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commission (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, closed_at DATETIME DEFAULT NULL, is_closed TINYINT(1) NOT NULL, is_temp TINYINT(1) NOT NULL, INDEX IDX_1C650158F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commission_user (commission_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C80211AB202D1EB2 (commission_id), INDEX IDX_C80211ABA76ED395 (user_id), PRIMARY KEY(commission_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commission ADD CONSTRAINT FK_1C650158F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commission_user ADD CONSTRAINT FK_C80211AB202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commission_user ADD CONSTRAINT FK_C80211ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
