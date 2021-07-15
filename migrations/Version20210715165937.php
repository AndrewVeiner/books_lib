<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715165937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_2DFDA3CB7DD8AC20');
        $this->addSql('DROP INDEX IDX_2DFDA3CB6DE2013A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__authors_books AS SELECT authors_id, books_id FROM authors_books');
        $this->addSql('DROP TABLE authors_books');
        $this->addSql('CREATE TABLE authors_books (authors_id INTEGER NOT NULL, books_id INTEGER NOT NULL, PRIMARY KEY(authors_id, books_id), CONSTRAINT FK_2DFDA3CB6DE2013A FOREIGN KEY (authors_id) REFERENCES authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2DFDA3CB7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO authors_books (authors_id, books_id) SELECT authors_id, books_id FROM __temp__authors_books');
        $this->addSql('DROP TABLE __temp__authors_books');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB7DD8AC20 ON authors_books (books_id)');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB6DE2013A ON authors_books (authors_id)');
        $this->addSql('DROP INDEX IDX_B3C77447FE54D947');
        $this->addSql('DROP INDEX IDX_B3C77447A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fos_user_user_group AS SELECT user_id, group_id FROM fos_user_user_group');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INTEGER UNSIGNED NOT NULL, group_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(user_id, group_id), CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO fos_user_user_group (user_id, group_id) SELECT user_id, group_id FROM __temp__fos_user_user_group');
        $this->addSql('DROP TABLE __temp__fos_user_user_group');
        $this->addSql('CREATE INDEX IDX_B3C77447FE54D947 ON fos_user_user_group (group_id)');
        $this->addSql('CREATE INDEX IDX_B3C77447A76ED395 ON fos_user_user_group (user_id)');
        $this->addSql('DROP INDEX IDX_9407E54977FA751A');
        $this->addSql('DROP INDEX UNIQ_9407E5494B12AD6EA000B10');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_object_identities AS SELECT id, parent_object_identity_id, class_id, object_identifier, entries_inheriting FROM acl_object_identities');
        $this->addSql('DROP TABLE acl_object_identities');
        $this->addSql('CREATE TABLE acl_object_identities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_object_identity_id INTEGER UNSIGNED DEFAULT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL COLLATE BINARY, entries_inheriting BOOLEAN NOT NULL, CONSTRAINT FK_9407E54977FA751A FOREIGN KEY (parent_object_identity_id) REFERENCES acl_object_identities (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO acl_object_identities (id, parent_object_identity_id, class_id, object_identifier, entries_inheriting) SELECT id, parent_object_identity_id, class_id, object_identifier, entries_inheriting FROM __temp__acl_object_identities');
        $this->addSql('DROP TABLE __temp__acl_object_identities');
        $this->addSql('CREATE INDEX IDX_9407E54977FA751A ON acl_object_identities (parent_object_identity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 ON acl_object_identities (object_identifier, class_id)');
        $this->addSql('DROP INDEX IDX_825DE299C671CEA1');
        $this->addSql('DROP INDEX IDX_825DE2993D9AB4A6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_object_identity_ancestors AS SELECT object_identity_id, ancestor_id FROM acl_object_identity_ancestors');
        $this->addSql('DROP TABLE acl_object_identity_ancestors');
        $this->addSql('CREATE TABLE acl_object_identity_ancestors (object_identity_id INTEGER UNSIGNED NOT NULL, ancestor_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(object_identity_id, ancestor_id), CONSTRAINT FK_825DE2993D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_825DE299C671CEA1 FOREIGN KEY (ancestor_id) REFERENCES acl_object_identities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO acl_object_identity_ancestors (object_identity_id, ancestor_id) SELECT object_identity_id, ancestor_id FROM __temp__acl_object_identity_ancestors');
        $this->addSql('DROP TABLE __temp__acl_object_identity_ancestors');
        $this->addSql('CREATE INDEX IDX_825DE299C671CEA1 ON acl_object_identity_ancestors (ancestor_id)');
        $this->addSql('CREATE INDEX IDX_825DE2993D9AB4A6 ON acl_object_identity_ancestors (object_identity_id)');
        $this->addSql('DROP INDEX IDX_46C8B806DF9183C9');
        $this->addSql('DROP INDEX IDX_46C8B8063D9AB4A6');
        $this->addSql('DROP INDEX IDX_46C8B806EA000B10');
        $this->addSql('DROP INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9');
        $this->addSql('DROP INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_entries AS SELECT id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure FROM acl_entries');
        $this->addSql('DROP TABLE acl_entries');
        $this->addSql('CREATE TABLE acl_entries (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identity_id INTEGER UNSIGNED DEFAULT NULL, security_identity_id INTEGER UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL COLLATE BINARY, ace_order SMALLINT UNSIGNED NOT NULL, mask INTEGER NOT NULL, granting BOOLEAN NOT NULL, granting_strategy VARCHAR(30) NOT NULL COLLATE BINARY, audit_success BOOLEAN NOT NULL, audit_failure BOOLEAN NOT NULL, CONSTRAINT FK_46C8B806EA000B10 FOREIGN KEY (class_id) REFERENCES acl_classes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46C8B8063D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46C8B806DF9183C9 FOREIGN KEY (security_identity_id) REFERENCES acl_security_identities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO acl_entries (id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure) SELECT id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure FROM __temp__acl_entries');
        $this->addSql('DROP TABLE __temp__acl_entries');
        $this->addSql('CREATE INDEX IDX_46C8B806DF9183C9 ON acl_entries (security_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B8063D9AB4A6 ON acl_entries (object_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B10 ON acl_entries (class_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 ON acl_entries (class_id, object_identity_id, security_identity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 ON acl_entries (class_id, object_identity_id, field_name, ace_order)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4');
        $this->addSql('DROP INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9');
        $this->addSql('DROP INDEX IDX_46C8B806EA000B10');
        $this->addSql('DROP INDEX IDX_46C8B8063D9AB4A6');
        $this->addSql('DROP INDEX IDX_46C8B806DF9183C9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_entries AS SELECT id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure FROM acl_entries');
        $this->addSql('DROP TABLE acl_entries');
        $this->addSql('CREATE TABLE acl_entries (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identity_id INTEGER UNSIGNED DEFAULT NULL, security_identity_id INTEGER UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL, ace_order SMALLINT UNSIGNED NOT NULL, mask INTEGER NOT NULL, granting BOOLEAN NOT NULL, granting_strategy VARCHAR(30) NOT NULL, audit_success BOOLEAN NOT NULL, audit_failure BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO acl_entries (id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure) SELECT id, class_id, object_identity_id, security_identity_id, field_name, ace_order, mask, granting, granting_strategy, audit_success, audit_failure FROM __temp__acl_entries');
        $this->addSql('DROP TABLE __temp__acl_entries');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 ON acl_entries (class_id, object_identity_id, field_name, ace_order)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 ON acl_entries (class_id, object_identity_id, security_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B10 ON acl_entries (class_id)');
        $this->addSql('CREATE INDEX IDX_46C8B8063D9AB4A6 ON acl_entries (object_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806DF9183C9 ON acl_entries (security_identity_id)');
        $this->addSql('DROP INDEX UNIQ_9407E5494B12AD6EA000B10');
        $this->addSql('DROP INDEX IDX_9407E54977FA751A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_object_identities AS SELECT id, parent_object_identity_id, class_id, object_identifier, entries_inheriting FROM acl_object_identities');
        $this->addSql('DROP TABLE acl_object_identities');
        $this->addSql('CREATE TABLE acl_object_identities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_object_identity_id INTEGER UNSIGNED DEFAULT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL, entries_inheriting BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO acl_object_identities (id, parent_object_identity_id, class_id, object_identifier, entries_inheriting) SELECT id, parent_object_identity_id, class_id, object_identifier, entries_inheriting FROM __temp__acl_object_identities');
        $this->addSql('DROP TABLE __temp__acl_object_identities');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 ON acl_object_identities (object_identifier, class_id)');
        $this->addSql('CREATE INDEX IDX_9407E54977FA751A ON acl_object_identities (parent_object_identity_id)');
        $this->addSql('DROP INDEX IDX_825DE2993D9AB4A6');
        $this->addSql('DROP INDEX IDX_825DE299C671CEA1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__acl_object_identity_ancestors AS SELECT object_identity_id, ancestor_id FROM acl_object_identity_ancestors');
        $this->addSql('DROP TABLE acl_object_identity_ancestors');
        $this->addSql('CREATE TABLE acl_object_identity_ancestors (object_identity_id INTEGER UNSIGNED NOT NULL, ancestor_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(object_identity_id, ancestor_id))');
        $this->addSql('INSERT INTO acl_object_identity_ancestors (object_identity_id, ancestor_id) SELECT object_identity_id, ancestor_id FROM __temp__acl_object_identity_ancestors');
        $this->addSql('DROP TABLE __temp__acl_object_identity_ancestors');
        $this->addSql('CREATE INDEX IDX_825DE2993D9AB4A6 ON acl_object_identity_ancestors (object_identity_id)');
        $this->addSql('CREATE INDEX IDX_825DE299C671CEA1 ON acl_object_identity_ancestors (ancestor_id)');
        $this->addSql('DROP INDEX IDX_2DFDA3CB6DE2013A');
        $this->addSql('DROP INDEX IDX_2DFDA3CB7DD8AC20');
        $this->addSql('CREATE TEMPORARY TABLE __temp__authors_books AS SELECT authors_id, books_id FROM authors_books');
        $this->addSql('DROP TABLE authors_books');
        $this->addSql('CREATE TABLE authors_books (authors_id INTEGER NOT NULL, books_id INTEGER NOT NULL, PRIMARY KEY(authors_id, books_id))');
        $this->addSql('INSERT INTO authors_books (authors_id, books_id) SELECT authors_id, books_id FROM __temp__authors_books');
        $this->addSql('DROP TABLE __temp__authors_books');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB6DE2013A ON authors_books (authors_id)');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB7DD8AC20 ON authors_books (books_id)');
        $this->addSql('DROP INDEX IDX_B3C77447A76ED395');
        $this->addSql('DROP INDEX IDX_B3C77447FE54D947');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fos_user_user_group AS SELECT user_id, group_id FROM fos_user_user_group');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INTEGER UNSIGNED NOT NULL, group_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('INSERT INTO fos_user_user_group (user_id, group_id) SELECT user_id, group_id FROM __temp__fos_user_user_group');
        $this->addSql('DROP TABLE __temp__fos_user_user_group');
        $this->addSql('CREATE INDEX IDX_B3C77447A76ED395 ON fos_user_user_group (user_id)');
        $this->addSql('CREATE INDEX IDX_B3C77447FE54D947 ON fos_user_user_group (group_id)');
    }
}
