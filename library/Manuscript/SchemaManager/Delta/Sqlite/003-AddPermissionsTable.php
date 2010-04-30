<?php

class AddPermissionsTable extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $sql = <<<SQL_END
        CREATE TABLE group_permissions_base (
            id VARCHAR(255) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL
        );

        CREATE INDEX "group_permissions_baseId" ON "group_permissions_base" ("id");
        CREATE UNIQUE INDEX "group_permissions_baseUniqueId" ON "group_permissions_base" ("id");

        INSERT INTO group_permissions_base (id, name, description) VALUES ('WIZARD', 'Super Users', 'Has full access to the system');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('USER_ADMIN', 'User Administration', 'Can add, edit, and disable users');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('GROUP_ADMIN', 'Group Administration', 'Can add, edit, and disable groups');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('SEE_ALL', 'See All Files', 'Can see all files, regardless of group permissions');
        INSERT INTO group_permissions_base (id, name, description) VALUES ('EDIT_ALL', 'Edit All Files', 'Can edit and delete all files, regardless of group permissions');

        CREATE TABLE group_permissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            group_id INTEGER NOT NULL,
            group_permission_key VARCHAR(255) NOT NULL
        );

        CREATE INDEX "group_permissionsId" ON "group_permissions" ("id");
        CREATE INDEX "group_permissionsGroupId" ON "group_permissions" ("group_id");
        CREATE INDEX "group_permissionsPermissionKey" ON "group_permissions" ("group_permission_key");

        INSERT INTO group_permissions (group_id, group_permission_key) VALUES (1, 'WIZARD');

        UPDATE config SET value = 3 WHERE key_name = 'version';
SQL_END;

        $this->_db->getConnection()->exec($sql);
    }
}
