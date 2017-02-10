<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209045600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE tavro_account_billing_profile (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, customer_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_302F2069395C3F3 (customer_id), UNIQUE INDEX UNIQ_302F2069B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tavro_account_billing_profile ADD CONSTRAINT FK_302F2069B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE tavro_account_billing_profile');
    }
}
