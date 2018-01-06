<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170316034827 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tavro_comment CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE tavro_revenue ADD account_id INT NOT NULL');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A1069B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('CREATE INDEX IDX_5944A1069B6B5FBA ON tavro_revenue (account_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tavro_comment CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A1069B6B5FBA');
        $this->addSql('DROP INDEX IDX_5944A1069B6B5FBA ON tavro_revenue');
        $this->addSql('ALTER TABLE tavro_revenue DROP account_id');
    }
}
