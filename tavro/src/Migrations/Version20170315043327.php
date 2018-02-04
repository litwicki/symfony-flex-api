<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315043327 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tavro_account_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tavro_account ADD account_type_id INT NOT NULL, ADD structure VARCHAR(500) DEFAULT NULL, ADD business_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tavro_account ADD CONSTRAINT FK_CD639B27C6798DB FOREIGN KEY (account_type_id) REFERENCES tavro_account_type (id)');
        $this->addSql('CREATE INDEX IDX_CD639B27C6798DB ON tavro_account (account_type_id)');
        $this->addSql('ALTER TABLE tavro_comment CHANGE user_id user_id INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tavro_account DROP FOREIGN KEY FK_CD639B27C6798DB');
        $this->addSql('DROP TABLE tavro_account_type');
        $this->addSql('DROP INDEX IDX_CD639B27C6798DB ON tavro_account');
        $this->addSql('ALTER TABLE tavro_account DROP account_type_id, DROP structure, DROP business_id');
        $this->addSql('ALTER TABLE tavro_comment CHANGE user_id user_id INT NOT NULL');
    }
}
