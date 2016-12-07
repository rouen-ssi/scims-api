<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1481117381.
 * Generated on 2016-12-07 14:29:41 by mbrochard
 */
class PropelMigration_1481117381
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

CREATE TABLE "highlighted_article"
(
    "account_id" INTEGER NOT NULL,
    "article_id" INTEGER NOT NULL,
    PRIMARY KEY ("account_id","article_id")
);

ALTER TABLE "article_view"

  DROP COLUMN "ip_address";

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
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

DROP TABLE IF EXISTS "highlighted_article" CASCADE;

ALTER TABLE "article_view"

  ADD "ip_address" VARCHAR(15) NOT NULL;

COMMIT;
',
);
    }

}