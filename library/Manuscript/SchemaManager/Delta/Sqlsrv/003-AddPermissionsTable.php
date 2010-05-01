<?php

class AddPermissionsTable extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $sql = <<<SQL_END
CREATE TABLE [dbo].[group_permissions_base] (
    [id] [varchar] (255)  NOT NULL,
    [name] [varchar] (255) NOT NULL,
    [description] [varchar] (255) NOT NULL,
    CONSTRAINT [group_permissions_baseId] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF,
        STATISTICS_NORECOMPUTE = OFF,
        IGNORE_DUP_KEY = OFF,
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]

ALTER TABLE group_permissions_base ADD CONSTRACT group_permissions_baseUniqueId UNIQUE(id)

        INSERT INTO group_permissions_base (id, name, description) VALUES ('WIZARD', 'Super Users', 'Has full access to the system');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('USER_ADMIN', 'User Administration', 'Can add, edit, and disable users');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('GROUP_ADMIN', 'Group Administration', 'Can add, edit, and disable groups');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('SEE_ALL', 'See All Files', 'Can see all files, regardless of group permissions');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('EDIT_ALL', 'Edit All Files', 'Can edit and delete all files, regardless of group permissions');

CREATE TABLE [dbo].[group_permissions_base] (
    [id] [int] IDENTITY(1,1)  NOT NULL,
    [group_id] [int] NOT NULL,
    [group_permission_key] [varchar[ (255) NOT NULL,
    CONSTRAINT [group_permissionsId] PRIMARY KEY CLUSTERED (
        [id] ASC
    ) WITH (
        PAD_INDEX = OFF,
        STATISTICS_NORECOMPUTE = OFF,
        IGNORE_DUP_KEY = OFF,
        ALLOW_ROW_LOCKS = ON )
    ON [PRIMARY] )
ON [PRIMARY]

        CREATE INDEX "group_permissionsGroupId" ON "group_permissions" ("group_id");
        CREATE INDEX "group_permissionsPermissionKey" ON "group_permissions" ("group_permission_key");

        INSERT INTO group_permissions (group_id, group_permission_key) VALUES (1, 'WIZARD');

        UPDATE config SET value = 3 WHERE key_name = 'version';
SQL_END;

        sqlsrv_query($this->_db->getConnection(), $sql);;
    }
}
