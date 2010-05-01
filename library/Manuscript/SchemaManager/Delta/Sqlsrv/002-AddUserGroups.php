<?php

class AddUserGroups extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $sql = <<<SQL_END
        ALTER TABLE groups ADD CONSTRAINT groups_nameUniqueId UNIQUE(name)
        Go

        INSERT INTO groups (name) VALUES ('Admin');
        UPDATE user_accounts SET primaryGroup = 1;
        UPDATE config SET value = 2 WHERE key_name = 'version';
SQL_END;

        sqlsrv_query($this->_db->getConnection(), $sql);;
    }
}
