<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260119080424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD module_id INT NOT NULL, DROP title');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9AFC2B591 ON course (module_id)');
        $this->addSql('ALTER TABLE intervention_type CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE color color VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE module CHANGE code code VARCHAR(50) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE school_year CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teaching_block CHANGE code code VARCHAR(50) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP role, CHANGE email email VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9AFC2B591');
        $this->addSql('DROP INDEX IDX_169E6FB9AFC2B591 ON course');
        $this->addSql('ALTER TABLE course ADD title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, DROP module_id');
        $this->addSql('ALTER TABLE intervention_type CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE color color VARCHAR(20) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE module CHANGE code code VARCHAR(50) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE school_year CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE teaching_block CHANGE code code VARCHAR(50) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, DROP roles, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
    }
}
