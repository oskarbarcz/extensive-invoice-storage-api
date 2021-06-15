<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210615202640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial invoices table';
    }

    public function up(Schema $schema): void
    {
        $sql = 'CREATE TABLE invoices (
                     id      BINARY(16)   NOT NULL COMMENT \'(DC2Type:uuid)\',
                     name    VARCHAR(255) NOT NULL,
                     file_id BINARY(16)   NOT NULL COMMENT \'(DC2Type:uuid)\',
                     type    VARCHAR(255) NOT NULL,
                     PRIMARY KEY(id)
                )
                DEFAULT CHARACTER SET utf8mb4 
                COLLATE `utf8mb4_unicode_ci` 
                ENGINE = InnoDB';

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = 'DROP TABLE invoices';
        $this->addSql($sql);
    }
}
