<?php

class AddUserGroups extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $sql = <<<SQL_END
        CREATE UNIQUE INDEX "groups_nameUniqueId" ON "groups" ("name");

        INSERT INTO groups (name) VALUES ('Admin');
        UPDATE user_accounts SET primaryGroup = 1;
        UPDATE config SET value = 2 WHERE key_name = 'version';
SQL_END;

        $this->_db->getConnection()->exec($sql);
    }
}
