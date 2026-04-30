<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430071513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_fichier (user_id INT NOT NULL, fichier_id INT NOT NULL, INDEX IDX_C37B4B15A76ED395 (user_id), INDEX IDX_C37B4B15F915CFE (fichier_id), PRIMARY KEY (user_id, fichier_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_fichier ADD CONSTRAINT FK_C37B4B15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_fichier ADD CONSTRAINT FK_C37B4B15F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_fichier DROP FOREIGN KEY FK_C37B4B15A76ED395');
        $this->addSql('ALTER TABLE user_fichier DROP FOREIGN KEY FK_C37B4B15F915CFE');
        $this->addSql('DROP TABLE user_fichier');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
