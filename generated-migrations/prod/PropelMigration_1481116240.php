<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1481116240.
 * Generated on 2016-12-07 14:10:40 by mbrochard
 */
class PropelMigration_1481116240
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

ALTER TABLE "article_view"

  ADD "date" INTEGER NOT NULL,

  ADD "ip_address" VARCHAR(15) NOT NULL;

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

ALTER TABLE "article_view"

  DROP COLUMN "date",

  DROP COLUMN "ip_address";

COMMIT;
',
);
    }

}