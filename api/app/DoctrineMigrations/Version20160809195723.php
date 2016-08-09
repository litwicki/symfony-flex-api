<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160809195723 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tavro_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_24219FEFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_customer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, person_id INT NOT NULL, organization_id INT NOT NULL, job_title VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_F538095EA76ED395 (user_id), INDEX IDX_F538095E217BBB47 (person_id), INDEX IDX_F538095E32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_customer_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, customer_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_5DF54DDBF8697D13 (comment_id), INDEX IDX_5DF54DDB9395C3F3 (customer_id), INDEX CUSTOMER_COMMENT (comment_id, customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, organization_id INT NOT NULL, customer_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, expense_date DATETIME NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_9D6F402512469DE2 (category_id), INDEX IDX_9D6F4025A76ED395 (user_id), INDEX IDX_9D6F402532C8A3DE (organization_id), INDEX IDX_9D6F40259395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_category (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_1571603132C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, expense_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_C595F474F8697D13 (comment_id), INDEX IDX_C595F474F395DB7B (expense_id), INDEX NODE_COMMENT (comment_id, expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, expense_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_C54FE424BAD26311 (tag_id), INDEX IDX_C54FE424F395DB7B (expense_id), INDEX EXPENSE_TAG (tag_id, expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, prospectus VARCHAR(255) DEFAULT NULL, share_price DOUBLE PRECISION DEFAULT \'0\', total_shares INT DEFAULT 0, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_F2ADB75B32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, funding_round_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_9AD42CA2F8697D13 (comment_id), INDEX IDX_9AD42CA2D01758B0 (funding_round_id), INDEX FUNDING_ROUND_COMMENT (comment_id, funding_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round_shareholder (id INT AUTO_INCREMENT NOT NULL, shareholder_id INT NOT NULL, funding_round_id INT NOT NULL, organization_id INT NOT NULL, shares INT DEFAULT 0, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_54BC254D9D59475 (shareholder_id), INDEX IDX_54BC254DD01758B0 (funding_round_id), INDEX IDX_54BC254D32C8A3DE (organization_id), INDEX ORGANIZATION_SHAREHOLDER (shareholder_id, funding_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_image (id INT AUTO_INCREMENT NOT NULL, aws_url VARCHAR(500) NOT NULL, bucket VARCHAR(32) NOT NULL, directory VARCHAR(32) NOT NULL, aws_key VARCHAR(500) NOT NULL, original_filename VARCHAR(500) NOT NULL, mime_type VARCHAR(500) NOT NULL, filesize INT NOT NULL, height INT NOT NULL, width INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, organization_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, display_date DATETIME DEFAULT NULL, views INT DEFAULT 0, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_A12253AAA76ED395 (user_id), INDEX IDX_A12253AA32C8A3DE (organization_id), INDEX NODE_TYPE (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_CEAFA964F8697D13 (comment_id), INDEX IDX_CEAFA964460D9FD7 (node_id), INDEX NODE_COMMENT (comment_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_read (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_AA880921A76ED395 (user_id), INDEX IDX_AA880921460D9FD7 (node_id), INDEX USER_NODE_READ (user_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_4AD12AFBAD26311 (tag_id), INDEX IDX_4AD12AF460D9FD7 (node_id), INDEX NODE_TAG (tag_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_organization (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(500) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_3D3DDD06A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_person (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, suffix VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B189700CE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, organization_id INT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, cost DOUBLE PRECISION DEFAULT \'0\', body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_631FC92E12469DE2 (category_id), INDEX IDX_631FC92E32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product_category (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_18A0C85F32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, product_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_D44D349F3DA5256D (image_id), INDEX IDX_D44D349F4584665A (product_id), INDEX PRODUCT_IMAGE (image_id, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, customer_id INT DEFAULT NULL, organization_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_5944A10612469DE2 (category_id), INDEX IDX_5944A106A76ED395 (user_id), INDEX IDX_5944A1069395C3F3 (customer_id), INDEX IDX_5944A10632C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_category (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_A6B4CA6C32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, revenue_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_6645878CF8697D13 (comment_id), INDEX IDX_6645878C224718EB (revenue_id), INDEX NODE_COMMENT (comment_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, revenue_id INT NOT NULL, qty INT DEFAULT 1, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_217BD14D4584665A (product_id), INDEX IDX_217BD14D224718EB (revenue_id), INDEX REVENUE_PRODUCT (product_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, revenue_id INT NOT NULL, qty INT DEFAULT 1, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_13AC4F32ED5CA9E6 (service_id), INDEX IDX_13AC4F32224718EB (revenue_id), INDEX REVENUE_SERVICE (service_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, revenue_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_3591A5A3BAD26311 (tag_id), INDEX IDX_3591A5A3224718EB (revenue_id), INDEX REVENUE_TAG (tag_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_733431855E237E06 (name), UNIQUE INDEX UNIQ_7334318557698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, type VARCHAR(255) NOT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_51C8575112469DE2 (category_id), INDEX IDX_51C8575132C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service_category (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_2A66F9F532C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, service_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_DC63A2243DA5256D (image_id), INDEX IDX_DC63A224ED5CA9E6 (service_id), INDEX PRODUCT_IMAGE (image_id, service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_shareholder (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, user_id INT DEFAULT NULL, notes VARCHAR(8000) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_BFC86E97217BBB47 (person_id), INDEX IDX_BFC86E97A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_tag (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, tag VARCHAR(500) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_EA91FE7032C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user (id INT AUTO_INCREMENT NOT NULL, avatar_image_id INT DEFAULT NULL, user_quickbooks_id INT DEFAULT NULL, person_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, password_token VARCHAR(255) DEFAULT NULL, password_token_expire DATETIME DEFAULT NULL, salt VARCHAR(255) NOT NULL, signature VARCHAR(500) DEFAULT NULL, last_online_date DATETIME DEFAULT NULL, api_key VARCHAR(255) NOT NULL, api_password VARCHAR(255) NOT NULL, api_enabled TINYINT(1) NOT NULL, guid VARCHAR(255) NOT NULL, user_ip VARCHAR(255) DEFAULT NULL, gender VARCHAR(32) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A9CE6DA6F85E0677 (username), INDEX IDX_A9CE6DA65C18B4B1 (avatar_image_id), UNIQUE INDEX UNIQ_A9CE6DA6EDF4C3C9 (user_quickbooks_id), INDEX IDX_A9CE6DA6217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_D8930373A76ED395 (user_id), INDEX IDX_D8930373D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user_freshbooks (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, access_token VARCHAR(255) NOT NULL, access_token_secret VARCHAR(255) NOT NULL, consumer_key VARCHAR(255) NOT NULL, consumer_secret VARCHAR(255) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_57AEE80DB6A2DD68 (access_token), UNIQUE INDEX UNIQ_57AEE80D8196DFA5 (access_token_secret), UNIQUE INDEX UNIQ_57AEE80DD2702362 (consumer_key), UNIQUE INDEX UNIQ_57AEE80D92086127 (consumer_secret), UNIQUE INDEX UNIQ_57AEE80DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user_organization (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, user_id INT NOT NULL, org_title VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_382BFB6132C8A3DE (organization_id), INDEX IDX_382BFB61A76ED395 (user_id), INDEX USER_ORGANIZATION (organization_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user_quickbooks (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, access_token VARCHAR(255) NOT NULL, access_token_secret VARCHAR(255) NOT NULL, consumer_key VARCHAR(255) NOT NULL, consumer_secret VARCHAR(255) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4058E47AB6A2DD68 (access_token), UNIQUE INDEX UNIQ_4058E47A8196DFA5 (access_token_secret), UNIQUE INDEX UNIQ_4058E47AD2702362 (consumer_key), UNIQUE INDEX UNIQ_4058E47A92086127 (consumer_secret), UNIQUE INDEX UNIQ_4058E47AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_variable (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, name VARCHAR(100) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_B84C00DA32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tavro_comment ADD CONSTRAINT FK_24219FEFA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_customer ADD CONSTRAINT FK_F538095EA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_customer ADD CONSTRAINT FK_F538095E217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_customer ADD CONSTRAINT FK_F538095E32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_customer_comment ADD CONSTRAINT FK_5DF54DDBF8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_customer_comment ADD CONSTRAINT FK_5DF54DDB9395C3F3 FOREIGN KEY (customer_id) REFERENCES tavro_customer (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F402512469DE2 FOREIGN KEY (category_id) REFERENCES tavro_expense_category (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F4025A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F402532C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F40259395C3F3 FOREIGN KEY (customer_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_expense_category ADD CONSTRAINT FK_1571603132C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_expense_comment ADD CONSTRAINT FK_C595F474F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_expense_comment ADD CONSTRAINT FK_C595F474F395DB7B FOREIGN KEY (expense_id) REFERENCES tavro_expense (id)');
        $this->addSql('ALTER TABLE tavro_expense_tag ADD CONSTRAINT FK_C54FE424BAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_expense_tag ADD CONSTRAINT FK_C54FE424F395DB7B FOREIGN KEY (expense_id) REFERENCES tavro_expense (id)');
        $this->addSql('ALTER TABLE tavro_funding_round ADD CONSTRAINT FK_F2ADB75B32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_comment ADD CONSTRAINT FK_9AD42CA2F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_comment ADD CONSTRAINT FK_9AD42CA2D01758B0 FOREIGN KEY (funding_round_id) REFERENCES tavro_funding_round (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder ADD CONSTRAINT FK_54BC254D9D59475 FOREIGN KEY (shareholder_id) REFERENCES tavro_shareholder (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder ADD CONSTRAINT FK_54BC254DD01758B0 FOREIGN KEY (funding_round_id) REFERENCES tavro_funding_round (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder ADD CONSTRAINT FK_54BC254D32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_node ADD CONSTRAINT FK_A12253AAA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_node ADD CONSTRAINT FK_A12253AA32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_node_comment ADD CONSTRAINT FK_CEAFA964F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_node_comment ADD CONSTRAINT FK_CEAFA964460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_node_read ADD CONSTRAINT FK_AA880921A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_node_read ADD CONSTRAINT FK_AA880921460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_node_tag ADD CONSTRAINT FK_4AD12AFBAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_node_tag ADD CONSTRAINT FK_4AD12AF460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_organization ADD CONSTRAINT FK_3D3DDD06A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_product ADD CONSTRAINT FK_631FC92E12469DE2 FOREIGN KEY (category_id) REFERENCES tavro_product_category (id)');
        $this->addSql('ALTER TABLE tavro_product ADD CONSTRAINT FK_631FC92E32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_product_category ADD CONSTRAINT FK_18A0C85F32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_product_image ADD CONSTRAINT FK_D44D349F3DA5256D FOREIGN KEY (image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_product_image ADD CONSTRAINT FK_D44D349F4584665A FOREIGN KEY (product_id) REFERENCES tavro_product (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A10612469DE2 FOREIGN KEY (category_id) REFERENCES tavro_revenue_category (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A106A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A1069395C3F3 FOREIGN KEY (customer_id) REFERENCES tavro_customer (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A10632C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_revenue_category ADD CONSTRAINT FK_A6B4CA6C32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_revenue_comment ADD CONSTRAINT FK_6645878CF8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_revenue_comment ADD CONSTRAINT FK_6645878C224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_product ADD CONSTRAINT FK_217BD14D4584665A FOREIGN KEY (product_id) REFERENCES tavro_product (id)');
        $this->addSql('ALTER TABLE tavro_revenue_product ADD CONSTRAINT FK_217BD14D224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_service ADD CONSTRAINT FK_13AC4F32ED5CA9E6 FOREIGN KEY (service_id) REFERENCES tavro_service (id)');
        $this->addSql('ALTER TABLE tavro_revenue_service ADD CONSTRAINT FK_13AC4F32224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_tag ADD CONSTRAINT FK_3591A5A3BAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_revenue_tag ADD CONSTRAINT FK_3591A5A3224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_service ADD CONSTRAINT FK_51C8575112469DE2 FOREIGN KEY (category_id) REFERENCES tavro_service_category (id)');
        $this->addSql('ALTER TABLE tavro_service ADD CONSTRAINT FK_51C8575132C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_service_category ADD CONSTRAINT FK_2A66F9F532C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_service_image ADD CONSTRAINT FK_DC63A2243DA5256D FOREIGN KEY (image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_service_image ADD CONSTRAINT FK_DC63A224ED5CA9E6 FOREIGN KEY (service_id) REFERENCES tavro_service (id)');
        $this->addSql('ALTER TABLE tavro_shareholder ADD CONSTRAINT FK_BFC86E97217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_shareholder ADD CONSTRAINT FK_BFC86E97A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_tag ADD CONSTRAINT FK_EA91FE7032C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_user ADD CONSTRAINT FK_A9CE6DA65C18B4B1 FOREIGN KEY (avatar_image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_user ADD CONSTRAINT FK_A9CE6DA6EDF4C3C9 FOREIGN KEY (user_quickbooks_id) REFERENCES tavro_user_quickbooks (id)');
        $this->addSql('ALTER TABLE tavro_user ADD CONSTRAINT FK_A9CE6DA6217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_user_role ADD CONSTRAINT FK_D8930373A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_user_role ADD CONSTRAINT FK_D8930373D60322AC FOREIGN KEY (role_id) REFERENCES tavro_role (id)');
        $this->addSql('ALTER TABLE tavro_user_freshbooks ADD CONSTRAINT FK_57AEE80DA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_user_organization ADD CONSTRAINT FK_382BFB6132C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_user_organization ADD CONSTRAINT FK_382BFB61A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_user_quickbooks ADD CONSTRAINT FK_4058E47AA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_variable ADD CONSTRAINT FK_B84C00DA32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tavro_customer_comment DROP FOREIGN KEY FK_5DF54DDBF8697D13');
        $this->addSql('ALTER TABLE tavro_expense_comment DROP FOREIGN KEY FK_C595F474F8697D13');
        $this->addSql('ALTER TABLE tavro_funding_round_comment DROP FOREIGN KEY FK_9AD42CA2F8697D13');
        $this->addSql('ALTER TABLE tavro_node_comment DROP FOREIGN KEY FK_CEAFA964F8697D13');
        $this->addSql('ALTER TABLE tavro_revenue_comment DROP FOREIGN KEY FK_6645878CF8697D13');
        $this->addSql('ALTER TABLE tavro_customer_comment DROP FOREIGN KEY FK_5DF54DDB9395C3F3');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A1069395C3F3');
        $this->addSql('ALTER TABLE tavro_expense_comment DROP FOREIGN KEY FK_C595F474F395DB7B');
        $this->addSql('ALTER TABLE tavro_expense_tag DROP FOREIGN KEY FK_C54FE424F395DB7B');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F402512469DE2');
        $this->addSql('ALTER TABLE tavro_funding_round_comment DROP FOREIGN KEY FK_9AD42CA2D01758B0');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder DROP FOREIGN KEY FK_54BC254DD01758B0');
        $this->addSql('ALTER TABLE tavro_product_image DROP FOREIGN KEY FK_D44D349F3DA5256D');
        $this->addSql('ALTER TABLE tavro_service_image DROP FOREIGN KEY FK_DC63A2243DA5256D');
        $this->addSql('ALTER TABLE tavro_user DROP FOREIGN KEY FK_A9CE6DA65C18B4B1');
        $this->addSql('ALTER TABLE tavro_node_comment DROP FOREIGN KEY FK_CEAFA964460D9FD7');
        $this->addSql('ALTER TABLE tavro_node_read DROP FOREIGN KEY FK_AA880921460D9FD7');
        $this->addSql('ALTER TABLE tavro_node_tag DROP FOREIGN KEY FK_4AD12AF460D9FD7');
        $this->addSql('ALTER TABLE tavro_customer DROP FOREIGN KEY FK_F538095E32C8A3DE');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F402532C8A3DE');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F40259395C3F3');
        $this->addSql('ALTER TABLE tavro_expense_category DROP FOREIGN KEY FK_1571603132C8A3DE');
        $this->addSql('ALTER TABLE tavro_funding_round DROP FOREIGN KEY FK_F2ADB75B32C8A3DE');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder DROP FOREIGN KEY FK_54BC254D32C8A3DE');
        $this->addSql('ALTER TABLE tavro_node DROP FOREIGN KEY FK_A12253AA32C8A3DE');
        $this->addSql('ALTER TABLE tavro_product DROP FOREIGN KEY FK_631FC92E32C8A3DE');
        $this->addSql('ALTER TABLE tavro_product_category DROP FOREIGN KEY FK_18A0C85F32C8A3DE');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A10632C8A3DE');
        $this->addSql('ALTER TABLE tavro_revenue_category DROP FOREIGN KEY FK_A6B4CA6C32C8A3DE');
        $this->addSql('ALTER TABLE tavro_service DROP FOREIGN KEY FK_51C8575132C8A3DE');
        $this->addSql('ALTER TABLE tavro_service_category DROP FOREIGN KEY FK_2A66F9F532C8A3DE');
        $this->addSql('ALTER TABLE tavro_tag DROP FOREIGN KEY FK_EA91FE7032C8A3DE');
        $this->addSql('ALTER TABLE tavro_user_organization DROP FOREIGN KEY FK_382BFB6132C8A3DE');
        $this->addSql('ALTER TABLE tavro_variable DROP FOREIGN KEY FK_B84C00DA32C8A3DE');
        $this->addSql('ALTER TABLE tavro_customer DROP FOREIGN KEY FK_F538095E217BBB47');
        $this->addSql('ALTER TABLE tavro_shareholder DROP FOREIGN KEY FK_BFC86E97217BBB47');
        $this->addSql('ALTER TABLE tavro_user DROP FOREIGN KEY FK_A9CE6DA6217BBB47');
        $this->addSql('ALTER TABLE tavro_product_image DROP FOREIGN KEY FK_D44D349F4584665A');
        $this->addSql('ALTER TABLE tavro_revenue_product DROP FOREIGN KEY FK_217BD14D4584665A');
        $this->addSql('ALTER TABLE tavro_product DROP FOREIGN KEY FK_631FC92E12469DE2');
        $this->addSql('ALTER TABLE tavro_revenue_comment DROP FOREIGN KEY FK_6645878C224718EB');
        $this->addSql('ALTER TABLE tavro_revenue_product DROP FOREIGN KEY FK_217BD14D224718EB');
        $this->addSql('ALTER TABLE tavro_revenue_service DROP FOREIGN KEY FK_13AC4F32224718EB');
        $this->addSql('ALTER TABLE tavro_revenue_tag DROP FOREIGN KEY FK_3591A5A3224718EB');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A10612469DE2');
        $this->addSql('ALTER TABLE tavro_user_role DROP FOREIGN KEY FK_D8930373D60322AC');
        $this->addSql('ALTER TABLE tavro_revenue_service DROP FOREIGN KEY FK_13AC4F32ED5CA9E6');
        $this->addSql('ALTER TABLE tavro_service_image DROP FOREIGN KEY FK_DC63A224ED5CA9E6');
        $this->addSql('ALTER TABLE tavro_service DROP FOREIGN KEY FK_51C8575112469DE2');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder DROP FOREIGN KEY FK_54BC254D9D59475');
        $this->addSql('ALTER TABLE tavro_expense_tag DROP FOREIGN KEY FK_C54FE424BAD26311');
        $this->addSql('ALTER TABLE tavro_node_tag DROP FOREIGN KEY FK_4AD12AFBAD26311');
        $this->addSql('ALTER TABLE tavro_revenue_tag DROP FOREIGN KEY FK_3591A5A3BAD26311');
        $this->addSql('ALTER TABLE tavro_comment DROP FOREIGN KEY FK_24219FEFA76ED395');
        $this->addSql('ALTER TABLE tavro_customer DROP FOREIGN KEY FK_F538095EA76ED395');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F4025A76ED395');
        $this->addSql('ALTER TABLE tavro_node DROP FOREIGN KEY FK_A12253AAA76ED395');
        $this->addSql('ALTER TABLE tavro_node_read DROP FOREIGN KEY FK_AA880921A76ED395');
        $this->addSql('ALTER TABLE tavro_organization DROP FOREIGN KEY FK_3D3DDD06A76ED395');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A106A76ED395');
        $this->addSql('ALTER TABLE tavro_shareholder DROP FOREIGN KEY FK_BFC86E97A76ED395');
        $this->addSql('ALTER TABLE tavro_user_role DROP FOREIGN KEY FK_D8930373A76ED395');
        $this->addSql('ALTER TABLE tavro_user_freshbooks DROP FOREIGN KEY FK_57AEE80DA76ED395');
        $this->addSql('ALTER TABLE tavro_user_organization DROP FOREIGN KEY FK_382BFB61A76ED395');
        $this->addSql('ALTER TABLE tavro_user_quickbooks DROP FOREIGN KEY FK_4058E47AA76ED395');
        $this->addSql('ALTER TABLE tavro_user DROP FOREIGN KEY FK_A9CE6DA6EDF4C3C9');
        $this->addSql('DROP TABLE tavro_comment');
        $this->addSql('DROP TABLE tavro_customer');
        $this->addSql('DROP TABLE tavro_customer_comment');
        $this->addSql('DROP TABLE tavro_expense');
        $this->addSql('DROP TABLE tavro_expense_category');
        $this->addSql('DROP TABLE tavro_expense_comment');
        $this->addSql('DROP TABLE tavro_expense_tag');
        $this->addSql('DROP TABLE tavro_funding_round');
        $this->addSql('DROP TABLE tavro_funding_round_comment');
        $this->addSql('DROP TABLE tavro_funding_round_shareholder');
        $this->addSql('DROP TABLE tavro_image');
        $this->addSql('DROP TABLE tavro_node');
        $this->addSql('DROP TABLE tavro_node_comment');
        $this->addSql('DROP TABLE tavro_node_read');
        $this->addSql('DROP TABLE tavro_node_tag');
        $this->addSql('DROP TABLE tavro_organization');
        $this->addSql('DROP TABLE tavro_person');
        $this->addSql('DROP TABLE tavro_product');
        $this->addSql('DROP TABLE tavro_product_category');
        $this->addSql('DROP TABLE tavro_product_image');
        $this->addSql('DROP TABLE tavro_revenue');
        $this->addSql('DROP TABLE tavro_revenue_category');
        $this->addSql('DROP TABLE tavro_revenue_comment');
        $this->addSql('DROP TABLE tavro_revenue_product');
        $this->addSql('DROP TABLE tavro_revenue_service');
        $this->addSql('DROP TABLE tavro_revenue_tag');
        $this->addSql('DROP TABLE tavro_role');
        $this->addSql('DROP TABLE tavro_service');
        $this->addSql('DROP TABLE tavro_service_category');
        $this->addSql('DROP TABLE tavro_service_image');
        $this->addSql('DROP TABLE tavro_shareholder');
        $this->addSql('DROP TABLE tavro_tag');
        $this->addSql('DROP TABLE tavro_user');
        $this->addSql('DROP TABLE tavro_user_role');
        $this->addSql('DROP TABLE tavro_user_freshbooks');
        $this->addSql('DROP TABLE tavro_user_organization');
        $this->addSql('DROP TABLE tavro_user_quickbooks');
        $this->addSql('DROP TABLE tavro_variable');
    }
}
