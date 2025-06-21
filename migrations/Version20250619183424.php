<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619183424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78642B8210
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78783E3463
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_70E4FA78642B8210 ON member
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_70E4FA78783E3463 ON member
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD created_by_id INT NOT NULL, DROP manager_id, DROP admin_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_70E4FA78B03A8386 ON member (created_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_70E4FA78B03A8386 ON member
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD manager_id INT DEFAULT NULL, ADD admin_id INT DEFAULT NULL, DROP created_by_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE member ADD CONSTRAINT FK_70E4FA78783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_70E4FA78642B8210 ON member (admin_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_70E4FA78783E3463 ON member (manager_id)
        SQL);
    }
}
