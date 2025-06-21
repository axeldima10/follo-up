<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619030029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE member CHANGE member_joined_date member_joined_date DATE DEFAULT NULL, CHANGE baptism_date baptism_date DATE DEFAULT NULL, CHANGE transport_date transport_date DATE DEFAULT NULL, CHANGE home_cell_join_date home_cell_join_date DATE DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE member CHANGE member_joined_date member_joined_date DATE NOT NULL, CHANGE baptism_date baptism_date DATE NOT NULL, CHANGE transport_date transport_date DATE NOT NULL, CHANGE home_cell_join_date home_cell_join_date DATE NOT NULL
        SQL);
    }
}
