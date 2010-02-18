-- Tags Database
-- This stores all the base tag information, but not relationships
CREATE TABLE tags (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL
);
CREATE INDEX "tagId" ON "tags" ("id");

-- Files Database
-- Holds all the metadata for files, but not tag relationships
CREATE TABLE files (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    originalFilename VARCHAR(255) NOT NULL,
    directory VARCHAR(255) NOT NULL,
    reference VARCHAR(255) NOT NULL,
    mimetype VARCHAR(50) NOT NULL,
    filesize INTEGER NOT NULL,
    dateUploaded DATETIME NOT NULL,
    author INTEGER NOT NULL,
    version INTEGER DEFAULT 1,
    title VARCHAR(255) NOT NULL
);
CREATE INDEX "fileId" ON "files" ("id");

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