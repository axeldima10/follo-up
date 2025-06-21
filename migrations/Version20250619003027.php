<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619003027 extends AbstractMigration
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
            ALTER TABLE member ADD manager_id INT DEFAULT NULL, ADD admin_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_70E4FA78783E3463 ON member (manager_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_70E4FA78642B8210 ON member (admin_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD type VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78783E3463
        SQL);
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
            DROP TABLE consultant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE manager
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78642B8210
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_70E4FA78783E3463 ON member
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_70E4FA78642B8210 ON member
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP manager_id, DROP admin_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP type
        SQL);
    }
}
