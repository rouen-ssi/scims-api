
BEGIN;

-----------------------------------------------------------------------
-- account
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "account" CASCADE;

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
    "role" VARCHAR DEFAULT 'user' NOT NULL,
    PRIMARY KEY ("id","email"),
    CONSTRAINT "account_u_db2f7c" UNIQUE ("id")
);

-----------------------------------------------------------------------
-- article
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "article" CASCADE;

CREATE TABLE "article"
(
    "id" serial NOT NULL,
    "account_id" INTEGER NOT NULL,
    "is_draft" BOOLEAN DEFAULT 't' NOT NULL,
    "title" VARCHAR(128) NOT NULL,
    "content" TEXT NOT NULL,
    "publication_date" INTEGER NOT NULL,
    "last_modification_date" INTEGER NOT NULL,
    "category_id" INTEGER DEFAULT -1,
    "subcategory_id" INTEGER DEFAULT -1,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- article_view
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "article_view" CASCADE;

CREATE TABLE "article_view"
(
    "id" serial NOT NULL,
    "article_id" INTEGER NOT NULL,
    "account_id" INTEGER,
    "date" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- highlighted_article
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "highlighted_article" CASCADE;

CREATE TABLE "highlighted_article"
(
    "account_id" INTEGER NOT NULL,
    "article_id" INTEGER NOT NULL,
    PRIMARY KEY ("account_id","article_id")
);

-----------------------------------------------------------------------
-- category
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "category" CASCADE;

CREATE TABLE "category"
(
    "id" serial NOT NULL,
    "name" VARCHAR(32) NOT NULL,
    "parent_category_id" INTEGER DEFAULT -1,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- comment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "comment" CASCADE;

CREATE TABLE "comment"
(
    "id" serial NOT NULL,
    "parent_comment_id" INTEGER DEFAULT -1,
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

ALTER TABLE "article_view" ADD CONSTRAINT "article_view_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id");

ALTER TABLE "article_view" ADD CONSTRAINT "article_view_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id")
    ON DELETE CASCADE;

ALTER TABLE "highlighted_article" ADD CONSTRAINT "highlighted_article_fk_3610e9"
    FOREIGN KEY ("article_id")
    REFERENCES "article" ("id")
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
