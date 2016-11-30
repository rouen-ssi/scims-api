<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1480523733.
 * Generated on 2016-11-30 17:35:33 by mbrochard
 */
class PropelMigration_1480523733
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

ALTER TABLE "article" DROP CONSTRAINT "article_fk_474870";

ALTER TABLE "article" ADD CONSTRAINT "article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

ALTER TABLE "comment" DROP CONSTRAINT "comment_fk_3610e9";

ALTER TABLE "comment" DROP CONSTRAINT "comment_fk_d5af8f";

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id")
    ON DELETE CASCADE;

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_d5af8f"
    FOREIGN KEY ("author_id")
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

ALTER TABLE "article" DROP CONSTRAINT "article_fk_474870";

ALTER TABLE "article" ADD CONSTRAINT "article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

ALTER TABLE "comment" DROP CONSTRAINT "comment_fk_3610e9";

ALTER TABLE "comment" DROP CONSTRAINT "comment_fk_d5af8f";

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "comment" ADD CONSTRAINT "comment_fk_d5af8f"
    FOREIGN KEY ("author_id")
    REFERENCES "account" ("id");

COMMIT;
',
);
    }

}