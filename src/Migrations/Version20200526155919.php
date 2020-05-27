<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526155919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_group_id INT NOT NULL, INDEX IDX_8F02BF9D79F37AE5 (id_user_id), INDEX IDX_8F02BF9DAE8F35D2 (id_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, id_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5C93B3A4AE8F35D2 (id_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_project_id INT NOT NULL, timer TIME DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5058659779F37AE5 (id_user_id), INDEX IDX_50586597B3E79F4B (id_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DAE8F35D2 FOREIGN KEY (id_group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_5058659779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597B3E79F4B FOREIGN KEY (id_project_id) REFERENCES projects (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597B3E79F4B');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D79F37AE5');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_5058659779F37AE5');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DAE8F35D2');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4AE8F35D2');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE groups');
    }
}
