<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210703091057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE authors_books (authors_id INTEGER NOT NULL, books_id INTEGER NOT NULL, PRIMARY KEY(authors_id, books_id))');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB6DE2013A ON authors_books (authors_id)');
        $this->addSql('CREATE INDEX IDX_2DFDA3CB7DD8AC20 ON authors_books (books_id)');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, cover CLOB DEFAULT NULL, year INTEGER NOT NULL, file VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE fos_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B019DDB5E237E06 ON fos_group (name)');
        $this->addSql('CREATE TABLE fos_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data CLOB DEFAULT NULL --(DC2Type:json)
        , twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data CLOB DEFAULT NULL --(DC2Type:json)
        , gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data CLOB DEFAULT NULL --(DC2Type:json)
        , token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INTEGER UNSIGNED NOT NULL, group_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_B3C77447A76ED395 ON fos_user_user_group (user_id)');
        $this->addSql('CREATE INDEX IDX_B3C77447FE54D947 ON fos_user_user_group (group_id)');
        $this->addSql('CREATE TABLE acl_classes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_type VARCHAR(200) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_69DD750638A36066 ON acl_classes (class_type)');
        $this->addSql('CREATE TABLE acl_security_identities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifier VARCHAR(200) NOT NULL, username BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8835EE78772E836AF85E0677 ON acl_security_identities (identifier, username)');
        $this->addSql('CREATE TABLE acl_object_identities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_object_identity_id INTEGER UNSIGNED DEFAULT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL, entries_inheriting BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 ON acl_object_identities (object_identifier, class_id)');
        $this->addSql('CREATE INDEX IDX_9407E54977FA751A ON acl_object_identities (parent_object_identity_id)');
        $this->addSql('CREATE TABLE acl_object_identity_ancestors (object_identity_id INTEGER UNSIGNED NOT NULL, ancestor_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(object_identity_id, ancestor_id))');
        $this->addSql('CREATE INDEX IDX_825DE2993D9AB4A6 ON acl_object_identity_ancestors (object_identity_id)');
        $this->addSql('CREATE INDEX IDX_825DE299C671CEA1 ON acl_object_identity_ancestors (ancestor_id)');
        $this->addSql('CREATE TABLE acl_entries (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER UNSIGNED NOT NULL, object_identity_id INTEGER UNSIGNED DEFAULT NULL, security_identity_id INTEGER UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL, ace_order SMALLINT UNSIGNED NOT NULL, mask INTEGER NOT NULL, granting BOOLEAN NOT NULL, granting_strategy VARCHAR(30) NOT NULL, audit_success BOOLEAN NOT NULL, audit_failure BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 ON acl_entries (class_id, object_identity_id, field_name, ace_order)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 ON acl_entries (class_id, object_identity_id, security_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806EA000B10 ON acl_entries (class_id)');
        $this->addSql('CREATE INDEX IDX_46C8B8063D9AB4A6 ON acl_entries (object_identity_id)');
        $this->addSql('CREATE INDEX IDX_46C8B806DF9183C9 ON acl_entries (security_identity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE authors_books');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE fos_group');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('DROP TABLE acl_classes');
        $this->addSql('DROP TABLE acl_security_identities');
        $this->addSql('DROP TABLE acl_object_identities');
        $this->addSql('DROP TABLE acl_object_identity_ancestors');
        $this->addSql('DROP TABLE acl_entries');
    }
}
