<?php

class Application_Model_DbTable_Permissions extends Zend_Db_Table_Abstract
{
    protected $_name = 'group_permissions_base';

    public function associate($id, $perms)
    {
        $this->disassociate($id);

        $data = array();
        foreach($perms as $permission) {
            $data = array('group_id' => $id, 'group_permission_key' => $permission);
            $this->getDefaultAdapter()->insert('group_permissions', $data);
        }

    }

    public function disassociate($id)
    {
        return $this->getDefaultAdapter()->delete('group_permissions', 'group_id = '.(int)$id);
    }

    public function fetchPermissionsList($id = null)
    {
        $select = $this->select()->from(array('gpb' => $this->_name))->order('name ASC');

        if($id != null)
        {
            $select->join(array('gp' => 'group_permissions'), 'gp.group_permission_key = gpb.id', array('group_id', 'group_permission_key'))
                   ->where('gp.group_id = ?', $id)
                   ->setIntegrityCheck(false);
        }
        return $this->fetchAll($select)->toArray();
    }
}

