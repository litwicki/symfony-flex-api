<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161003023637 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tavro_account (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(500) NOT NULL, name_clean VARCHAR(500) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CD639B275E237E06 (name), UNIQUE INDEX UNIQ_CD639B273F96FF0C (name_clean), INDEX IDX_CD639B27A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_account_group (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(500) NOT NULL, name_clean VARCHAR(500) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EB63D6285E237E06 (name), UNIQUE INDEX UNIQ_EB63D6283F96FF0C (name_clean), INDEX IDX_EB63D6289B6B5FBA (account_id), INDEX IDX_EB63D628A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_account_group_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, account_group_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_D5237B8CA76ED395 (user_id), INDEX IDX_D5237B8C869A3BF1 (account_group_id), UNIQUE INDEX ACCOUNT_GROUP_USER (user_id, account_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_account_user (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, user_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_FDD3EF999B6B5FBA (account_id), INDEX IDX_FDD3EF99A76ED395 (user_id), UNIQUE INDEX account_user (account_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_24219FEFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_contact (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, organization_id INT NOT NULL, person_id INT NOT NULL, job_title VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_FC372BBBA76ED395 (user_id), INDEX IDX_FC372BBB32C8A3DE (organization_id), INDEX IDX_FC372BBB217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, expense_date DATETIME NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_9D6F40259B6B5FBA (account_id), INDEX IDX_9D6F402512469DE2 (category_id), INDEX IDX_9D6F4025A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_category (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_157160319B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, expense_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_C595F474F8697D13 (comment_id), INDEX IDX_C595F474F395DB7B (expense_id), INDEX NODE_COMMENT (comment_id, expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_expense_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, expense_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_C54FE424BAD26311 (tag_id), INDEX IDX_C54FE424F395DB7B (expense_id), INDEX EXPENSE_TAG (tag_id, expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_file (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, aws_url VARCHAR(500) NOT NULL, bucket VARCHAR(32) NOT NULL, directory VARCHAR(32) NOT NULL, aws_key VARCHAR(500) NOT NULL, original_filename VARCHAR(500) NOT NULL, mime_type VARCHAR(500) NOT NULL, filesize INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_A8C28DFF9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, prospectus VARCHAR(255) DEFAULT NULL, share_price DOUBLE PRECISION DEFAULT \'0\', total_shares INT DEFAULT 0, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_F2ADB75B9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, funding_round_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_9AD42CA2F8697D13 (comment_id), INDEX IDX_9AD42CA2D01758B0 (funding_round_id), INDEX FUNDING_ROUND_COMMENT (comment_id, funding_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_funding_round_shareholder (id INT AUTO_INCREMENT NOT NULL, shareholder_id INT NOT NULL, funding_round_id INT NOT NULL, shares INT DEFAULT 0, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_54BC254D9D59475 (shareholder_id), INDEX IDX_54BC254DD01758B0 (funding_round_id), INDEX FUNDING_ROUND_SHAREHOLDER (shareholder_id, funding_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_image (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, aws_url VARCHAR(500) NOT NULL, bucket VARCHAR(32) NOT NULL, directory VARCHAR(32) NOT NULL, aws_key VARCHAR(500) NOT NULL, original_filename VARCHAR(500) NOT NULL, mime_type VARCHAR(500) NOT NULL, filesize INT NOT NULL, height INT NOT NULL, width INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_F5ACA60D9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, display_date DATETIME DEFAULT NULL, views INT DEFAULT 0, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_A12253AA9B6B5FBA (account_id), INDEX IDX_A12253AAA76ED395 (user_id), INDEX NODE_TYPE (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_CEAFA964F8697D13 (comment_id), INDEX IDX_CEAFA964460D9FD7 (node_id), INDEX NODE_COMMENT (comment_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_read (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_AA880921A76ED395 (user_id), INDEX IDX_AA880921460D9FD7 (node_id), UNIQUE INDEX USER_NODE_READ (user_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_node_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, node_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_4AD12AFBAD26311 (tag_id), INDEX IDX_4AD12AF460D9FD7 (node_id), INDEX NODE_TAG (tag_id, node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_organization (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, hubspot_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(8000) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3D3DDD06B9DD01AC (hubspot_id), INDEX IDX_3D3DDD069B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_organization_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, organization_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_D12B057F8697D13 (comment_id), INDEX IDX_D12B05732C8A3DE (organization_id), INDEX ORGANIZATION_COMMENT (comment_id, organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_person (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, suffix VARCHAR(255) DEFAULT NULL, gender VARCHAR(32) DEFAULT NULL, birthday DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B189700CA76ED395 (user_id), INDEX USER (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, cost DOUBLE PRECISION DEFAULT \'0\', body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_631FC92E9B6B5FBA (account_id), INDEX IDX_631FC92E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product_category (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_18A0C85F9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_product_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, product_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_D44D349F3DA5256D (image_id), INDEX IDX_D44D349F4584665A (product_id), INDEX PRODUCT_IMAGE (image_id, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_5944A1069B6B5FBA (account_id), INDEX IDX_5944A10612469DE2 (category_id), INDEX IDX_5944A106A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_category (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_A6B4CA6C9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, revenue_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_6645878CF8697D13 (comment_id), INDEX IDX_6645878C224718EB (revenue_id), INDEX NODE_COMMENT (comment_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, revenue_id INT NOT NULL, qty INT DEFAULT 1, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_217BD14D4584665A (product_id), INDEX IDX_217BD14D224718EB (revenue_id), INDEX REVENUE_PRODUCT (product_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, revenue_id INT NOT NULL, qty INT DEFAULT 1, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_13AC4F32ED5CA9E6 (service_id), INDEX IDX_13AC4F32224718EB (revenue_id), INDEX REVENUE_SERVICE (service_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_revenue_tag (id INT AUTO_INCREMENT NOT NULL, tag_id INT NOT NULL, revenue_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_3591A5A3BAD26311 (tag_id), INDEX IDX_3591A5A3224718EB (revenue_id), INDEX REVENUE_TAG (tag_id, revenue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_733431855E237E06 (name), UNIQUE INDEX UNIQ_7334318557698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, type VARCHAR(255) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_51C857519B6B5FBA (account_id), INDEX IDX_51C8575112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service_category (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_2A66F9F59B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_service_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, service_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_DC63A2243DA5256D (image_id), INDEX IDX_DC63A224ED5CA9E6 (service_id), INDEX PRODUCT_IMAGE (image_id, service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_shareholder (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_BFC86E97217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(30) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user (id INT AUTO_INCREMENT NOT NULL, avatar_image_id INT DEFAULT NULL, person_id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, password_token VARCHAR(255) DEFAULT NULL, password_token_expire DATETIME DEFAULT NULL, salt VARCHAR(255) NOT NULL, signature VARCHAR(500) DEFAULT NULL, last_online_date DATETIME DEFAULT NULL, api_key VARCHAR(255) NOT NULL, api_password VARCHAR(255) NOT NULL, api_enabled TINYINT(1) NOT NULL, guid VARCHAR(255) NOT NULL, user_ip VARCHAR(255) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A9CE6DA6F85E0677 (username), INDEX IDX_A9CE6DA65C18B4B1 (avatar_image_id), UNIQUE INDEX UNIQ_A9CE6DA6217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_D8930373A76ED395 (user_id), INDEX IDX_D8930373D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavro_variable (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, name VARCHAR(100) NOT NULL, body VARCHAR(8000) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_B84C00DA9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tavro_account ADD CONSTRAINT FK_CD639B27A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_account_group ADD CONSTRAINT FK_EB63D6289B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_account_group ADD CONSTRAINT FK_EB63D628A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_account_group_user ADD CONSTRAINT FK_D5237B8CA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_account_group_user ADD CONSTRAINT FK_D5237B8C869A3BF1 FOREIGN KEY (account_group_id) REFERENCES tavro_account_group (id)');
        $this->addSql('ALTER TABLE tavro_account_user ADD CONSTRAINT FK_FDD3EF999B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_account_user ADD CONSTRAINT FK_FDD3EF99A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_comment ADD CONSTRAINT FK_24219FEFA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_contact ADD CONSTRAINT FK_FC372BBBA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_contact ADD CONSTRAINT FK_FC372BBB32C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_contact ADD CONSTRAINT FK_FC372BBB217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F40259B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F402512469DE2 FOREIGN KEY (category_id) REFERENCES tavro_expense_category (id)');
        $this->addSql('ALTER TABLE tavro_expense ADD CONSTRAINT FK_9D6F4025A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_expense_category ADD CONSTRAINT FK_157160319B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_expense_comment ADD CONSTRAINT FK_C595F474F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_expense_comment ADD CONSTRAINT FK_C595F474F395DB7B FOREIGN KEY (expense_id) REFERENCES tavro_expense (id)');
        $this->addSql('ALTER TABLE tavro_expense_tag ADD CONSTRAINT FK_C54FE424BAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_expense_tag ADD CONSTRAINT FK_C54FE424F395DB7B FOREIGN KEY (expense_id) REFERENCES tavro_expense (id)');
        $this->addSql('ALTER TABLE tavro_file ADD CONSTRAINT FK_A8C28DFF9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_funding_round ADD CONSTRAINT FK_F2ADB75B9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_comment ADD CONSTRAINT FK_9AD42CA2F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_comment ADD CONSTRAINT FK_9AD42CA2D01758B0 FOREIGN KEY (funding_round_id) REFERENCES tavro_funding_round (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder ADD CONSTRAINT FK_54BC254D9D59475 FOREIGN KEY (shareholder_id) REFERENCES tavro_shareholder (id)');
        $this->addSql('ALTER TABLE tavro_funding_round_shareholder ADD CONSTRAINT FK_54BC254DD01758B0 FOREIGN KEY (funding_round_id) REFERENCES tavro_funding_round (id)');
        $this->addSql('ALTER TABLE tavro_image ADD CONSTRAINT FK_F5ACA60D9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_node ADD CONSTRAINT FK_A12253AA9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_node ADD CONSTRAINT FK_A12253AAA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_node_comment ADD CONSTRAINT FK_CEAFA964F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_node_comment ADD CONSTRAINT FK_CEAFA964460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_node_read ADD CONSTRAINT FK_AA880921A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_node_read ADD CONSTRAINT FK_AA880921460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_node_tag ADD CONSTRAINT FK_4AD12AFBAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_node_tag ADD CONSTRAINT FK_4AD12AF460D9FD7 FOREIGN KEY (node_id) REFERENCES tavro_node (id)');
        $this->addSql('ALTER TABLE tavro_organization ADD CONSTRAINT FK_3D3DDD069B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_organization_comment ADD CONSTRAINT FK_D12B057F8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_organization_comment ADD CONSTRAINT FK_D12B05732C8A3DE FOREIGN KEY (organization_id) REFERENCES tavro_organization (id)');
        $this->addSql('ALTER TABLE tavro_person ADD CONSTRAINT FK_B189700CA76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_product ADD CONSTRAINT FK_631FC92E9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_product ADD CONSTRAINT FK_631FC92E12469DE2 FOREIGN KEY (category_id) REFERENCES tavro_product_category (id)');
        $this->addSql('ALTER TABLE tavro_product_category ADD CONSTRAINT FK_18A0C85F9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_product_image ADD CONSTRAINT FK_D44D349F3DA5256D FOREIGN KEY (image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_product_image ADD CONSTRAINT FK_D44D349F4584665A FOREIGN KEY (product_id) REFERENCES tavro_product (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A1069B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A10612469DE2 FOREIGN KEY (category_id) REFERENCES tavro_revenue_category (id)');
        $this->addSql('ALTER TABLE tavro_revenue ADD CONSTRAINT FK_5944A106A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_revenue_category ADD CONSTRAINT FK_A6B4CA6C9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_revenue_comment ADD CONSTRAINT FK_6645878CF8697D13 FOREIGN KEY (comment_id) REFERENCES tavro_comment (id)');
        $this->addSql('ALTER TABLE tavro_revenue_comment ADD CONSTRAINT FK_6645878C224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_product ADD CONSTRAINT FK_217BD14D4584665A FOREIGN KEY (product_id) REFERENCES tavro_product (id)');
        $this->addSql('ALTER TABLE tavro_revenue_product ADD CONSTRAINT FK_217BD14D224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_service ADD CONSTRAINT FK_13AC4F32ED5CA9E6 FOREIGN KEY (service_id) REFERENCES tavro_service (id)');
        $this->addSql('ALTER TABLE tavro_revenue_service ADD CONSTRAINT FK_13AC4F32224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_revenue_tag ADD CONSTRAINT FK_3591A5A3BAD26311 FOREIGN KEY (tag_id) REFERENCES tavro_tag (id)');
        $this->addSql('ALTER TABLE tavro_revenue_tag ADD CONSTRAINT FK_3591A5A3224718EB FOREIGN KEY (revenue_id) REFERENCES tavro_revenue (id)');
        $this->addSql('ALTER TABLE tavro_service ADD CONSTRAINT FK_51C857519B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_service ADD CONSTRAINT FK_51C8575112469DE2 FOREIGN KEY (category_id) REFERENCES tavro_service_category (id)');
        $this->addSql('ALTER TABLE tavro_service_category ADD CONSTRAINT FK_2A66F9F59B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
        $this->addSql('ALTER TABLE tavro_service_image ADD CONSTRAINT FK_DC63A2243DA5256D FOREIGN KEY (image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_service_image ADD CONSTRAINT FK_DC63A224ED5CA9E6 FOREIGN KEY (service_id) REFERENCES tavro_service (id)');
        $this->addSql('ALTER TABLE tavro_shareholder ADD CONSTRAINT FK_BFC86E97217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_user ADD CONSTRAINT FK_A9CE6DA65C18B4B1 FOREIGN KEY (avatar_image_id) REFERENCES tavro_image (id)');
        $this->addSql('ALTER TABLE tavro_user ADD CONSTRAINT FK_A9CE6DA6217BBB47 FOREIGN KEY (person_id) REFERENCES tavro_person (id)');
        $this->addSql('ALTER TABLE tavro_user_role ADD CONSTRAINT FK_D8930373A76ED395 FOREIGN KEY (user_id) REFERENCES tavro_user (id)');
        $this->addSql('ALTER TABLE tavro_user_role ADD CONSTRAINT FK_D8930373D60322AC FOREIGN KEY (role_id) REFERENCES tavro_role (id)');
        $this->addSql('ALTER TABLE tavro_variable ADD CONSTRAINT FK_B84C00DA9B6B5FBA FOREIGN KEY (account_id) REFERENCES tavro_account (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tavro_account_group DROP FOREIGN KEY FK_EB63D6289B6B5FBA');
        $this->addSql('ALTER TABLE tavro_account_user DROP FOREIGN KEY FK_FDD3EF999B6B5FBA');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F40259B6B5FBA');
        $this->addSql('ALTER TABLE tavro_expense_category DROP FOREIGN KEY FK_157160319B6B5FBA');
        $this->addSql('ALTER TABLE tavro_file DROP FOREIGN KEY FK_A8C28DFF9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_funding_round DROP FOREIGN KEY FK_F2ADB75B9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_image DROP FOREIGN KEY FK_F5ACA60D9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_node DROP FOREIGN KEY FK_A12253AA9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_organization DROP FOREIGN KEY FK_3D3DDD069B6B5FBA');
        $this->addSql('ALTER TABLE tavro_product DROP FOREIGN KEY FK_631FC92E9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_product_category DROP FOREIGN KEY FK_18A0C85F9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A1069B6B5FBA');
        $this->addSql('ALTER TABLE tavro_revenue_category DROP FOREIGN KEY FK_A6B4CA6C9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_service DROP FOREIGN KEY FK_51C857519B6B5FBA');
        $this->addSql('ALTER TABLE tavro_service_category DROP FOREIGN KEY FK_2A66F9F59B6B5FBA');
        $this->addSql('ALTER TABLE tavro_variable DROP FOREIGN KEY FK_B84C00DA9B6B5FBA');
        $this->addSql('ALTER TABLE tavro_account_group_user DROP FOREIGN KEY FK_D5237B8C869A3BF1');
        $this->addSql('ALTER TABLE tavro_expense_comment DROP FOREIGN KEY FK_C595F474F8697D13');
        $this->addSql('ALTER TABLE tavro_funding_round_comment DROP FOREIGN KEY FK_9AD42CA2F8697D13');
        $this->addSql('ALTER TABLE tavro_node_comment DROP FOREIGN KEY FK_CEAFA964F8697D13');
        $this->addSql('ALTER TABLE tavro_organization_comment DROP FOREIGN KEY FK_D12B057F8697D13');
        $this->addSql('ALTER TABLE tavro_revenue_comment DROP FOREIGN KEY FK_6645878CF8697D13');
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
        $this->addSql('ALTER TABLE tavro_contact DROP FOREIGN KEY FK_FC372BBB32C8A3DE');
        $this->addSql('ALTER TABLE tavro_organization_comment DROP FOREIGN KEY FK_D12B05732C8A3DE');
        $this->addSql('ALTER TABLE tavro_contact DROP FOREIGN KEY FK_FC372BBB217BBB47');
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
        $this->addSql('ALTER TABLE tavro_account DROP FOREIGN KEY FK_CD639B27A76ED395');
        $this->addSql('ALTER TABLE tavro_account_group DROP FOREIGN KEY FK_EB63D628A76ED395');
        $this->addSql('ALTER TABLE tavro_account_group_user DROP FOREIGN KEY FK_D5237B8CA76ED395');
        $this->addSql('ALTER TABLE tavro_account_user DROP FOREIGN KEY FK_FDD3EF99A76ED395');
        $this->addSql('ALTER TABLE tavro_comment DROP FOREIGN KEY FK_24219FEFA76ED395');
        $this->addSql('ALTER TABLE tavro_contact DROP FOREIGN KEY FK_FC372BBBA76ED395');
        $this->addSql('ALTER TABLE tavro_expense DROP FOREIGN KEY FK_9D6F4025A76ED395');
        $this->addSql('ALTER TABLE tavro_node DROP FOREIGN KEY FK_A12253AAA76ED395');
        $this->addSql('ALTER TABLE tavro_node_read DROP FOREIGN KEY FK_AA880921A76ED395');
        $this->addSql('ALTER TABLE tavro_person DROP FOREIGN KEY FK_B189700CA76ED395');
        $this->addSql('ALTER TABLE tavro_revenue DROP FOREIGN KEY FK_5944A106A76ED395');
        $this->addSql('ALTER TABLE tavro_user_role DROP FOREIGN KEY FK_D8930373A76ED395');
        $this->addSql('DROP TABLE tavro_account');
        $this->addSql('DROP TABLE tavro_account_group');
        $this->addSql('DROP TABLE tavro_account_group_user');
        $this->addSql('DROP TABLE tavro_account_user');
        $this->addSql('DROP TABLE tavro_comment');
        $this->addSql('DROP TABLE tavro_contact');
        $this->addSql('DROP TABLE tavro_expense');
        $this->addSql('DROP TABLE tavro_expense_category');
        $this->addSql('DROP TABLE tavro_expense_comment');
        $this->addSql('DROP TABLE tavro_expense_tag');
        $this->addSql('DROP TABLE tavro_file');
        $this->addSql('DROP TABLE tavro_funding_round');
        $this->addSql('DROP TABLE tavro_funding_round_comment');
        $this->addSql('DROP TABLE tavro_funding_round_shareholder');
        $this->addSql('DROP TABLE tavro_image');
        $this->addSql('DROP TABLE tavro_node');
        $this->addSql('DROP TABLE tavro_node_comment');
        $this->addSql('DROP TABLE tavro_node_read');
        $this->addSql('DROP TABLE tavro_node_tag');
        $this->addSql('DROP TABLE tavro_organization');
        $this->addSql('DROP TABLE tavro_organization_comment');
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
        $this->addSql('DROP TABLE tavro_variable');
    }
}
