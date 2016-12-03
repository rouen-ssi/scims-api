<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1480762528.
 * Generated on 2016-12-03 10:55:28 by antoine
 */
class PropelMigration_1480762528
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'scims' => '
BEGIN;

CREATE TABLE "account"
(
    "id" serial NOT NULL,
    "uid" VARCHAR(16) NOT NULL,
    "email" VARCHAR(254) NOT NULL,
    "first_name" VARCHAR(128) NOT NULL,
    "last_name" VARCHAR(128) NOT NULL,
    "biography" TEXT,
    "password" VARCHAR(255) NOT NULL,
    "token" VARCHAR(255),
    "token_expiration" INTEGER,
    PRIMARY KEY ("id","email"),
    CONSTRAINT "account_u_db2f7c" UNIQUE ("id")
);

CREATE TABLE "article"
(
    "id" serial NOT NULL,
    "account_id" INTEGER NOT NULL,
    "is_draft" BOOLEAN DEFAULT \'t\' NOT NULL,
    "title" VARCHAR(128) NOT NULL,
    "content" TEXT NOT NULL,
    "publication_date" INTEGER NOT NULL,
    "last_modification_date" INTEGER NOT NULL,
    "category_id" INTEGER DEFAULT -1,
    "subcategory_id" INTEGER DEFAULT -1,
    PRIMARY KEY ("id")
);

CREATE TABLE "highlighted_article"
(
    "account_id" INTEGER NOT NULL,
    "article_id" INTEGER NOT NULL,
    PRIMARY KEY ("account_id","article_id")
);

CREATE TABLE "category"
(
    "id" serial NOT NULL,
    "name" VARCHAR(32) NOT NULL,
    "parent_category_id" INTEGER DEFAULT -1,
    PRIMARY KEY ("id")
);

CREATE TABLE "comment"
(
    "id" serial NOT NULL,
    "parent_comment_id" INTEGER,
    "author_id" INTEGER NOT NULL,
    "article_id" INTEGER NOT NULL,
    "publication_date" INTEGER NOT NULL,
    "content" TEXT NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "article" ADD CONSTRAINT "article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_22957b"
    FOREIGN KEY ("parent_comment_id")
    REFERENCES "comment" ("id")
    ON DELETE CASCADE;

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_d5af8f"
    FOREIGN KEY ("author_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id")
    ON DELETE CASCADE;

COMMIT;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'scims' => '
BEGIN;

DROP TABLE IF EXISTS "account" CASCADE;

DROP TABLE IF EXISTS "article" CASCADE;

DROP TABLE IF EXISTS "highlighted_article" CASCADE;

DROP TABLE IF EXISTS "category" CASCADE;

DROP TABLE IF EXISTS "comment" CASCADE;

COMMIT;
',
);
    }

}