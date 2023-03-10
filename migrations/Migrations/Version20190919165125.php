<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919165125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE IF NOT EXISTS user_id_seq;');
        $this->addSql("CREATE TABLE IF NOT EXISTS \"user\" (id INT DEFAULT NEXTVAL ('user_id_seq') NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, CONSTRAINT UNIQ_8D93D649E7927C74 UNIQUE (email), PRIMARY KEY(id)) ");

        $this->addSql('CREATE SEQUENCE IF NOT EXISTS big_foot_sighting_id_seq;');
        $this->addSql("CREATE TABLE IF NOT EXISTS big_foot_sighting (id INT DEFAULT NEXTVAL ('big_foot_sighting_id_seq') NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, confidence_index INT NOT NULL, latitude NUMERIC(8, 6) NOT NULL, PRIMARY KEY(id))");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE big_foot_sighting');
    }
}
