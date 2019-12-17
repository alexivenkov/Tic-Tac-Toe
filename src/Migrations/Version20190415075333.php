<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415075333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE DATABASE IF NOT EXISTS `tic-tac-toe`');

        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, winner_id INT DEFAULT NULL, first_player_id INT NOT NULL, second_player_id INT NOT NULL, board_state JSON NOT NULL COMMENT \'(DC2Type:json_array)\', is_finished TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_232B318C5DFCD4B8 (winner_id), INDEX IDX_232B318C65EB6591 (first_player_id), INDEX IDX_232B318CA40D7457 (second_player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, unit VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C65EB6591 FOREIGN KEY (first_player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA40D7457 FOREIGN KEY (second_player_id) REFERENCES user (id)');

        $this->addSql('INSERT INTO user (unit) VALUES("X")');
        $this->addSql('INSERT INTO user (unit) VALUES("O")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5DFCD4B8');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C65EB6591');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CA40D7457');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE user');
    }
}
