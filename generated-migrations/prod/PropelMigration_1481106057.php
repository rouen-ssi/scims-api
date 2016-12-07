<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1481106057.
 * Generated on 2016-12-07 11:20:57 by antoinechauvin
 */
class PropelMigration_1481106057
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

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_3610e9";

ALTER TABLE "highlighted_article" DROP CONSTRAINT "highlighted_article_fk_474870";

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

COMMIT;
',
);
    }

}