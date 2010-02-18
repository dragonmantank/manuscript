-- Tags Database
-- This stores all the base tag information, but not relationships
CREATE TABLE tags (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL
);
CREATE INDEX "tagId" ON "tags" ("id");

-- Files Database
-- Header information for files
CREATE TABLE files (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    filename VARCHAR(255) NOT NULL,
    directory VARCHAR(255) NOT NULL,
    originalAuthor INTEGER NOT NULL,
    revision INTEGER DEFAULT 1,
    title VARCHAR(255) NOT NULL,
    detailId INTEGER DEFAULT 0
);
CREATE INDEX "fileId" ON "files" ("id");

-- File Detail Database
-- Holds all the metadata for a file revision
CREATE TABLE files_detail (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    fileId INTEGER NOT NULL,
    fsFilename VARCHAR(255) NOT NULL,
    mimetype VARCHAR(255) NOT NULL,
    size INTEGER NOT NULL,
    dateUploaded DATETIME NOT NULL,
    author INTEGER NOT NULL
);
CREATE INDEX "files_detailId" ON "files_detail" ("id");

-- File to Tags Cross Reference
-- Holds the relationships between files and their tags
CREATE TABLE files_tags_xref (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    fileId INTEGER NOT NULL,
    tagId INTEGER NOT NULL
);
CREATE INDEX "file_tags_xrefId" ON "files_tags_xref" ("id");

-- Comments Database
-- Stores the comments for the files in the database
CREATE TABLE comments (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    fileId INTEGER NOT NULL,
    version INTEGER NOT NULL,
    comment TEXT NOT NULL,
    dateAdded DATETIME NOT NULL,
    author INTEGER NOT NULL
);
CREATE INDEX "commentId" ON "comments" ("id");

-- Users Database
-- Holds all the user metadata
CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    active BOOL DEFAULT 1,
    primaryGroup INTEGER NOT NULL
);
CREATE INDEX "usersId" ON "users" ("id");
CREATE UNIQUE INDEX "usersUniqueUsername" ON "users" ("username");
CREATE UNIQUE INDEX "usersUniqueEmail" ON "users" ("email");

-- Groups Database
-- Holds all the group informatin
CREATE TABLE groups (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL
);
CREATE INDEX "groupsId" ON "groups" ("id");