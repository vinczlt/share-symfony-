<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318130701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, nom_original VARCHAR(255) NOT NULL, nom_serveur VARCHAR(255) NOT NULL, date_envoi DATETIME NOT NULL, extension VARCHAR(5) NOT NULL, taille INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9B76551FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551FA76ED395');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
