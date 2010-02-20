/**
 * Tags Database
 * This stores all the base tag information, but not relationships
 */
CREATE TABLE [dbo].[tags] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [name] [varchar] (255) NOT NULL,
    CONSTRAINT [PK_tags] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

/**
 * Files Database
 * Header information for files
 */
CREATE TABLE [dbo].[files] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [directory] [varchar] (255) NOT NULL,
    [originalAuthor] [int] NOT NULL,
    [revision] [int] NOT NULL,
    [title] [varchar] (255) NOT NULL,
    [detailId] [int] NOT NULL,
    CONSTRAINT [PK_files] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

ALTER TABLE [dbo].[files] ADD CONSTRAINT [DF_files_revision] DEFAULT ((1)) FOR [revision]
GO

ALTER TABLE [dbo].[files] ADD CONSTRAINT [DF_files_detailId] DEFAULT ((1)) FOR [detailId]
GO

/**
 * Files to Tags Cross Reference 
 * Holds the relationships between files and their tags
 */
CREATE TABLE [dbo].[files_tags_xref] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [fileId] [int] NOT NULL,
    [tagId] [int] NOT NULL,
    CONSTRAINT [PK_files_tags_xref] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

/**
 * Comments Database 
 * Stores the comments for the files in the database
 */
CREATE TABLE [dbo].[comments] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [fileId] [int] NOT NULL,
    [version] [int] NOT NULL,
    [comment] [ntext] NOT NULL,
    [dateAdded] [datetime] NOT NULL,
    [author] [int] NOT NULL,
    CONSTRAINT [PK_comments] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

ALTER TABLE [dbo].[comments] ADD CONSTRAINT [DF_comments_dateAdded] DEFAULT (getdate()) FOR [dateAdded]
GO

/**
 * Users Database 
 * Holds all the user metadata
 */
CREATE TABLE [dbo].[users] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [username] [varchar] (255) NOT NULL,
    [password] [varchar] (255) NOT NULL,
    [name] [varchar] (255) NOT NULL,
    [email] [varchar] (255) NOT NULL,
    [active] [tinyint] NOT NULL,
    [primaryGroup] [int] NOT NULL,
    CONSTRAINT [PK_users] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

ALTER TABLE [dbo].[users] ADD CONSTRAINT [DF_users_active] DEFAULT ((1)) FOR [active]
GO

/**
 * Mimetypes Database 
 * Contains a translation table for mimetypes
 */
CREATE TABLE [dbo].[mimetypes] (
    [id] [int] IDENTITY(1,1) NOT NULL,
    [mimetype] [varchar] (255) NOT NULL,
    [description] [varchar] (255) NOT NULL,
    [editable] [tinyint] NOT NULL,
    CONSTRAINT [PK_mimetypes] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF, 
        STATISTICS_NORECOMPUTE = OFF, 
        IGNORE_DUP_KEY = OFF, 
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]
GO

ALTER TABLE [dbo].[editable] ADD CONSTRAINT [DF_mimetypes_editable] DEFAULT ((0)) FOR [active]
GO

CREATE VIEW [dbo].[new_mimetypes] AS
    SELECT
        DISTINCT fd.mimetype AS 'newMimetype'
    FROM
        files_detail AS fd
    WHERE
        NOT EXISTS(SELECT m.mimetype FROM mimetypes AS m WHERE fd.mimetype = m.mimetype);
