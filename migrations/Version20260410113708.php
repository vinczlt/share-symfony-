<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410113708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_demande (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_7E99C9AF3AD8644E (user_source), INDEX IDX_7E99C9AF233D34C1 (user_target), PRIMARY KEY (user_source, user_target)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_demande ADD CONSTRAINT FK_7E99C9AF3AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_demande ADD CONSTRAINT FK_7E99C9AF233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_demande DROP FOREIGN KEY FK_7E99C9AF3AD8644E');
        $this->addSql('ALTER TABLE user_demande DROP FOREIGN KEY FK_7E99C9AF233D34C1');
        $this->addSql('DROP TABLE user_demande');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
