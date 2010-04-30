<?php
/**
 * Manuscript
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file licenses/manuscript-LICENSE.txt.
 * If you did not receive a copy of the license please send an email
 * to chris@tankersleywebsolutions.com so we can send you a copy immediately.
 *
 * @category   Manuscript
 * @package    Application_Model_Users
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */

/**
 * Class to interact with the User Groups information
 *
 * @category   Manuscript
 * @package    Application_Model_Groups
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */
class Application_Model_Groups
{
    /**
     * Backend DB Table
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * Backend Permission DB Table
     * @var Zend_Db_Table_Abstract
     */
    protected $_permissionsTable;

    /**
     * Inserts a group into the system
     * 
     * @param array $data
     * @return int
     */
    public function add($data)
    {
        $groupId = $this->getDbTable()->insert(array('name' => $data['name']));
        $this->getPermissionsTable()->associate($groupId, $data['permissions']);

        return $groupId;
    }

    /**
     * Returns a dataset of users based on the passed parameters
     * 
     * @param string $where
     * @param string $order
     * @param string $count
     * @param string $offset
     * @return Zend_Db_Rowset
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getDbTable()->fetchAll($where, $order, $count, $offset);
    }

    public function fetchPermissionsList($id = null)
    {
        return $this->getPermissionsTable()->fetchPermissionsList($id);
    }

    /**
     * Returns a specified group
     *
     * @param mixed $id
     * @returns Zend_Db_Row
     */
    public function find($id)
    {
        if( is_numeric($id) ) {
            $result = $this->getDbTable()->find($id);
            $file = $result->current();
        } else {
            $select = $this->getDbTable()->select()->where('name LIKE ?', $id);
            $file = $this->getDbTable()->fetchRow($select);
        }

        return $file;
    }

    /**
     * Sets and returns the backend table
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Groups');
        }

        return $this->_dbTable;
    }

    /**
     * Sets and returns the backend Permissionstable
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getPermissionsTable()
    {
        if($this->_permissionsTable === null) {
            $this->setPermissionsTable('Application_Model_DbTable_Permissions');
        }

        return $this->_permissionsTable;
    }

    /**
     * Sets the backend table object
     *
     * @param mixed $table
     */
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Groups Model');
        }
    }

    /**
     * Sets the backend Permissions table object
     *
     * @param mixed $table
     */
    public function setPermissionsTable($table)
    {
        if(is_string($table)) {
            $this->_permissionsTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_permissionsTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Groups Permissions Model');
        }
    }

    /**
     * Updates information for the specified group
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update($id, $data)
    {
        $group = $this->Find($id);
        $group->name = $data['name'];
        $group->save();

        $this->getPermissionsTable()->associate($id, $data['permissions']);
    }
}
