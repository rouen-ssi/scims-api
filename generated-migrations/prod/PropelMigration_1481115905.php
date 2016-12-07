<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1481115905.
 * Generated on 2016-12-07 14:05:05 by mbrochard
 */
class PropelMigration_1481115905
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

CREATE TABLE "article_view"
(
    "id" serial NOT NULL,
    "article_id" INTEGER NOT NULL,
    "account_id" INTEGER,
    PRIMARY KEY ("id")
);

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_3610e9";

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_474870";

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

ALTER TABLE "article_view" ADD CONSTRAINT "article_view_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "article_view" ADD CONSTRAINT "article_view_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

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

DROP TABLE IF EXISTS "article_view" CASCADE;

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_3610e9";

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_474870";

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id")
    ON DELETE CASCADE;

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

COMMIT;
',
);
    }

}