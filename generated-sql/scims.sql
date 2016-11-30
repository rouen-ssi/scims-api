
-----------------------------------------------------------------------
-- account
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [account];

CREATE TABLE [account]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [uid] VARCHAR(16) NOT NULL,
    [email] VARCHAR(254) NOT NULL,
    [first_name] VARCHAR(128) NOT NULL,
    [last_name] VARCHAR(128) NOT NULL,
    [biography] MEDIUMTEXT,
    [password] VARCHAR(255) NOT NULL,
    [token] VARCHAR(255),
    [token_expiration] INTEGER(8),
    UNIQUE ([id],[email])
);

-----------------------------------------------------------------------
-- article
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [article];

CREATE TABLE [article]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [account_id] INTEGER NOT NULL,
    [is_draft] INTEGER DEFAULT 1 NOT NULL,
    [title] VARCHAR(128) NOT NULL,
    [content] MEDIUMTEXT NOT NULL,
    [publication_date] INTEGER NOT NULL,
    [last_modification_date] INTEGER NOT NULL,
    [category_id] INTEGER DEFAULT -1,
    [subcategory_id] INTEGER DEFAULT -1,
    UNIQUE ([id]),
    FOREIGN KEY ([account_id]) REFERENCES [account] ([id])
        ON DELETE CASCADE
);

-----------------------------------------------------------------------
-- highlighted_article
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [highlighted_article];

CREATE TABLE [highlighted_article]
(
    [account_id] INTEGER NOT NULL,
    [article_id] INTEGER NOT NULL,
    PRIMARY KEY ([account_id],[article_id]),
    UNIQUE ([account_id],[article_id]),
    FOREIGN KEY ([account_id]) REFERENCES [account] ([id]),
    FOREIGN KEY ([article_id]) REFERENCES [article] ([id])
);

-----------------------------------------------------------------------
-- category
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [category];

CREATE TABLE [category]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(32) NOT NULL,
    [parent_category_id] INTEGER DEFAULT -1,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- comment
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [comment];

CREATE TABLE [comment]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [parent_comment_id] INTEGER,
    [author_id] INTEGER NOT NULL,
    [article_id] INTEGER NOT NULL,
    [publication_date] INTEGER NOT NULL,
    [content] MEDIUMTEXT NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([parent_comment_id]) REFERENCES [comment] ([id])
        ON DELETE CASCADE,
    FOREIGN KEY ([author_id]) REFERENCES [account] ([id])
        ON DELETE CASCADE,
    FOREIGN KEY ([article_id]) REFERENCES [article] ([id])
        ON DELETE CASCADE
);
