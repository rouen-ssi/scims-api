<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1481564109.
 * Generated on 2016-12-12 17:35:09 by mathieubrochard
 */
class PropelMigration_1481564109
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

ALTER TABLE "keyword"

  ADD "article_id" INTEGER;

ALTER TABLE "keyword" ADD CONSTRAINT "keyword_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

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

ALTER TABLE "keyword" DROP CONSTRAINT "keyword_fk_3610e9";

ALTER TABLE "keyword"

  DROP COLUMN "article_id";

COMMIT;
',
);
    }

}