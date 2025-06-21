<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619183649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE consultant (id INT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_441282A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE manager (id INT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_FA2425B9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, quartier VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', is_member TINYINT(1) NOT NULL, member_joined_date DATE DEFAULT NULL, is_baptized TINYINT(1) NOT NULL, baptism_date DATE DEFAULT NULL, has_transport TINYINT(1) NOT NULL, transport_date DATE DEFAULT NULL, is_in_home_cell TINYINT(1) NOT NULL, home_cell_join_date DATE DEFAULT NULL, observations VARCHAR(255) DEFAULT NULL, INDEX IDX_70E4FA78B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE consultant ADD CONSTRAINT FK_441282A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE consultant ADD CONSTRAINT FK_441282A1BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE consultant DROP FOREIGN KEY FK_441282A1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE consultant DROP FOREIGN KEY FK_441282A1BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE consultant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE manager
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE member
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
