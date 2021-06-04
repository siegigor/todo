<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604094046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_tasks (id UUID NOT NULL, parent_id UUID DEFAULT NULL, status VARCHAR(255) NOT NULL, priority INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, author_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97FC7EB7727ACA70 ON task_tasks (parent_id)');
        $this->addSql('CREATE INDEX IDX_97FC7EB78B8E8428 ON task_tasks (created_at)');
        $this->addSql('COMMENT ON COLUMN task_tasks.id IS \'(DC2Type:task_task_id)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.parent_id IS \'(DC2Type:task_task_id)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.status IS \'(DC2Type:task_task_status)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.priority IS \'(DC2Type:task_task_priority)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.author_id IS \'(DC2Type:task_task_author_id)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task_tasks.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_users (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, name_first VARCHAR(255) NOT NULL, name_last VARCHAR(255) NOT NULL, name_middle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS \'(DC2Type:user_user_id)\'');
        $this->addSql('COMMENT ON COLUMN user_users.email IS \'(DC2Type:user_user_email)\'');
        $this->addSql('ALTER TABLE task_tasks ADD CONSTRAINT FK_97FC7EB7727ACA70 FOREIGN KEY (parent_id) REFERENCES task_tasks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_tasks DROP CONSTRAINT FK_97FC7EB7727ACA70');
        $this->addSql('DROP TABLE task_tasks');
        $this->addSql('DROP TABLE user_users');
    }
}
